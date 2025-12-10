<?php

if (!function_exists('breadcrumbs')) {
    /**
     * Generate breadcrumbs for the current page
     */
    function breadcrumbs(): array
    {
        $segments = request()->segments();
        $breadcrumbs = [
            ['title' => 'Home', 'url' => url('/')]
        ];
        
        $url = '';
        foreach ($segments as $segment) {
            $url .= '/' . $segment;
            $title = ucwords(str_replace(['-', '_'], ' ', $segment));
            $breadcrumbs[] = ['title' => $title, 'url' => url($url)];
        }
        
        return $breadcrumbs;
    }
}

if (!function_exists('add_breadcrumb')) {
    /**
     * Add a custom breadcrumb
     */
    function add_breadcrumb(string $title, ?string $url = null): void
    {
        $breadcrumbs = session()->get('breadcrumbs', [['title' => 'Home', 'url' => url('/')]]);
        $breadcrumbs[] = ['title' => $title, 'url' => $url];
        session()->put('breadcrumbs', $breadcrumbs);
    }
}

if (!function_exists('get_breadcrumbs')) {
    /**
     * Get stored breadcrumbs or generate from URL
     */
    function get_breadcrumbs(): array
    {
        if (session()->has('breadcrumbs')) {
            $breadcrumbs = session()->get('breadcrumbs');
            session()->forget('breadcrumbs');
            return $breadcrumbs;
        }
        
        return breadcrumbs();
    }
}
