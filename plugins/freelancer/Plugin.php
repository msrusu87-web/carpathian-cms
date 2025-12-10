<?php

namespace Plugins\Freelancer;

use Illuminate\Support\Facades\File;

class Plugin
{
    public static function activate(): void
    {
        // Register resources with Filament
        self::registerResources();
    }

    public static function deactivate(): void
    {
        // Cleanup if needed
    }

    public static function uninstall(): void
    {
        // Additional cleanup before deletion
    }

    protected static function registerResources(): void
    {
        $resources = [
            \Plugins\Freelancer\Filament\Resources\FreelancerOrderResource::class,
            \Plugins\Freelancer\Filament\Resources\FreelancerProfileResource::class,
            \Plugins\Freelancer\Filament\Resources\FreelancerGigResource::class,
        ];

        foreach ($resources as $resource) {
            \Filament\Facades\Filament::registerResources([$resource]);
        }
    }
}
