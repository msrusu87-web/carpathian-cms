<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PerformanceOptimization
{
    /**
     * Routes that should never be cached.
     */
    protected array $excludedRoutes = [
        'login',
        'register',
        'logout',
        'password/*',
        'verify-email/*',
        'forgot-password',
        'reset-password/*',
        'two-factor*',
        'admin/*',
        'livewire/*',
        'checkout/*',
        'cart/*',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip caching for excluded routes
        if ($this->shouldExclude($request)) {
            $response = $next($request);
            $response->header('X-Cache', 'BYPASS');
            return $response;
        }

        // Get locale from cookie for cache key
        $locale = $_COOKIE['locale'] ?? config('app.locale', 'ro');
        
        // Enable response caching for GET requests only for guests
        if ($request->isMethod('GET') && !$request->user()) {
            $cacheKey = 'page_cache_' . $locale . '_' . md5($request->fullUrl());
            
            // Check if cached response exists
            if (Cache::has($cacheKey)) {
                $cachedResponse = Cache::get($cacheKey);
                return response($cachedResponse['content'])
                    ->header('Content-Type', $cachedResponse['content_type'])
                    ->header('X-Cache', 'HIT')
                    ->header('X-Cache-Locale', $locale);
            }
        }

        $response = $next($request);

        // Cache successful GET responses for non-auth pages
        if ($request->isMethod('GET') && 
            !$request->user() && 
            $response->getStatusCode() === 200 &&
            !$this->shouldExclude($request)) {
            
            $cacheKey = 'page_cache_' . $locale . '_' . md5($request->fullUrl());
            
            Cache::put($cacheKey, [
                'content' => $response->getContent(),
                'content_type' => $response->headers->get('Content-Type')
            ], config('performance.cache.ttl', 3600));
            
            $response->header('X-Cache', 'MISS');
            $response->header('X-Cache-Locale', $locale);
        }

        // Add performance headers
        $response->header('X-Response-Time', round((microtime(true) - LARAVEL_START) * 1000, 2) . 'ms');
        
        return $response;
    }

    /**
     * Check if the request should be excluded from caching.
     */
    protected function shouldExclude(Request $request): bool
    {
        $path = $request->path();
        
        foreach ($this->excludedRoutes as $pattern) {
            if (fnmatch($pattern, $path)) {
                return true;
            }
        }
        
        // Also exclude if request has _token (form submission)
        if ($request->has('_token')) {
            return true;
        }
        
        return false;
    }
}
