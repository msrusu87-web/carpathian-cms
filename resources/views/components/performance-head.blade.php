<!-- Page Speed Optimization Component -->
<!-- Include this in your <head> section for better performance -->

<!-- DNS Prefetch for external resources -->
<link rel="dns-prefetch" href="//cdn.tailwindcss.com">
<link rel="dns-prefetch" href="//cdn.jsdelivr.net">
<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
<link rel="dns-prefetch" href="//unpkg.com">
<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//fonts.gstatic.com">

<!-- Preconnect for critical resources -->
<link rel="preconnect" href="https://cdn.tailwindcss.com" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Preload critical assets -->
@if(isset($criticalCss))
<link rel="preload" href="{{ asset($criticalCss) }}" as="style">
@endif

@if(isset($criticalJs))
<link rel="preload" href="{{ asset($criticalJs) }}" as="script">
@endif

<!-- Preload hero/featured image if exists -->
@if(isset($heroImage))
<link rel="preload" href="{{ $heroImage }}" as="image" type="image/webp">
@endif

<!-- Resource Hints for likely navigation -->
@if(isset($prefetchPages))
    @foreach($prefetchPages as $page)
    <link rel="prefetch" href="{{ $page }}">
    @endforeach
@endif

<!-- Performance optimizations -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- Optimize rendering -->
<style>
    /* Critical CSS - Inline to prevent render blocking */
    html { font-family: system-ui, -apple-system, sans-serif; }
    body { margin: 0; padding: 0; }
    
    /* Image lazy loading blur-up effect */
    img[loading="lazy"] {
        transition: filter 0.3s;
    }
    img[loading="lazy"]:not([src]) {
        filter: blur(10px);
    }
    
    /* Skeleton loaders for better perceived performance */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    /* Reduce layout shift */
    img, video, iframe {
        max-width: 100%;
        height: auto;
    }
</style>

<!-- Defer non-critical CSS -->
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

<!-- Service Worker for PWA capabilities (optional) -->
@if(config('app.pwa_enabled', false))
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js');
        });
    }
</script>
@endif

<!-- Performance monitoring (optional) -->
<script>
    // Core Web Vitals monitoring
    if (window.performance && window.PerformanceObserver) {
        // Largest Contentful Paint
        new PerformanceObserver((list) => {
            const entries = list.getEntries();
            const lastEntry = entries[entries.length - 1];
            console.log('LCP:', lastEntry.renderTime || lastEntry.loadTime);
        }).observe({entryTypes: ['largest-contentful-paint']});
        
        // First Input Delay
        new PerformanceObserver((list) => {
            const entries = list.getEntries();
            entries.forEach(entry => {
                console.log('FID:', entry.processingStart - entry.startTime);
            });
        }).observe({entryTypes: ['first-input']});
        
        // Cumulative Layout Shift
        let clsScore = 0;
        new PerformanceObserver((list) => {
            list.getEntries().forEach(entry => {
                if (!entry.hadRecentInput) {
                    clsScore += entry.value;
                    console.log('CLS:', clsScore);
                }
            });
        }).observe({entryTypes: ['layout-shift']});
    }
</script>
