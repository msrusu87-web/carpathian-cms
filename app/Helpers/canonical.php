<?php

if (!function_exists('canonical_url')) {
    /**
     * Generate canonical URL for the current page
     */
    function canonical_url(?string $url = null): string
    {
        if ($url) {
            return url($url);
        }
        
        return url()->current();
    }
}

if (!function_exists('set_canonical')) {
    /**
     * Set canonical URL in view data
     */
    function set_canonical(?string $url = null): void
    {
        view()->share('canonicalUrl', canonical_url($url));
    }
}
