<?php

namespace Plugins\Marketing;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class Plugin
{
    public static function activate(): void
    {
        Log::info('Marketing plugin activated');
        self::registerResources();
    }

    public static function deactivate(): void
    {
        Log::info('Marketing plugin deactivated');
    }

    public static function uninstall(): void
    {
        Log::info('Marketing plugin uninstalled');
    }

    protected static function registerResources(): void
    {
        // Resources are auto-discovered via AdminPanelProvider
        // Routes are loaded via service provider
    }
    
    public static function registerRoutes(): void
    {
        $routesPath = __DIR__ . '/routes/web.php';
        if (File::exists($routesPath)) {
            Route::middleware('web')->group($routesPath);
        }
    }
    
    public static function registerViews(): void
    {
        $viewsPath = __DIR__ . '/resources/views';
        if (File::exists($viewsPath)) {
            app('view')->addNamespace('marketing', $viewsPath);
        }
    }
    
    public static function getConfig(): array
    {
        $configPath = __DIR__ . '/plugin.json';
        if (File::exists($configPath)) {
            return json_decode(File::get($configPath), true)['config'] ?? [];
        }
        return [];
    }
    
    public static function getRateLimits(): array
    {
        return self::getConfig()['rate_limits'] ?? [
            'scrape_per_minute' => 10,
            'emails_per_hour' => 50,
            'emails_per_day' => 200
        ];
    }
    
    public static function getAntiSpamConfig(): array
    {
        return self::getConfig()['anti_spam'] ?? [
            'require_unsubscribe_link' => true,
            'min_delay_between_emails_seconds' => 30,
            'max_emails_per_contact_per_day' => 1,
            'blacklist_domains' => []
        ];
    }
}
