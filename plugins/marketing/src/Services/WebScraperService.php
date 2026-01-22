<?php

namespace Plugins\Marketing\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Plugins\Marketing\Models\MarketingScrapeJob;
use Plugins\Marketing\Models\MarketingScrapedData;
use Plugins\Marketing\Models\MarketingContact;
use Plugins\Marketing\Models\MarketingList;
use Plugins\Marketing\Models\MarketingRateLimit;
use Plugins\Marketing\Plugin;

class WebScraperService
{
    protected Client $client;
    protected array $config;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 30,
            'verify' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
            ],
        ]);
        
        $this->config = Plugin::getAntiSpamConfig();
    }

    public function runJob(MarketingScrapeJob $job): array
    {
        try {
            $job->start();
            
            $urls = json_decode($job->urls, true);
            if (!is_array($urls) || empty($urls)) {
                $job->fail('No valid URLs provided');
                return ['success' => false, 'message' => 'No valid URLs provided'];
            }

            $processed = 0;
            $contactsFound = 0;
            $errors = [];

            foreach ($urls as $url) {
                // Check rate limit
                if (!MarketingRateLimit::canScrape()) {
                    $errors[] = 'Rate limit reached, stopping scrape';
                    break;
                }

                try {
                    $result = $this->scrapeUrl($url);
                    MarketingRateLimit::incrementScrape();
                    
                    $scrapedData = MarketingScrapedData::create([
                        'job_id' => $job->id,
                        'url' => $url,
                        'domain' => $this->extractDomain($url),
                        'extracted_data' => $result['data'],
                        'status' => $result['success'] ? 'pending' : 'failed',
                        'error_message' => $result['error'] ?? null,
                    ]);

                    if ($result['success'] && !empty($result['data']['email'])) {
                        $contactsFound++;
                        
                        // Auto-create contact if configured
                        $settings = json_decode($job->settings ?? '{}', true);
                        if (!empty($settings['add_to_list'])) {
                            $contact = $scrapedData->toContact();
                            if ($contact) {
                                $list = MarketingList::find($settings['add_to_list']);
                                $list?->addContact($contact);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $errors[] = "Failed to scrape {$url}: " . $e->getMessage();
                    
                    MarketingScrapedData::create([
                        'job_id' => $job->id,
                        'url' => $url,
                        'domain' => $this->extractDomain($url),
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
                }

                $processed++;
                $job->incrementProcessed();
                
                // Small delay between requests to be polite
                usleep(500000); // 0.5 seconds
            }

            $job->update(['contacts_found' => $contactsFound]);
            
            if (empty($errors)) {
                $job->complete();
                return ['success' => true, 'message' => "Completed. Found {$contactsFound} contacts from {$processed} URLs."];
            } else {
                $job->update([
                    'status' => 'completed',
                    'error_log' => implode("\n", $errors),
                    'completed_at' => now(),
                ]);
                return ['success' => true, 'message' => "Completed with errors. Found {$contactsFound} contacts. Errors: " . count($errors)];
            }

        } catch (\Exception $e) {
            Log::error('Scrape job failed: ' . $e->getMessage());
            $job->fail($e->getMessage());
            return ['success' => false, 'message' => 'Job failed: ' . $e->getMessage()];
        }
    }

    public function scrapeUrl(string $url): array
    {
        try {
            // Normalize URL
            if (!preg_match('/^https?:\/\//', $url)) {
                $url = 'https://' . $url;
            }

            $response = $this->client->get($url);
            $html = (string) $response->getBody();
            
            $data = $this->extractContactInfo($html, $url);
            
            return [
                'success' => true,
                'data' => $data,
            ];

        } catch (RequestException $e) {
            return [
                'success' => false,
                'data' => [],
                'error' => 'HTTP Error: ' . ($e->getResponse()?->getStatusCode() ?? 'Connection failed'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'data' => [],
                'error' => $e->getMessage(),
            ];
        }
    }

    protected function extractContactInfo(string $html, string $url): array
    {
        $data = [
            'website' => $url,
            'domain' => $this->extractDomain($url),
        ];

        // Extract emails
        $emails = $this->extractEmails($html);
        if (!empty($emails)) {
            // Filter out blacklisted domains
            $blacklist = $this->config['blacklist_domains'] ?? [];
            $emails = array_filter($emails, function($email) use ($blacklist) {
                $domain = substr($email, strpos($email, '@') + 1);
                foreach ($blacklist as $blocked) {
                    if (str_ends_with($domain, $blocked)) {
                        return false;
                    }
                }
                // Also filter common non-personal emails
                $skipPatterns = ['noreply', 'no-reply', 'info@', 'support@', 'contact@', 'admin@', 'webmaster@'];
                foreach ($skipPatterns as $pattern) {
                    if (stripos($email, $pattern) !== false) {
                        return false;
                    }
                }
                return true;
            });
            
            if (!empty($emails)) {
                $data['email'] = reset($emails);
                $data['all_emails'] = array_values(array_unique($emails));
            }
        }

        // Extract phone numbers
        $phones = $this->extractPhones($html);
        if (!empty($phones)) {
            $data['phone'] = reset($phones);
            $data['all_phones'] = array_values(array_unique($phones));
        }

        // Extract company name from title or meta
        $data['company_name'] = $this->extractCompanyName($html);

        // Extract address
        $data['address'] = $this->extractAddress($html);

        // Try to find contact person name
        $data['contact_name'] = $this->extractContactName($html);

        return $data;
    }

    protected function extractEmails(string $html): array
    {
        $emails = [];
        
        // Standard email pattern
        $pattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';
        preg_match_all($pattern, $html, $matches);
        
        if (!empty($matches[0])) {
            foreach ($matches[0] as $email) {
                $email = strtolower(trim($email));
                // Basic validation
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // Skip image files and common false positives
                    if (!preg_match('/\.(png|jpg|jpeg|gif|svg|webp|css|js)$/i', $email)) {
                        $emails[] = $email;
                    }
                }
            }
        }

        // Also check mailto: links
        preg_match_all('/mailto:([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/i', $html, $mailtoMatches);
        if (!empty($mailtoMatches[1])) {
            foreach ($mailtoMatches[1] as $email) {
                $email = strtolower(trim($email));
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emails[] = $email;
                }
            }
        }

        return array_unique($emails);
    }

    protected function extractPhones(string $html): array
    {
        $phones = [];
        
        // Romanian phone patterns
        $patterns = [
            '/\+40\s?[0-9]{2,3}\s?[0-9]{3}\s?[0-9]{3,4}/', // +40 xxx xxx xxx
            '/0[27][0-9]{2}[\s.-]?[0-9]{3}[\s.-]?[0-9]{3}/', // 07xx xxx xxx or 02xx xxx xxx
            '/\(\s?0[27][0-9]{2}\s?\)\s?[0-9]{3}[\s.-]?[0-9]{3}/', // (0xxx) xxx xxx
            '/tel:[\s]*([+0-9\s.-]+)/i', // tel: links
        ];

        foreach ($patterns as $pattern) {
            preg_match_all($pattern, $html, $matches);
            if (!empty($matches[0])) {
                foreach ($matches[0] as $phone) {
                    $phone = preg_replace('/[^\d+]/', '', $phone);
                    if (strlen($phone) >= 10) {
                        $phones[] = $phone;
                    }
                }
            }
        }

        // International format
        preg_match_all('/\+[0-9]{1,3}[\s.-]?[0-9]{2,4}[\s.-]?[0-9]{3,4}[\s.-]?[0-9]{3,4}/', $html, $intlMatches);
        if (!empty($intlMatches[0])) {
            foreach ($intlMatches[0] as $phone) {
                $phone = preg_replace('/[^\d+]/', '', $phone);
                if (strlen($phone) >= 10) {
                    $phones[] = $phone;
                }
            }
        }

        return array_unique($phones);
    }

    protected function extractCompanyName(string $html): ?string
    {
        // Try title tag
        if (preg_match('/<title[^>]*>([^<]+)<\/title>/i', $html, $matches)) {
            $title = html_entity_decode(trim($matches[1]));
            // Clean up common suffixes
            $title = preg_replace('/\s*[-|–]\s*(Home|Acasa|Homepage|Welcome|Official|Site|Website).*$/i', '', $title);
            if (!empty($title) && strlen($title) < 100) {
                return $title;
            }
        }

        // Try og:site_name
        if (preg_match('/property=["\']og:site_name["\'][^>]*content=["\']([^"\']+)["\']/i', $html, $matches)) {
            return html_entity_decode(trim($matches[1]));
        }

        // Try company schema
        if (preg_match('/"name"\s*:\s*"([^"]+)"/', $html, $matches)) {
            $name = trim($matches[1]);
            if (!empty($name) && strlen($name) < 100) {
                return $name;
            }
        }

        return null;
    }

    protected function extractAddress(string $html): ?string
    {
        // Romanian address patterns
        $patterns = [
            '/(?:str\.|strada|bulevardul|bd\.|calea|aleea)[^<,]{10,80}/i',
            '/[^>]{5,}\s+nr\.?\s*\d+[^<]{0,50}/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $matches)) {
                $address = strip_tags(trim($matches[0]));
                $address = preg_replace('/\s+/', ' ', $address);
                if (strlen($address) > 10 && strlen($address) < 200) {
                    return $address;
                }
            }
        }

        // Look for address in schema.org
        if (preg_match('/"streetAddress"\s*:\s*"([^"]+)"/i', $html, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    protected function extractContactName(string $html): ?string
    {
        // Look for common contact patterns
        $patterns = [
            '/(?:contact|persoana\s+de\s+contact|administrator|director|manager)[:\s]+([A-Z][a-z]+\s+[A-Z][a-z]+)/i',
            '/(?:nume|name)[:\s]+([A-Z][a-z]+\s+[A-Z][a-z]+)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $matches)) {
                $name = trim($matches[1]);
                if (strlen($name) > 3 && strlen($name) < 50) {
                    return $name;
                }
            }
        }

        return null;
    }

    protected function extractDomain(string $url): string
    {
        $parsed = parse_url($url);
        return $parsed['host'] ?? $url;
    }

    /**
     * Scrape a list of URLs and return results without saving to database
     * Useful for preview/testing
     */
    public function previewScrape(array $urls, int $limit = 5): array
    {
        $results = [];
        $count = 0;

        foreach ($urls as $url) {
            if ($count >= $limit) break;
            
            $url = trim($url);
            if (empty($url)) continue;

            $result = $this->scrapeUrl($url);
            $results[] = [
                'url' => $url,
                'success' => $result['success'],
                'data' => $result['data'] ?? [],
                'error' => $result['error'] ?? null,
            ];

            $count++;
            usleep(300000); // 0.3 seconds delay
        }

        return $results;
    }
}
