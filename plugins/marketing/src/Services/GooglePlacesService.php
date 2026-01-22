<?php

namespace Plugins\Marketing\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Plugins\Marketing\Models\MarketingContact;
use Plugins\Marketing\Models\MarketingScrapeJob;

class GooglePlacesService
{
    protected Client $client;
    protected string $apiKey;
    protected string $baseUrl = 'https://maps.googleapis.com/maps/api/place';

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.google_places.api_key', 'AIzaSyAtVBC21Kka7EEVFhyijbO7wazwTNjX_LQ');
    }

    /**
     * Search for places by location and type
     */
    public function searchPlaces(float $lat, float $lng, int $radius = 1000, string $type = 'establishment', ?string $keyword = null): array
    {
        try {
            $params = [
                'location' => "{$lat},{$lng}",
                'radius' => $radius,
                'type' => $type,
                'key' => $this->apiKey
            ];

            if ($keyword) {
                $params['keyword'] = $keyword;
            }

            $response = $this->client->get("{$this->baseUrl}/nearbysearch/json", [
                'query' => $params
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] !== 'OK') {
                throw new \Exception("Google Places API error: {$data['status']} - " . ($data['error_message'] ?? ''));
            }

            return $data['results'];
        } catch (\Exception $e) {
            Log::error('Google Places search failed', [
                'error' => $e->getMessage(),
                'location' => "{$lat},{$lng}",
                'type' => $type
            ]);
            throw $e;
        }
    }

    /**
     * Get detailed information about a place
     */
    public function getPlaceDetails(string $placeId): array
    {
        try {
            $response = $this->client->get("{$this->baseUrl}/details/json", [
                'query' => [
                    'place_id' => $placeId,
                    'fields' => 'name,formatted_address,formatted_phone_number,international_phone_number,website,business_status,rating,user_ratings_total,opening_hours,types,geometry',
                    'key' => $this->apiKey
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] !== 'OK') {
                throw new \Exception("Google Places API error: {$data['status']} - " . ($data['error_message'] ?? ''));
            }

            return $data['result'];
        } catch (\Exception $e) {
            Log::error('Google Places details failed', [
                'error' => $e->getMessage(),
                'place_id' => $placeId
            ]);
            throw $e;
        }
    }

    /**
     * Search and convert places to marketing contacts
     */
    public function searchAndCreateContacts(
        float $lat,
        float $lng,
        int $radius = 1000,
        string $type = 'establishment',
        ?string $keyword = null,
        ?int $listId = null
    ): array {
        $places = $this->searchPlaces($lat, $lng, $radius, $type, $keyword);
        $contacts = [];
        $created = 0;
        $updated = 0;

        foreach ($places as $place) {
            try {
                // Get detailed information
                $details = $this->getPlaceDetails($place['place_id']);
                
                // Extract contact information
                $name = $details['name'] ?? $place['name'];
                $email = $this->extractEmailFromWebsite($details['website'] ?? null);
                $phone = $this->cleanPhoneNumber($details['international_phone_number'] ?? $details['formatted_phone_number'] ?? null);
                $address = $details['formatted_address'] ?? $place['vicinity'] ?? null;
                $website = $details['website'] ?? null;

                // Create/update contact
                $contact = MarketingContact::updateOrCreate(
                    [
                        'email' => $email ?: "noemail+{$place['place_id']}@placeholder.com"
                    ],
                    [
                        'name' => $name,
                        'phone' => $phone,
                        'company' => $name,
                        'address' => $address,
                        'website' => $website,
                        'source' => 'google_places',
                        'notes' => json_encode([
                            'place_id' => $place['place_id'],
                            'rating' => $details['rating'] ?? null,
                            'user_ratings_total' => $details['user_ratings_total'] ?? null,
                            'business_status' => $details['business_status'] ?? null,
                            'types' => $details['types'] ?? $place['types'] ?? []
                        ]),
                        'tags' => json_encode(array_merge(
                            [$type],
                            $details['types'] ?? $place['types'] ?? []
                        ))
                    ]
                );

                if ($contact->wasRecentlyCreated) {
                    $created++;
                } else {
                    $updated++;
                }

                // Add to list if specified
                if ($listId && $contact->exists) {
                    $contact->lists()->syncWithoutDetaching([$listId]);
                }

                $contacts[] = $contact;

                // Small delay to respect API limits
                usleep(100000); // 0.1 second

            } catch (\Exception $e) {
                Log::warning('Failed to process place', [
                    'place_id' => $place['place_id'],
                    'name' => $place['name'],
                    'error' => $e->getMessage()
                ]);
                continue;
            }
        }

        return [
            'contacts' => $contacts,
            'stats' => [
                'created' => $created,
                'updated' => $updated,
                'total_places' => count($places)
            ]
        ];
    }

    /**
     * Try to extract email from website
     */
    protected function extractEmailFromWebsite(?string $website): ?string
    {
        if (!$website) {
            return null;
        }

        try {
            // Try to scrape email from website
            $scraperService = new WebScraperService();
            $scraped = $scraperService->scrapeUrl($website);
            return $scraped['email'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Clean and format phone number
     */
    protected function cleanPhoneNumber(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        // Remove spaces, dashes, parentheses
        $cleaned = preg_replace('/[^\d+]/', '', $phone);
        
        // Ensure it starts with + for international format
        if (!str_starts_with($cleaned, '+')) {
            // Assume Romanian number if no country code
            if (strlen($cleaned) === 10 && str_starts_with($cleaned, '0')) {
                $cleaned = '+40' . substr($cleaned, 1);
            } elseif (strlen($cleaned) === 9) {
                $cleaned = '+40' . $cleaned;
            }
        }

        return $cleaned;
    }

    /**
     * Get cities in Romania for location searches
     */
    public static function getRomanianCities(): array
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
            'Ploiești' => ['lat' => 44.9436, 'lng' => 26.0166],
            'Oradea' => ['lat' => 47.0465, 'lng' => 21.9189],
            'Braila' => ['lat' => 45.2692, 'lng' => 27.9574],
            'Arad' => ['lat' => 46.1866, 'lng' => 21.3123],
            'Pitești' => ['lat' => 44.8565, 'lng' => 24.8692],
            'Sibiu' => ['lat' => 45.7983, 'lng' => 24.1256],
            'Bacău' => ['lat' => 46.5670, 'lng' => 26.9146],
            'Târgu Mureș' => ['lat' => 46.5425, 'lng' => 24.5579],
            'Baia Mare' => ['lat' => 47.6587, 'lng' => 23.5681]
        ];
    }

    /**
     * Get business types for searches
     */
    public static function getBusinessTypes(): array
    {
        return [
            'restaurant' => 'Restaurants',
            'store' => 'Retail Stores',
            'beauty_salon' => 'Beauty Salons',
            'gym' => 'Gyms & Fitness',
            'dentist' => 'Dentists',
            'lawyer' => 'Lawyers',
            'accounting' => 'Accounting',
            'real_estate_agency' => 'Real Estate',
            'car_dealer' => 'Car Dealers',
            'insurance_agency' => 'Insurance',
            'bank' => 'Banks',
            'pharmacy' => 'Pharmacies',
            'hospital' => 'Hospitals',
            'school' => 'Schools',
            'lodging' => 'Hotels & Lodging',
            'gas_station' => 'Gas Stations',
            'establishment' => 'All Businesses'
        ];
    }

    /**
     * Alias for searchPlaces - search nearby places with place details
     */
    public function searchNearby(float $lat, float $lng, int $radius = 1000, string $type = 'establishment', ?string $keyword = null): array
    {
        $places = $this->searchPlaces($lat, $lng, $radius, $type, $keyword);
        
        // Enrich with details for each place
        $enrichedPlaces = [];
        foreach ($places as $place) {
            try {
                // Get more details including website and phone
                $details = $this->getPlaceDetails($place['place_id']);
                $enrichedPlaces[] = array_merge($place, $details);
                
                // Small delay to avoid rate limiting
                usleep(100000); // 100ms
            } catch (\Exception $e) {
                // Use basic info if details fail
                $enrichedPlaces[] = $place;
            }
        }
        
        return $enrichedPlaces;
    }
}
