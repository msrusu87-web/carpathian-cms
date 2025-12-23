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
        
        // Priority 3: Cookie (persistent across requests)
        $cookieLocale = $request->cookie('locale');
        
        // Priority 4: Session (current session)
        $sessionLocale = Session::get('locale');
        
        // Priority 5: Config default
        $defaultLocale = config('app.locale', 'ro');
        
        $debugEnabled = (bool) env('LOCALE_DEBUG', false) || $request->boolean('__locale_debug');

        if ($debugEnabled) {
            Log::info('SetLocale BEFORE', [
                'rid' => $request->attributes->get('rid'),
                'url' => $request->fullUrl(),
                'query_locale' => $queryLocale,
                'segment_locale' => $segmentLocale,
                'cookie_locale' => $cookieLocale,
                'session_locale' => $sessionLocale,
                'default' => $defaultLocale,
            ]);
        }
        
        // Determine locale based on priority
        $locale = null;
        
        if ($queryLocale && in_array($queryLocale, $availableLocales)) {
            $locale = $queryLocale;
        } elseif ($segmentLocale && in_array($segmentLocale, $availableLocales)) {
            $locale = $segmentLocale;
        } elseif ($cookieLocale && in_array($cookieLocale, $availableLocales)) {
            $locale = $cookieLocale;
        } elseif ($sessionLocale && in_array($sessionLocale, $availableLocales)) {
            $locale = $sessionLocale;
        } else {
            $locale = $defaultLocale;
        }
        
        // Set the locale
        App::setLocale($locale);
        
        // Persist in session
        Session::put('locale', $locale);
        
        // Persist in cookie (for next request) - 1 year expiration
        cookie()->queue('locale', $locale, 60 * 24 * 365);
        
        if ($debugEnabled) {
            Log::info('SetLocale AFTER', [
                'rid' => $request->attributes->get('rid'),
                'selected_locale' => $locale,
                'app_locale' => App::getLocale(),
                'test_translation' => __('messages.features_title'),
            ]);
        }
        
        return $next($request);
    }
}
