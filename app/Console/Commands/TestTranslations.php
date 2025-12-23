<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestTranslations extends Command
{
    protected $signature = "translations:test {--locale=ro : The locale to test}";
    protected $description = "Test all pages and sections for proper translations";

    public function handle()
    {
        $locale = $this->option("locale");
        $baseUrl = config("app.url");
        
        $this->info("🔍 Testing translations for locale: {$locale}");
        $this->info("Base URL: {$baseUrl}");
        $this->newLine();

        // Set the session locale for testing
        $cookieJar = new \GuzzleHttp\Cookie\CookieJar();
        
        $pages = [
            "/" => "Homepage",
            "/blog" => "Blog Page",
            "/shop" => "Shop Page",
            "/contact" => "Contact Page",
        ];

        $results = [];
        
        foreach ($pages as $path => $name) {
            $this->info("Testing: {$name} ({$path})");
            
            try {
                // First set the locale
                $response = Http::withOptions([
                    "cookies" => $cookieJar,
                ])->get("{$baseUrl}/{$locale}");
                
                // Then fetch the page
                $response = Http::withOptions([
                    "cookies" => $cookieJar,
                    "verify" => false,
                ])->get("{$baseUrl}{$path}");
                
                if ($response->successful()) {
                    $html = $response->body();
                    
                    // Check for translation markers
                    $checks = [
                        "Navigation" => $this->checkNavigation($html, $locale),
                        "Hero Section" => $this->checkHero($html, $locale),
                        "Features" => $this->checkFeatures($html, $locale),
                        "Products" => $this->checkProducts($html, $locale),
                        "Blog" => $this->checkBlog($html, $locale),
                        "Footer" => $this->checkFooter($html, $locale),
                    ];
                    
                    $results[$name] = $checks;
                    
                    foreach ($checks as $section => $status) {
                        $icon = $status ? "✅" : "❌";
                        $this->line("  {$icon} {$section}");
                    }
                } else {
                    $this->error("  Failed to load page (HTTP {$response->status()})");
                    $results[$name] = false;
                }
            } catch (\Exception $e) {
                $this->error("  Error: {$e->getMessage()}");
                $results[$name] = false;
            }
            
            $this->newLine();
        }

        // Summary
        $this->info("📊 Test Summary:");
        $this->table(
            ["Page", "Status"],
            collect($results)->map(function ($checks, $page) {
                if (is_array($checks)) {
                    $passed = collect($checks)->filter()->count();
                    $total = count($checks);
                    $status = "{$passed}/{$total} sections translated";
                    return [$page, $status];
                }
                return [$page, "Failed"];
            })->toArray()
        );

        return 0;
    }

    protected function checkNavigation($html, $locale)
    {
        if ($locale === "ro") {
            return str_contains($html, "Acasă") || 
                   str_contains($html, "Magazin") || 
                   str_contains($html, "Administrare");
        }
        return str_contains($html, "Home") || str_contains($html, "Shop");
    }

    protected function checkHero($html, $locale)
    {
        if ($locale === "ro") {
            return str_contains($html, "Bine ați venit") || 
                   str_contains($html, "CMS Profesional");
        }
        return str_contains($html, "Welcome") || str_contains($html, "Professional CMS");
    }

    protected function checkFeatures($html, $locale)
    {
        if ($locale === "ro") {
            return str_contains($html, "Funcționalități") || 
                   str_contains($html, "Cu Puterea AI");
        }
        return str_contains($html, "Features") || str_contains($html, "AI-Powered");
    }

    protected function checkProducts($html, $locale)
    {
        if ($locale === "ro") {
            return str_contains($html, "Produse Recomandate") || 
                   str_contains($html, "Vezi Detalii");
        }
        return str_contains($html, "Featured Products") || str_contains($html, "View Details");
    }

    protected function checkBlog($html, $locale)
    {
        if ($locale === "ro") {
            return str_contains($html, "Cele Mai Recente") || 
                   str_contains($html, "Citește Mai Mult");
        }
        return str_contains($html, "Latest Blog") || str_contains($html, "Read More");
    }

    protected function checkFooter($html, $locale)
    {
        if ($locale === "ro") {
            return str_contains($html, "Link-uri Rapide") || 
                   str_contains($html, "Toate drepturile rezervate");
        }
        return str_contains($html, "Quick Links") || str_contains($html, "All rights reserved");
    }
}
