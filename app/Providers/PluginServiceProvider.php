<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use App\Models\Plugin;

class PluginServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Services\PluginManager::class);
    }

    public function boot(): void
    {
        if (!app()->runningInConsole() || app()->runningUnitTests()) {
            $this->loadActivePlugins();
        }
    }

    protected function loadActivePlugins(): void
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('plugins')) {
                return;
            }

            $activePlugins = Plugin::where('is_active', true)->get();

            foreach ($activePlugins as $plugin) {
                $this->loadPlugin($plugin->slug);
            }
        } catch (\Exception $e) {
            \Log::error("Error loading plugins: " . $e->getMessage());
        }
    }

    protected function loadPlugin(string $slug): void
    {
        $pluginPath = base_path("plugins/{$slug}");
        
        if (!File::exists($pluginPath)) {
            return;
        }

        // Load routes
        $routesFile = "{$pluginPath}/routes/web.php";
        if (File::exists($routesFile)) {
            $this->loadRoutesFrom($routesFile);
        }

        // Load views
        $viewsPath = "{$pluginPath}/views";
        if (File::exists($viewsPath)) {
            $this->loadViewsFrom($viewsPath, $slug);
        }

        // Register Filament resources
        $this->registerPluginResources($slug, $pluginPath);
    }

    protected function registerPluginResources(string $slug, string $pluginPath): void
    {
        $resourcesPath = "{$pluginPath}/src/Filament/Resources";
        
        if (!File::exists($resourcesPath)) {
            return;
        }

        $resources = File::glob("{$resourcesPath}/*Resource.php");

        foreach ($resources as $resourceFile) {
            $className = "\\Plugins\\" . \Illuminate\Support\Str::studly($slug) . "\\Filament\\Resources\\" . basename($resourceFile, '.php');
            
            if (class_exists($className)) {
                try {
                    \Filament\Facades\Filament::registerResources([$className]);
                } catch (\Exception $e) {
                    \Log::error("Failed to register resource {$className}: " . $e->getMessage());
                }
            }
        }
    }
}
