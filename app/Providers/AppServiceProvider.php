<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

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
        // Set locale from session, cookie, or default to Romanian
        $locale = session('locale') 
            ?? request()->cookie('locale') 
            ?? config('app.locale', 'ro');
            
        // Validate locale is supported
        if (in_array($locale, ['en', 'ro', 'es', 'it', 'de', 'fr'])) {
            App::setLocale($locale);
            if (!session()->has('locale')) {
                session(['locale' => $locale]);
            }
        } else {
            App::setLocale('ro');
            session(['locale' => 'ro']);
        }
    }
}
