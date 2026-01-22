<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Performance Optimization Configuration
    |--------------------------------------------------------------------------
    */

    // Cache Configuration
    'cache' => [
        'enabled' => env('CACHE_ENABLED', true),
        'ttl' => env('CACHE_TTL', 3600), // 1 hour
        'pages_ttl' => env('CACHE_PAGES_TTL', 7200), // 2 hours
        'translations_ttl' => env('CACHE_TRANSLATIONS_TTL', 86400), // 24 hours
    ],

    // Image Optimization
    'images' => [
        'lazy_loading' => true,
        'webp_conversion' => true,
        'quality' => 85,
        'max_width' => 2000,
        'max_height' => 2000,
    ],

    // Database Query Optimization
    'database' => [
        'eager_loading' => true,
        'query_cache' => true,
        'chunk_size' => 100,
    ],

    // Asset Minification
    'assets' => [
        'minify_css' => env('MINIFY_CSS', true),
        'minify_js' => env('MINIFY_JS', true),
        'combine_files' => env('COMBINE_FILES', true),
    ],
];
