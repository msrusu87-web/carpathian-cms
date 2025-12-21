<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Load custom plugins autoloader
require_once __DIR__ . '/plugins-autoload.php';

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Exclude locale cookie from encryption so middleware can read it
        $middleware->encryptCookies(except: ['locale']);
        
        // Web middleware
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\RedirectMiddleware::class,
            \App\Http\Middleware\LoadActiveTemplate::class,
            \App\Http\Middleware\SecurityHeaders::class,
            // DISABLED: \App\Http\Middleware\PerformanceOptimization::class,
            // This was caching pages with CSRF tokens causing 419 errors
        ]);

        // Throttle API requests - 60 requests per minute
        $middleware->throttleApi('60:1');
        
        // Rate limiting for specific routes
        $middleware->alias([
            'throttle.strict' => \App\Http\Middleware\RateLimitRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
