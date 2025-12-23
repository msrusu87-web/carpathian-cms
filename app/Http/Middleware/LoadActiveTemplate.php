<?php

namespace App\Http\Middleware;

use App\Models\Template;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class LoadActiveTemplate
{
    public function handle(Request $request, Closure $next)
    {
        // Get active template from cache or database
        $template = Cache::remember('active_template', 3600, function () {
            return Template::where('is_active', true)->first();
        });

        // If no active template, get default
        if (!$template) {
            $template = Cache::remember('default_template', 3600, function () {
                return Template::where('is_default', true)->first();
            });
        }

        // Share template data with all views
        if ($template) {
            $colorScheme = is_string($template->color_scheme) 
                ? json_decode($template->color_scheme, true) 
                : $template->color_scheme;
            
            $typography = is_string($template->typography) 
                ? json_decode($template->typography, true) 
                : $template->typography;
            
            $config = is_string($template->config) 
                ? json_decode($template->config, true) 
                : $template->config;

            View::share('activeTemplate', $template);
            View::share('templateColors', $colorScheme ?? []);
            View::share('templateTypography', $typography ?? []);
            View::share('templateConfig', $config ?? []);
        }

        return $next($request);
    }
}
