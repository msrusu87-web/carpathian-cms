<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\Product;
use App\Policies\PagePolicy;
use App\Policies\ProductPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Rate limiting
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->getAuthIdentifier() ?: $request->ip());
        });
        
        // Stricter rate limit for auth endpoints
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        // Set locale from session or default to Romanian
        $locale = session('locale', config('app.locale', 'ro'));
        App::setLocale($locale);
        
        // Register policies
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Page::class, PagePolicy::class);
        
        // Gate for backup management - only super admins
        Gate::define('manage-backups', function ($user) {
            return $user->hasRole('super_admin') || $user->hasRole('admin');
        });
    }
}
