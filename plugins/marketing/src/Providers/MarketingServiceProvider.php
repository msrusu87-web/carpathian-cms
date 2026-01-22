<?php

namespace Plugins\Marketing\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Models\Plugin;
use Plugins\Marketing\Console\Commands\HarvestContactsCommand;
use Plugins\Marketing\Console\Commands\SyncBrevoStatsCommand;

class MarketingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Always register commands (they can run even if plugin is "inactive")
        if ($this->app->runningInConsole()) {
            $this->commands([
                HarvestContactsCommand::class,
                SyncBrevoStatsCommand::class,
            ]);
        }

        // Check if plugin is active for web features
        if (!$this->isPluginActive()) {
            return;
        }

        $this->loadRoutes();
        $this->loadViews();
    }

    protected function isPluginActive(): bool
    {
        try {
            return Plugin::where('slug', 'marketing')
                ->where('is_active', true)
                ->exists();
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function loadRoutes(): void
    {
        $routesPath = __DIR__ . '/../../routes/web.php';
        
        if (file_exists($routesPath)) {
            Route::middleware('web')->group($routesPath);
        }
    }

    protected function loadViews(): void
    {
        $viewsPath = __DIR__ . '/../../resources/views';
        
        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, 'marketing');
        }
    }
}
