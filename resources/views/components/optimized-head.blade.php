<!-- Optimized Head Component for Maximum Performance -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- Preconnect to critical origins -->
<link rel="preconnect" href="https://cdn.tailwindcss.com" crossorigin>
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
<link rel="preconnect" href="https://unpkg.com" crossorigin>

<!-- DNS Prefetch for additional resources -->
<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//fonts.gstatic.com">

<!-- Critical CSS - Inline for faster FCP -->
<style>
    /* Critical CSS for above-the-fold content */
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{-webkit-text-size-adjust:100%;-moz-tab-size:4;tab-size:4;font-family:ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif}
    body{margin:0;line-height:inherit}
    img,svg,video,canvas,audio,iframe,embed,object{display:block;vertical-align:middle}
    img,video{max-width:100%;height:auto}
    
    /* Lazy loading placeholder */
    img[loading="lazy"]{opacity:0;transition:opacity .3s}
    img[loading="lazy"].loaded{opacity:1}
    
    /* Prevent layout shift */
    img,video,iframe{width:100%;height:auto}
    
    /* Loading skeleton */
    .skeleton{background:linear-gradient(90deg,#f0f0f0 25%,#e0e0e0 50%,#f0f0f0 75%);background-size:200% 100%;animation:skeleton-loading 1.5s infinite}
    @keyframes skeleton-loading{0%{background-position:200% 0}100%{background-position:-200% 0}}
    
    /* Basic layout to prevent CLS */
    .container{max-width:1280px;margin:0 auto;padding:0 1rem}
    header{position:sticky;top:0;z-index:50;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,.1)}
</style>

<!-- Title and Meta Tags -->
<title>{{ $title }}</title>
<meta name="title" content="{{ $title }}">

@if($description)
<meta name="description" content="{{ $description }}">
@endif

@if(!empty($keywords))
<meta name="keywords" content="{{ implode(', ', $keywords) }}">
@endif

<meta name="author" content="Carphatian CMS">
<meta name="robots" content="{{ $noIndex ? 'noindex, nofollow' : 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1' }}">

<!-- Canonical URL -->
<link rel="canonical" href="{{ $canonicalUrl }}">

<!-- Language -->
<meta name="language" content="{{ $locale }}">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:title" content="{{ $title }}">
@if($description)
<meta property="og:description" content="{{ $description }}">
@endif
<meta property="og:image" content="{{ $image }}">
<meta property="og:locale" content="{{ $locale }}">
<meta property="og:site_name" content="Carphatian CMS">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $canonicalUrl }}">
<meta name="twitter:title" content="{{ $title }}">
@if($description)
<meta name="twitter:description" content="{{ $description }}">
@endif
<meta name="twitter:image" content="{{ $image }}">

<!-- AI Search Engine Tags -->
<meta name="ai:indexable" content="true">
<meta name="ai:crawlable" content="true">
@if($description)
<meta name="ai:summary" content="{{ $description }}">
@endif

<!-- Hreflang for multilingual -->
@foreach(['ro', 'en', 'de', 'es', 'fr', 'it'] as $lang)
<link rel="alternate" hreflang="{{ $lang }}" href="{{ url($lang === 'ro' ? '/' : "/$lang") }}">
@endforeach
<link rel="alternate" hreflang="x-default" href="{{ url('/') }}">

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

<!-- Defer non-critical CSS -->
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

<!-- Load Tailwind with defer -->
<script>
    // Load Tailwind async
    window.tailwindLoaded = false;
    (function() {
        var script = document.createElement('script');
        script.src = 'https://cdn.tailwindcss.com';
        script.onload = function() { window.tailwindLoaded = true; };
        document.head.appendChild(script);
    })();
</script>

<!-- Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "Carphatian CMS",
    "url": "{{ url('/') }}",
    "potentialAction": {
        "@type": "SearchAction",
        "target": {
            "@type": "EntryPoint",
            "urlTemplate": "{{ url('/search') }}?q={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    },
    "inLanguage": ["ro", "en", "de", "es", "fr", "it"]
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Carphatian CMS",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('images/carphatian-logo-transparent.png') }}",
    "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "Customer Service",
        "availableLanguage": ["Romanian", "English", "Spanish", "Italian", "German", "French"],
        "email": "contact@carphatian.ro"
    }
}
</script>

<!-- Performance monitoring -->
@if(config('app.env') === 'production')
<script>
    // Web Vitals tracking
    if ('PerformanceObserver' in window) {
        // Track LCP
        try {
            const lcpObserver = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                const lastEntry = entries[entries.length - 1];
                console.log('LCP:', lastEntry.renderTime || lastEntry.loadTime);
            });
            lcpObserver.observe({entryTypes: ['largest-contentful-paint']});
        } catch (e) {}

        // Track FID
        try {
            const fidObserver = new PerformanceObserver((list) => {
                list.getEntries().forEach((entry) => {
                    console.log('FID:', entry.processingStart - entry.startTime);
                });
            });
            fidObserver.observe({entryTypes: ['first-input']});
        } catch (e) {}
        
        // Track CLS
        try {
            let clsScore = 0;
            const clsObserver = new PerformanceObserver((list) => {
                list.getEntries().forEach((entry) => {
                    if (!entry.hadRecentInput) {
                        clsScore += entry.value;
                        console.log('CLS:', clsScore);
                    }
                });
            });
            clsObserver.observe({entryTypes: ['layout-shift']});
        } catch (e) {}
    }
</script>
@endif
