<?php

namespace Plugins\Marketing\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Grid;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Filament\Clusters\Marketing;
use Plugins\Marketing\Models\MarketingContact;
use Plugins\Marketing\Models\MarketingList;
use Plugins\Marketing\Services\GooglePlacesService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmailHarvester extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationLabel = 'Email Harvester';
    protected static ?string $title = 'Email Harvester';
    protected static ?string $slug = 'email-harvester';
    protected static ?string $cluster = Marketing::class;
    protected static ?int $navigationSort = 0;

    protected static string $view = 'marketing::filament.pages.email-harvester';

    public ?array $data = [];
    public array $results = [];
    public array $stats = [];
    public bool $isHarvesting = false;

    public function mount(): void
    {
        $this->form->fill([
            'country' => 'Romania',
            'city' => 'București',
            'category' => 'restaurant',
            'keyword' => '',
            'radius' => 5000,
            'scan_websites' => true,
            'only_with_email' => true,
            'list_id' => null,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('📍 Location Settings')
                ->description('Select country and city to harvest business contacts')
                ->schema([
                    Grid::make(3)->schema([
                        Select::make('country')
                            ->label('Country')
                            ->options(['Romania' => '🇷🇴 Romania'])
                            ->default('Romania')
                            ->disabled(),

                        Select::make('city')
                            ->label('City')
                            ->options($this->getRomanianCities())
                            ->default('București')
                            ->searchable()
                            ->required(),

                        TextInput::make('radius')
                            ->label('Search Radius (meters)')
                            ->numeric()
                            ->default(5000)
                            ->minValue(500)
                            ->maxValue(50000)
                            ->suffix('m')
                            ->helperText('Max 50km'),
                    ]),
                ]),

            Section::make('🏢 Business Filters')
                ->description('Choose what type of businesses to find')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('category')
                            ->label('Business Category')
                            ->options($this->getBusinessCategories())
                            ->default('restaurant')
                            ->searchable()
                            ->required(),

                        TextInput::make('keyword')
                            ->label('Additional Keyword (Optional)')
                            ->placeholder('e.g., pizza, hotel, dentist')
                            ->helperText('Narrow down results'),
                    ]),
                ]),

            Section::make('⚙️ Harvest Options')
                ->schema([
                    Grid::make(3)->schema([
                        Toggle::make('scan_websites')
                            ->label('Scan Websites for Emails')
                            ->helperText('Extract emails from websites')
                            ->default(true),

                        Toggle::make('only_with_email')
                            ->label('Only Save with Email')
                            ->helperText('Skip without email')
                            ->default(true),

                        Select::make('list_id')
                            ->label('Add to List')
                            ->options(MarketingList::where('is_active', true)->pluck('name', 'id'))
                            ->placeholder('Select list (optional)'),
                    ]),
                ]),
        ])->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('harvest')
                ->label('🚀 Start Harvesting')
                ->color('success')
                ->size('lg')
                ->icon('heroicon-o-play')
                ->action('startHarvest')
                ->requiresConfirmation()
                ->modalHeading('Start Email Harvesting?')
                ->modalDescription('This will search Google Places and scan websites for emails.'),
        ];
    }

    public function startHarvest(): void
    {
        $data = $this->data;
        
        $this->isHarvesting = true;
        $this->results = [];
        $this->stats = [
            'places_found' => 0,
            'websites_scanned' => 0,
            'emails_found' => 0,
            'contacts_created' => 0,
            'contacts_updated' => 0,
            'skipped_no_email' => 0,
        ];

        try {
            $cities = $this->getRomanianCitiesWithCoords();
            $city = $data['city'] ?? 'București';
            $cityData = $cities[$city] ?? $cities['București'];
            
            $lat = $cityData['lat'];
            $lng = $cityData['lng'];
            $radius = min($data['radius'] ?? 5000, 50000);
            $category = $data['category'] ?? 'restaurant';
            $keyword = $data['keyword'] ?? null;

            $service = new GooglePlacesService();
            $places = $service->searchNearby($lat, $lng, $radius, $category, $keyword);
            
            $this->stats['places_found'] = count($places);

            foreach ($places as $place) {
                $result = [
                    'name' => $place['name'] ?? 'Unknown',
                    'address' => $place['formatted_address'] ?? $place['vicinity'] ?? '',
                    'phone' => $place['formatted_phone_number'] ?? $place['international_phone_number'] ?? null,
                    'website' => $place['website'] ?? null,
                    'email' => null,
                    'status' => 'pending',
                    'google_place_id' => $place['place_id'] ?? null,
                    'rating' => $place['rating'] ?? null,
                    'types' => $place['types'] ?? [],
                ];

                if (!empty($result['website']) && ($data['scan_websites'] ?? true)) {
                    $this->stats['websites_scanned']++;
                    
                    try {
                        $email = $this->extractEmailFromWebsite($result['website']);
                        if ($email) {
                            $result['email'] = $email;
                            $this->stats['emails_found']++;
                        }
                    } catch (\Exception $e) {
                        Log::warning("Failed to scan: " . $result['website']);
                    }
                    usleep(100000);
                }

                if (($data['only_with_email'] ?? true) && empty($result['email'])) {
                    $result['status'] = 'skipped_no_email';
                    $this->stats['skipped_no_email']++;
                    $this->results[] = $result;
                    continue;
                }

                $contactResult = $this->saveContact($result, $data['list_id'] ?? null, $city);
                $result['status'] = $contactResult['status'];
                
                if ($contactResult['status'] === 'created') {
                    $this->stats['contacts_created']++;
                } elseif ($contactResult['status'] === 'updated') {
                    $this->stats['contacts_updated']++;
                }

                $this->results[] = $result;
            }

            Notification::make()
                ->title('Harvesting Complete!')
                ->body("Found {$this->stats['places_found']} businesses, {$this->stats['emails_found']} emails. Created {$this->stats['contacts_created']} contacts.")
                ->success()
                ->duration(10000)
                ->send();

        } catch (\Exception $e) {
            Log::error('Email harvesting failed', ['error' => $e->getMessage()]);
            
            Notification::make()
                ->title('Harvesting Failed')
                ->body('Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }

        $this->isHarvesting = false;
    }

    protected function extractEmailFromWebsite(string $url): ?string
    {
        try {
            if (!str_starts_with($url, 'http')) {
                $url = 'https://' . $url;
            }

            $response = Http::timeout(10)
                ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; CarpathianBot/1.0)'])
                ->get($url);

            if (!$response->successful()) return null;

            $html = $response->body();
            $emailPattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';
            preg_match_all($emailPattern, $html, $matches);

            if (empty($matches[0])) {
                $contactUrls = ['contact', 'contacte', 'contact-us', 'despre', 'about'];
                $baseUrl = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
                
                foreach ($contactUrls as $contactPath) {
                    try {
                        $contactResponse = Http::timeout(5)
                            ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; CarpathianBot/1.0)'])
                            ->get($baseUrl . '/' . $contactPath);
                        
                        if ($contactResponse->successful()) {
                            preg_match_all($emailPattern, $contactResponse->body(), $matches);
                            if (!empty($matches[0])) break;
                        }
                    } catch (\Exception $e) { continue; }
                }
            }

            if (empty($matches[0])) return null;

            $excludePatterns = [
                'example.com', 'domain.com', 'wixpress.com', 'sentry.io',
                'google.com', 'facebook.com', 'wordpress.com', 'cloudflare.com',
                '.png', '.jpg', '.gif', '.css', '.js'
            ];

            foreach ($matches[0] as $email) {
                $email = strtolower(trim($email));
                $skip = false;
                foreach ($excludePatterns as $pattern) {
                    if (str_contains($email, $pattern)) { $skip = true; break; }
                }
                if (!$skip && filter_var($email, FILTER_VALIDATE_EMAIL)) return $email;
            }

            return null;
        } catch (\Exception $e) { return null; }
    }

    protected function saveContact(array $data, ?int $listId, string $city): array
    {
        $existing = null;
        if (!empty($data['email'])) {
            $existing = MarketingContact::where('email', $data['email'])->first();
        }
        if (!$existing && !empty($data['google_place_id'])) {
            $existing = MarketingContact::where('google_place_id', $data['google_place_id'])->first();
        }

        $contactData = [
            'company_name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'website' => $data['website'],
            'address' => $data['address'],
            'city' => $city,
            'country' => 'Romania',
            'source' => 'google_places',
            'google_place_id' => $data['google_place_id'],
            'google_rating' => $data['rating'],
            'tags' => $data['types'] ?? [],
            'status' => 'active',
            'has_consent' => false,
        ];

        if ($existing) {
            if (empty($existing->email) && !empty($contactData['email'])) {
                $existing->update($contactData);
            }
            $contact = $existing;
            $status = 'updated';
        } else {
            $contact = MarketingContact::create($contactData);
            $status = 'created';
        }

        if ($listId && $contact) {
            $list = MarketingList::find($listId);
            if ($list) $list->addContact($contact);
        }

        return ['status' => $status, 'contact' => $contact];
    }

    protected function getRomanianCities(): array
    {
        return [
            'București' => '🏙️ București',
            'Cluj-Napoca' => '🏔️ Cluj-Napoca',
            'Timișoara' => '🏛️ Timișoara',
            'Iași' => '🎓 Iași',
            'Constanța' => '🏖️ Constanța',
            'Craiova' => '🌳 Craiova',
            'Brașov' => '⛰️ Brașov',
            'Galați' => '🚢 Galați',
            'Ploiești' => '🛢️ Ploiești',
            'Oradea' => '🏰 Oradea',
            'Brăila' => '🌊 Brăila',
            'Arad' => '🏛️ Arad',
            'Pitești' => '🚗 Pitești',
            'Sibiu' => '🎭 Sibiu',
            'Bacău' => '✈️ Bacău',
            'Târgu Mureș' => '🎪 Târgu Mureș',
            'Baia Mare' => '⛏️ Baia Mare',
        ];
    }

    protected function getRomanianCitiesWithCoords(): array
    {
        return [
            'București' => ['lat' => 44.4268, 'lng' => 26.1025],
            'Cluj-Napoca' => ['lat' => 46.7712, 'lng' => 23.6236],
            'Timișoara' => ['lat' => 45.7489, 'lng' => 21.2087],
            'Iași' => ['lat' => 47.1585, 'lng' => 27.6014],
            'Constanța' => ['lat' => 44.1598, 'lng' => 28.6348],
            'Craiova' => ['lat' => 44.3302, 'lng' => 23.7949],
            'Brașov' => ['lat' => 45.6427, 'lng' => 25.5887],
            'Galați' => ['lat' => 45.4353, 'lng' => 28.0080],
            'Ploiești' => ['lat' => 44.9365, 'lng' => 26.0254],
            'Oradea' => ['lat' => 47.0465, 'lng' => 21.9189],
            'Brăila' => ['lat' => 45.2692, 'lng' => 27.9574],
            'Arad' => ['lat' => 46.1866, 'lng' => 21.3123],
            'Pitești' => ['lat' => 44.8565, 'lng' => 24.8692],
            'Sibiu' => ['lat' => 45.7983, 'lng' => 24.1256],
            'Bacău' => ['lat' => 46.5670, 'lng' => 26.9146],
            'Târgu Mureș' => ['lat' => 46.5386, 'lng' => 24.5579],
            'Baia Mare' => ['lat' => 47.6567, 'lng' => 23.5850],
        ];
    }

    protected function getBusinessCategories(): array
    {
        return [
            'restaurant' => '🍽️ Restaurante',
            'cafe' => '☕ Cafenele',
            'bar' => '🍺 Baruri',
            'bakery' => '🥖 Patiserii',
            'beauty_salon' => '💇 Saloane Frumusețe',
            'hair_care' => '✂️ Frizerii',
            'spa' => '🧖 Spa / Wellness',
            'gym' => '💪 Săli Fitness',
            'dentist' => '🦷 Stomatologie',
            'doctor' => '👨‍⚕️ Cabinete Medicale',
            'pharmacy' => '💊 Farmacii',
            'lawyer' => '⚖️ Avocați',
            'accounting' => '📊 Contabilitate',
            'real_estate_agency' => '🏠 Imobiliare',
            'car_repair' => '🔧 Service Auto',
            'car_dealer' => '🚗 Dealeri Auto',
            'hotel' => '🏨 Hoteluri',
            'lodging' => '🏨 Pensiuni',
            'clothing_store' => '👗 Magazine Haine',
            'electronics_store' => '📱 Electronice',
            'furniture_store' => '🛋️ Mobilă',
            'florist' => '💐 Florării',
            'pet_store' => '🐾 Pet Shop',
            'veterinary_care' => '🐕 Veterinar',
            'supermarket' => '🛒 Supermarket',
            'establishment' => '🏢 Toate Afacerile',
        ];
    }
}
