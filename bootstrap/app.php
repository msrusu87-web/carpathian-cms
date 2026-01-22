<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            require __DIR__.'/../routes/debug.php';
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Web middleware
        $middleware->web(append: [
            \App\Http\Middleware\RequestId::class,
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\RedirectMiddleware::class,
            \App\Http\Middleware\LoadActiveTemplate::class,
        ]);

        // Throttle API requests - 60 requests per minute
        $middleware->throttleApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
