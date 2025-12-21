<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $availableLocales = ['ro', 'en', 'de', 'fr', 'it', 'es'];
        
        // Priority 1: Query parameter (?locale=en) - HIGHEST PRIORITY
        $queryLocale = $request->query('locale');
        
        // Priority 2: URL segment (/en/page)
        $segmentLocale = $request->segment(1);
        
        // Priority 3: Session (current session)
        // NOTE: Session must win over cookie to allow a "switch language" action
        // (which typically sets the session then redirects) to take effect immediately.
        $sessionLocale = Session::get('locale');

        // Priority 4: Cookie (persistent across sessions)
        $cookieLocale = $request->cookie('locale');
        
        // Priority 5: Config default
        $defaultLocale = config('app.locale', 'ro');
        
        // Determine locale based on priority
        $locale = null;
        
        if ($queryLocale && in_array($queryLocale, $availableLocales)) {
            $locale = $queryLocale;
        } elseif ($segmentLocale && in_array($segmentLocale, $availableLocales)) {
            $locale = $segmentLocale;
        } elseif ($sessionLocale && in_array($sessionLocale, $availableLocales)) {
            $locale = $sessionLocale;
        } elseif ($cookieLocale && in_array($cookieLocale, $availableLocales)) {
            $locale = $cookieLocale;
        } else {
            $locale = $defaultLocale;
        }
        
        // Set the locale
        App::setLocale($locale);
        
        // Only persist in session if locale changed
        if (Session::get('locale') !== $locale) {
            Session::put('locale', $locale);
            // Persist in cookie (for next request) - 1 year expiration
            cookie()->queue('locale', $locale, 60 * 24 * 365);
        }
        
        return $next($request);
    }
}
