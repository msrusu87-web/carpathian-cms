<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Don't interfere with login, logout, or admin routes
        if ($request->is('login') || $request->is('logout') || $request->is('admin*') || $request->is('auth-test')) {
            return $next($request);
        }

        // Let everything else pass through
        return $next($request);
    }
}
