<?php

namespace Plugins\PaymentGateway;

use Illuminate\Support\Facades\File;
use Filament\Facades\Filament;

class Plugin
{
    public static function activate(): void
    {
        // Register resources with Filament
        self::registerResources();
        
        // Run migrations if needed
        self::runMigrations();
    }

    public static function deactivate(): void
    {
        // Unregister resources from Filament
        // The navigation will automatically disappear
    }

    public static function uninstall(): void
    {
        // Additional cleanup before deletion
        // Optionally remove database tables
    }

    protected static function registerResources(): void
    {
        $resources = [
            \Plugins\PaymentGateway\Filament\Resources\PaymentGatewayResource::class,
        ];

        foreach ($resources as $resource) {
            Filament::registerResources([$resource]);
        }
    }

    protected static function runMigrations(): void
    {
        // Check if migrations need to be run
        $migrationsPath = __DIR__ . '/database/migrations';
        if (File::exists($migrationsPath)) {
            \Artisan::call('migrate', [
                '--path' => 'plugins/payment-gateway/database/migrations',
                '--force' => true
            ]);
        }
    }

    /**
     * Get active payment gateways
     */
    public static function getActiveGateways(): array
    {
        return \Plugins\PaymentGateway\Models\PaymentGateway::where('is_active', true)
            ->get()
            ->toArray();
    }

    /**
     * Process quick payment link
     */
    public static function generateQuickPaymentLink(float $amount, string $description, array $metadata = []): ?string
    {
        $gateway = \Plugins\PaymentGateway\Models\PaymentGateway::where('is_active', true)
            ->where('supports_quick_links', true)
            ->first();

        if (!$gateway) {
            return null;
        }

        $service = new \Plugins\PaymentGateway\Services\PaymentService($gateway);
        return $service->createQuickPaymentLink($amount, $description, $metadata);
    }
}
