<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    /**
     * Available currencies
     */
    private array $currencies = [
        'RON' => ['symbol' => 'RON', 'name' => 'Romanian Leu'],
        'EUR' => ['symbol' => '€', 'name' => 'Euro'],
        'USD' => ['symbol' => '$', 'name' => 'US Dollar'],
        'GBP' => ['symbol' => '£', 'name' => 'British Pound'],
    ];

    /**
     * Get exchange rates from exchangerate-api.com (free, reliable, no API key needed for basic usage)
     */
    public function getExchangeRates(string $baseCurrency = 'RON'): array
    {
        $cacheKey = "exchange_rates_{$baseCurrency}";
        
        return Cache::remember($cacheKey, now()->addHours(6), function () use ($baseCurrency) {
            try {
                $response = Http::timeout(10)->get("https://api.exchangerate-api.com/v4/latest/{$baseCurrency}");
                
                if ($response->successful()) {
                    $data = $response->json();
                    return $data['rates'] ?? $this->getFallbackRates($baseCurrency);
                }
            } catch (\Exception $e) {
                Log::error('Currency exchange API failed: ' . $e->getMessage());
            }
            
            return $this->getFallbackRates($baseCurrency);
        });
    }

    /**
     * Fallback exchange rates if API fails
     */
    private function getFallbackRates(string $baseCurrency): array
    {
        // Approximate rates (updated periodically)
        $baseRates = [
            'RON' => ['RON' => 1, 'EUR' => 0.20, 'USD' => 0.22, 'GBP' => 0.17],
            'EUR' => ['RON' => 4.97, 'EUR' => 1, 'USD' => 1.09, 'GBP' => 0.86],
            'USD' => ['RON' => 4.56, 'EUR' => 0.92, 'USD' => 1, 'GBP' => 0.79],
            'GBP' => ['RON' => 5.77, 'EUR' => 1.16, 'USD' => 1.27, 'GBP' => 1],
        ];

        return $baseRates[$baseCurrency] ?? $baseRates['RON'];
    }

    /**
     * Convert amount from one currency to another
     */
    public function convert(float $amount, string $from, string $to): float
    {
        if ($from === $to) {
            return $amount;
        }

        $rates = $this->getExchangeRates($from);
        $rate = $rates[$to] ?? 1;

        return round($amount * $rate, 2);
    }

    /**
     * Format price with currency symbol
     */
    public function formatPrice(float $amount, string $currency): string
    {
        $symbol = $this->currencies[$currency]['symbol'] ?? $currency;
        
        return number_format($amount, 2, '.', ',') . ' ' . $symbol;
    }

    /**
     * Get available currencies
     */
    public function getCurrencies(): array
    {
        return $this->currencies;
    }

    /**
     * Get current selected currency from session
     */
    public function getCurrentCurrency(): string
    {
        return session('currency', 'RON');
    }

    /**
     * Set currency in session
     */
    public function setCurrency(string $currency): void
    {
        if (isset($this->currencies[$currency])) {
            session(['currency' => $currency]);
        }
    }
}
