<?php

namespace Plugins\Marketing\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Plugins\Marketing\Models\MarketingContact;
use Plugins\Marketing\Models\MarketingList;

class HarvestContactsCommand extends Command
{
    protected $signature = 'marketing:harvest 
                            {--target=500 : Target number of contacts to harvest}
                            {--city=Cluj-Napoca : Starting city}
                            {--radius=300 : Radius in km}
                            {--delay=2 : Delay between requests in seconds}';
    
    protected $description = 'Harvest business contacts from Romanian web directories';

    protected array $stats = [
        'pages_scanned' => 0,
        'businesses_found' => 0,
        'websites_scanned' => 0,
        'emails_found' => 0,
        'contacts_created' => 0,
        'duplicates_skipped' => 0,
        'no_email_skipped' => 0,
    ];

    protected array $categories = [
        'restaurant' => ['restaurant', 'restaurante', 'pizzerie', 'grill', 'bistro', 'trattoria'],
        'cafe' => ['cafenea', 'coffee', 'cafe', 'cofetarie', 'patiserie'],
        'bar' => ['bar', 'pub', 'cocktail', 'lounge'],
        'shop' => ['magazin', 'boutique', 'shop', 'store', 'comercial'],
    ];

    // Cities within 300km of Cluj-Napoca, sorted by distance
    protected array $citiesFromCluj = [
        ['name' => 'Cluj-Napoca', 'lat' => 46.7712, 'lng' => 23.6236, 'distance' => 0],
        ['name' => 'Turda', 'lat' => 46.5677, 'lng' => 23.7850, 'distance' => 25],
        ['name' => 'Dej', 'lat' => 47.1419, 'lng' => 23.8739, 'distance' => 45],
        ['name' => 'Gherla', 'lat' => 47.0314, 'lng' => 23.9106, 'distance' => 35],
        ['name' => 'Câmpia Turzii', 'lat' => 46.5500, 'lng' => 23.8833, 'distance' => 30],
        ['name' => 'Bistrița', 'lat' => 47.1333, 'lng' => 24.5000, 'distance' => 80],
        ['name' => 'Zalău', 'lat' => 47.1833, 'lng' => 23.0500, 'distance' => 85],
        ['name' => 'Baia Mare', 'lat' => 47.6567, 'lng' => 23.5850, 'distance' => 100],
        ['name' => 'Târgu Mureș', 'lat' => 46.5386, 'lng' => 24.5579, 'distance' => 105],
        ['name' => 'Satu Mare', 'lat' => 47.7833, 'lng' => 22.8833, 'distance' => 130],
        ['name' => 'Alba Iulia', 'lat' => 46.0667, 'lng' => 23.5833, 'distance' => 80],
        ['name' => 'Mediaș', 'lat' => 46.1667, 'lng' => 24.3500, 'distance' => 90],
        ['name' => 'Sibiu', 'lat' => 45.7983, 'lng' => 24.1256, 'distance' => 115],
        ['name' => 'Sighișoara', 'lat' => 46.2167, 'lng' => 24.7833, 'distance' => 120],
        ['name' => 'Oradea', 'lat' => 47.0465, 'lng' => 21.9189, 'distance' => 155],
        ['name' => 'Deva', 'lat' => 45.8833, 'lng' => 22.9000, 'distance' => 130],
        ['name' => 'Hunedoara', 'lat' => 45.7500, 'lng' => 22.9000, 'distance' => 140],
        ['name' => 'Petroșani', 'lat' => 45.4167, 'lng' => 23.3667, 'distance' => 165],
        ['name' => 'Arad', 'lat' => 46.1866, 'lng' => 21.3123, 'distance' => 200],
        ['name' => 'Timișoara', 'lat' => 45.7489, 'lng' => 21.2087, 'distance' => 250],
        ['name' => 'Brașov', 'lat' => 45.6427, 'lng' => 25.5887, 'distance' => 200],
        ['name' => 'Reghin', 'lat' => 46.7833, 'lng' => 24.7000, 'distance' => 95],
        ['name' => 'Sebeș', 'lat' => 45.9500, 'lng' => 23.5667, 'distance' => 95],
        ['name' => 'Aiud', 'lat' => 46.3000, 'lng' => 23.7167, 'distance' => 55],
        ['name' => 'Blaj', 'lat' => 46.1667, 'lng' => 23.9167, 'distance' => 70],
        ['name' => 'Sighetu Marmației', 'lat' => 47.9333, 'lng' => 23.8833, 'distance' => 135],
        ['name' => 'Beclean', 'lat' => 47.1833, 'lng' => 24.1833, 'distance' => 60],
        ['name' => 'Năsăud', 'lat' => 47.2833, 'lng' => 24.4000, 'distance' => 75],
    ];

    protected int $targetContacts;
    protected int $delay;
    protected ?MarketingList $harvestList = null;

    public function handle(): int
    {
        $this->targetContacts = (int) $this->option('target');
        $this->delay = (int) $this->option('delay');
        $radius = (int) $this->option('radius');

        $this->info("🚀 Starting Business Contact Harvester");
        $this->info("   Target: {$this->targetContacts} contacts");
        $this->info("   Starting city: Cluj-Napoca");
        $this->info("   Radius: {$radius}km");
        $this->info("   Delay: {$this->delay}s between requests");
        $this->newLine();

        // Create or get harvest list
        $this->harvestList = MarketingList::firstOrCreate(
            ['name' => 'Web Harvest - ' . now()->format('Y-m-d')],
            ['description' => 'Contacts harvested from web on ' . now()->format('Y-m-d H:i'), 'is_active' => true]
        );
        $this->info("📋 Saving to list: {$this->harvestList->name}");
        $this->newLine();

        // Filter cities by radius
        $cities = array_filter($this->citiesFromCluj, fn($c) => $c['distance'] <= $radius);
        usort($cities, fn($a, $b) => $a['distance'] <=> $b['distance']);

        $progressBar = $this->output->createProgressBar($this->targetContacts);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% -- %message%');
        $progressBar->setMessage('Starting...');
        $progressBar->start();

        // Harvest from different sources
        foreach ($cities as $city) {
            if ($this->stats['contacts_created'] >= $this->targetContacts) {
                break;
            }

            $progressBar->setMessage("📍 {$city['name']} ({$city['distance']}km)");

            foreach ($this->categories as $categoryKey => $keywords) {
                if ($this->stats['contacts_created'] >= $this->targetContacts) {
                    break 2;
                }

                // Try Pagini Aurii (Romanian Yellow Pages)
                $this->harvestFromPaginiAurii($city['name'], $categoryKey, $progressBar);
                
                // Try Firme.info
                $this->harvestFromFirmeInfo($city['name'], $categoryKey, $progressBar);
                
                // Try direct web search scraping
                $this->harvestFromWebSearch($city['name'], $keywords, $progressBar);
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        // Print stats
        $this->info("📊 Harvesting Complete!");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Pages Scanned', $this->stats['pages_scanned']],
                ['Businesses Found', $this->stats['businesses_found']],
                ['Websites Scanned', $this->stats['websites_scanned']],
                ['Emails Found', $this->stats['emails_found']],
                ['Contacts Created', $this->stats['contacts_created']],
                ['Duplicates Skipped', $this->stats['duplicates_skipped']],
                ['No Email Skipped', $this->stats['no_email_skipped']],
            ]
        );

        return Command::SUCCESS;
    }

    protected function harvestFromPaginiAurii(string $city, string $category, $progressBar): void
    {
        $citySlug = $this->slugify($city);
        $url = "https://www.paginiaurii.ro/{$category}/{$citySlug}";
        
        try {
            $html = $this->fetchPage($url);
            if (!$html) return;
            
            $this->stats['pages_scanned']++;
            
            // Extract business listings
            preg_match_all('/<div[^>]*class="[^"]*listing[^"]*"[^>]*>(.*?)<\/div>/is', $html, $matches);
            
            // Also try to find business cards
            preg_match_all('/href="(\/firma\/[^"]+)"/i', $html, $firmLinks);
            
            foreach ($firmLinks[1] ?? [] as $firmLink) {
                if ($this->stats['contacts_created'] >= $this->targetContacts) return;
                
                $this->processFirmPage("https://www.paginiaurii.ro{$firmLink}", $city, $category, $progressBar);
                sleep($this->delay);
            }
        } catch (\Exception $e) {
            Log::warning("PaginiAurii error: " . $e->getMessage());
        }
    }

    protected function harvestFromFirmeInfo(string $city, string $category, $progressBar): void
    {
        $citySlug = $this->slugify($city);
        $url = "https://www.firme.info/cautare/{$category}-{$citySlug}.html";
        
        try {
            $html = $this->fetchPage($url);
            if (!$html) return;
            
            $this->stats['pages_scanned']++;
            
            // Extract firm links
            preg_match_all('/href="(https?:\/\/www\.firme\.info\/[^"]+\.html)"/i', $html, $matches);
            
            foreach (array_unique($matches[1] ?? []) as $firmUrl) {
                if ($this->stats['contacts_created'] >= $this->targetContacts) return;
                if (strpos($firmUrl, '/cautare/') !== false) continue;
                
                $this->processFirmPage($firmUrl, $city, $category, $progressBar);
                sleep($this->delay);
            }
        } catch (\Exception $e) {
            Log::warning("FirmeInfo error: " . $e->getMessage());
        }
    }

    protected function harvestFromWebSearch(string $city, array $keywords, $progressBar): void
    {
        foreach ($keywords as $keyword) {
            if ($this->stats['contacts_created'] >= $this->targetContacts) return;
            
            $searchQuery = urlencode("{$keyword} {$city} contact email");
            
            // Try DuckDuckGo HTML (more permissive for scraping)
            $url = "https://html.duckduckgo.com/html/?q={$searchQuery}";
            
            try {
                $html = $this->fetchPage($url);
                if (!$html) continue;
                
                $this->stats['pages_scanned']++;
                
                // Extract result links
                preg_match_all('/href="([^"]+)"[^>]*>.*?<\/a>/is', $html, $matches);
                
                $processedDomains = [];
                foreach ($matches[1] ?? [] as $resultUrl) {
                    if ($this->stats['contacts_created'] >= $this->targetContacts) return;
                    
                    // Skip search engine and social media links
                    if (preg_match('/(google|bing|yahoo|facebook|instagram|twitter|youtube|linkedin|duckduckgo|wikipedia)/i', $resultUrl)) {
                        continue;
                    }
                    
                    // Extract actual URL from DuckDuckGo redirect
                    if (preg_match('/uddg=([^&]+)/', $resultUrl, $uddgMatch)) {
                        $resultUrl = urldecode($uddgMatch[1]);
                    }
                    
                    if (!filter_var($resultUrl, FILTER_VALIDATE_URL)) continue;
                    
                    $domain = parse_url($resultUrl, PHP_URL_HOST);
                    if (in_array($domain, $processedDomains)) continue;
                    $processedDomains[] = $domain;
                    
                    $this->processBusinessWebsite($resultUrl, $city, $keyword, $progressBar);
                    sleep($this->delay);
                }
            } catch (\Exception $e) {
                Log::warning("WebSearch error: " . $e->getMessage());
            }
            
            sleep($this->delay);
        }
    }

    protected function processFirmPage(string $url, string $city, string $category, $progressBar): void
    {
        try {
            $html = $this->fetchPage($url);
            if (!$html) return;
            
            $this->stats['businesses_found']++;
            
            // Extract business info
            $name = $this->extractBusinessName($html);
            $phone = $this->extractPhone($html);
            $address = $this->extractAddress($html);
            $website = $this->extractWebsite($html);
            $email = $this->extractEmail($html);
            
            // If no email on directory page, try the website
            if (!$email && $website) {
                $this->stats['websites_scanned']++;
                $email = $this->scrapeEmailFromWebsite($website);
            }
            
            if ($email) {
                $this->stats['emails_found']++;
                $this->saveContact($name, $email, $phone, $website, $address, $city, $category, $progressBar);
            } else {
                $this->stats['no_email_skipped']++;
            }
        } catch (\Exception $e) {
            Log::warning("ProcessFirm error: " . $e->getMessage());
        }
    }

    protected function processBusinessWebsite(string $url, string $city, string $category, $progressBar): void
    {
        try {
            $this->stats['websites_scanned']++;
            
            $html = $this->fetchPage($url);
            if (!$html) return;
            
            $this->stats['businesses_found']++;
            
            $domain = parse_url($url, PHP_URL_HOST);
            $name = $this->extractBusinessNameFromWebsite($html, $domain);
            $email = $this->extractEmail($html);
            $phone = $this->extractPhone($html);
            
            // If no email on homepage, try contact page
            if (!$email) {
                $contactUrl = $this->findContactPage($html, $url);
                if ($contactUrl) {
                    sleep(1);
                    $contactHtml = $this->fetchPage($contactUrl);
                    if ($contactHtml) {
                        $email = $this->extractEmail($contactHtml);
                        if (!$phone) $phone = $this->extractPhone($contactHtml);
                    }
                }
            }
            
            if ($email) {
                $this->stats['emails_found']++;
                $this->saveContact($name, $email, $phone, $url, null, $city, $category, $progressBar);
            } else {
                $this->stats['no_email_skipped']++;
            }
        } catch (\Exception $e) {
            Log::warning("ProcessWebsite error for {$url}: " . $e->getMessage());
        }
    }

    protected function scrapeEmailFromWebsite(string $url): ?string
    {
        try {
            if (!str_starts_with($url, 'http')) {
                $url = 'https://' . $url;
            }
            
            $html = $this->fetchPage($url);
            if (!$html) return null;
            
            $email = $this->extractEmail($html);
            
            if (!$email) {
                $contactUrl = $this->findContactPage($html, $url);
                if ($contactUrl) {
                    sleep(1);
                    $contactHtml = $this->fetchPage($contactUrl);
                    if ($contactHtml) {
                        $email = $this->extractEmail($contactHtml);
                    }
                }
            }
            
            return $email;
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function findContactPage(string $html, string $baseUrl): ?string
    {
        $patterns = [
            '/href=["\']([^"\']*contact[^"\']*)["\']/',
            '/href=["\']([^"\']*contacte[^"\']*)["\']/',
            '/href=["\']([^"\']*despre[^"\']*)["\']/',
            '/href=["\']([^"\']*about[^"\']*)["\']/',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern . 'i', $html, $match)) {
                $contactPath = $match[1];
                
                if (str_starts_with($contactPath, 'http')) {
                    return $contactPath;
                }
                
                $parsedBase = parse_url($baseUrl);
                $base = $parsedBase['scheme'] . '://' . $parsedBase['host'];
                
                if (str_starts_with($contactPath, '/')) {
                    return $base . $contactPath;
                }
                
                return $base . '/' . $contactPath;
            }
        }
        
        return null;
    }

    protected function extractEmail(string $html): ?string
    {
        // Decode HTML entities first
        $html = html_entity_decode($html);
        
        // Multiple patterns for email extraction
        $patterns = [
            '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/',
            '/mailto:([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/',
        ];
        
        $excludePatterns = [
            'example.com', 'domain.com', 'email.com', 'test.com',
            'wix.com', 'wixpress.com', 'sentry.io', 'google.com',
            'facebook.com', 'wordpress.com', 'cloudflare.com',
            '.png', '.jpg', '.gif', '.css', '.js', '.svg',
            'noreply', 'no-reply', 'mailer-daemon',
        ];
        
        foreach ($patterns as $pattern) {
            preg_match_all($pattern, $html, $matches);
            $emails = $matches[1] ?? $matches[0] ?? [];
            
            foreach ($emails as $email) {
                $email = strtolower(trim($email));
                
                $skip = false;
                foreach ($excludePatterns as $exclude) {
                    if (str_contains($email, $exclude)) {
                        $skip = true;
                        break;
                    }
                }
                
                if (!$skip && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return $email;
                }
            }
        }
        
        return null;
    }

    protected function extractPhone(string $html): ?string
    {
        // Romanian phone patterns
        $patterns = [
            '/(?:tel|phone|telefon)[:\s]*([+]?[0-9\s\-\.]{10,20})/i',
            '/\b(0[237][0-9]{8})\b/',
            '/\b(\+40[237][0-9]{8})\b/',
            '/\b(0[237][0-9]{2}[\s\.\-]?[0-9]{3}[\s\.\-]?[0-9]{3})\b/',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $match)) {
                $phone = preg_replace('/[^\d+]/', '', $match[1]);
                if (strlen($phone) >= 10) {
                    return $phone;
                }
            }
        }
        
        return null;
    }

    protected function extractWebsite(string $html): ?string
    {
        if (preg_match('/(?:website|site|web)[:\s]*<a[^>]*href=["\']([^"\']+)["\']/', $html, $match)) {
            return $match[1];
        }
        
        if (preg_match('/href=["\']?(https?:\/\/(?!(?:www\.)?(?:facebook|instagram|twitter|linkedin|youtube))[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})["\']?/i', $html, $match)) {
            return $match[1];
        }
        
        return null;
    }

    protected function extractAddress(string $html): ?string
    {
        if (preg_match('/(?:adresa|address)[:\s]*([^<]{10,100})/i', $html, $match)) {
            return trim(strip_tags($match[1]));
        }
        return null;
    }

    protected function extractBusinessName(string $html): string
    {
        // Try title tag
        if (preg_match('/<title>([^<]+)<\/title>/i', $html, $match)) {
            $title = trim(strip_tags($match[1]));
            $title = preg_replace('/\s*[-|–]\s*.+$/', '', $title);
            if (strlen($title) > 3 && strlen($title) < 100) {
                return $title;
            }
        }
        
        // Try h1
        if (preg_match('/<h1[^>]*>([^<]+)<\/h1>/i', $html, $match)) {
            return trim(strip_tags($match[1]));
        }
        
        return 'Unknown Business';
    }

    protected function extractBusinessNameFromWebsite(string $html, string $domain): string
    {
        $name = $this->extractBusinessName($html);
        
        if ($name === 'Unknown Business') {
            // Use domain name as fallback
            $name = preg_replace('/^www\./', '', $domain);
            $name = preg_replace('/\.[a-z]+$/', '', $name);
            $name = ucfirst($name);
        }
        
        return $name;
    }

    protected function saveContact(
        string $name,
        string $email,
        ?string $phone,
        ?string $website,
        ?string $address,
        string $city,
        string $category,
        $progressBar
    ): void {
        // Check for duplicates
        $exists = MarketingContact::where('email', $email)->exists();
        
        if ($exists) {
            $this->stats['duplicates_skipped']++;
            return;
        }
        
        // Also check by name + city
        $existsByName = MarketingContact::where('company_name', $name)
            ->where('city', $city)
            ->exists();
            
        if ($existsByName) {
            $this->stats['duplicates_skipped']++;
            return;
        }

        try {
            $contact = MarketingContact::create([
                'company_name' => $name,
                'email' => $email,
                'phone' => $phone,
                'website' => $website,
                'address' => $address,
                'city' => $city,
                'country' => 'Romania',
                'source' => 'web_harvest',
                'tags' => [$category],
                'status' => 'active',
                'has_consent' => false,
            ]);

            // Add to harvest list
            if ($this->harvestList) {
                DB::table('marketing_contact_list')->insert([
                    'marketing_contact_id' => $contact->id,
                    'marketing_list_id' => $this->harvestList->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $this->stats['contacts_created']++;
            $progressBar->setMessage("✓ {$name} ({$email})");
            $progressBar->advance();
            
        } catch (\Exception $e) {
            Log::warning("Failed to save contact: " . $e->getMessage());
        }
    }

    protected function fetchPage(string $url): ?string
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'ro-RO,ro;q=0.9,en;q=0.8',
                ])
                ->get($url);

            if ($response->successful()) {
                return $response->body();
            }
        } catch (\Exception $e) {
            // Silent fail
        }
        
        return null;
    }

    protected function slugify(string $text): string
    {
        $text = mb_strtolower($text);
        $text = str_replace(
            ['ă', 'â', 'î', 'ș', 'ț', 'Ă', 'Â', 'Î', 'Ș', 'Ț', ' ', '-'],
            ['a', 'a', 'i', 's', 't', 'a', 'a', 'i', 's', 't', '-', '-'],
            $text
        );
        return preg_replace('/[^a-z0-9-]/', '', $text);
    }
}
