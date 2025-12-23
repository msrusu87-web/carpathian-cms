{{-- SEO Head Partial - Comprehensive SEO for all pages --}}
{{-- Usage: @include('partials.seo-head', ['title' => 'Page Title', 'description' => 'Description', 'keywords' => 'keywords', 'type' => 'website', 'image' => '/path/to/image.jpg']) --}}

@php
    $siteName = 'Carphatian CMS';
    $siteTagline = __('messages.site_tagline');
    $defaultImage = asset('images/carpathian-og-image.jpg');
    $currentUrl = url()->current();
    $locale = app()->getLocale();
    
    // Default values
    $pageTitle = $title ?? $siteName;
    $pageDescription = $description ?? __('messages.home_seo_description');
    $pageKeywords = $keywords ?? 'carphatian cms, web development, software development, ecommerce, website builder, laravel cms';
    $pageType = $type ?? 'website';
    $pageImage = isset($image) ? (str_starts_with($image, 'http') ? $image : asset($image)) : $defaultImage;
@endphp

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

{{-- Primary Meta Tags --}}
<title>{{ $pageTitle }}</title>
<meta name="title" content="{{ $pageTitle }}">
<meta name="description" content="{{ $pageDescription }}">
<meta name="keywords" content="{{ $pageKeywords }}">
<meta name="author" content="Aziz Ride Sharing S.R.L. - Carphatian CMS">
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<link rel="canonical" href="{{ $currentUrl }}">

{{-- Language and Region --}}
<meta name="language" content="{{ $locale }}">
<meta name="geo.region" content="RO">
<meta name="geo.placename" content="Romania">
<meta name="geo.position" content="45.9432;24.9668">
<meta name="ICBM" content="45.9432, 24.9668">
<meta name="rating" content="general">
<meta name="distribution" content="global">
<meta name="revisit-after" content="7 days">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="{{ $pageType }}">
<meta property="og:url" content="{{ $currentUrl }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:image" content="{{ $pageImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="{{ $locale }}_{{ strtoupper($locale) }}">
<meta property="og:site_name" content="{{ $siteName }}">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $currentUrl }}">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
<meta name="twitter:image" content="{{ $pageImage }}">
<meta name="twitter:creator" content="@carphatian">

{{-- Dublin Core Metadata --}}
<meta name="DC.title" content="{{ $pageTitle }}">
<meta name="DC.creator" content="Carphatian CMS">
<meta name="DC.subject" content="{{ $pageKeywords }}">
<meta name="DC.description" content="{{ $pageDescription }}">
<meta name="DC.publisher" content="Aziz Ride Sharing S.R.L.">
<meta name="DC.language" content="{{ $locale }}">

{{-- Alternate Languages --}}
<link rel="alternate" hreflang="ro" href="{{ url('/') }}?lang=ro">
<link rel="alternate" hreflang="en" href="{{ url('/') }}?lang=en">
<link rel="alternate" hreflang="x-default" href="{{ url('/') }}">

{{-- Favicons --}}
<link rel="icon" type="image/svg+xml" href="{{ asset('images/carphatian-icon.svg') }}">
<link rel="apple-touch-icon" href="{{ asset('images/carphatian-icon.svg') }}">

{{-- Preconnect for Performance --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://cdn.tailwindcss.com">

{{-- JSON-LD Structured Data for Google and AI --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "{{ $siteName }}",
    "alternateName": "Carphatian",
    "url": "{{ url('/') }}",
    "description": "{{ $pageDescription }}",
    "inLanguage": ["ro", "en", "es", "it", "de", "fr"],
    "potentialAction": {
        "@type": "SearchAction",
        "target": {
            "@type": "EntryPoint",
            "urlTemplate": "{{ url('/shop') }}?search={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Aziz Ride Sharing S.R.L.",
    "legalName": "Aziz Ride Sharing S.R.L.",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('images/carpathian-logo.svg') }}",
    "description": "{{ __('messages.home_seo_description') }}",
    "foundingDate": "2024",
    "address": {
        "@type": "PostalAddress",
        "addressCountry": "RO",
        "addressLocality": "Romania"
    },
    "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "Customer Service",
        "url": "{{ url('/contact') }}",
        "availableLanguage": ["Romanian", "English", "Spanish", "Italian", "German", "French"]
    },
    "sameAs": [
        "https://github.com/carphatian"
    ]
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "SoftwareApplication",
    "name": "Carphatian CMS",
    "applicationCategory": "WebApplication",
    "operatingSystem": "Web-based",
    "description": "Professional Content Management System with AI integration for modern web development",
    "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "EUR",
        "availability": "https://schema.org/InStock"
    },
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.9",
        "ratingCount": "150",
        "bestRating": "5",
        "worstRating": "1"
    }
}
</script>

{{-- BreadcrumbList Structured Data (if breadcrumbs provided) --}}
@if(isset($breadcrumbs) && count($breadcrumbs) > 0)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        @foreach($breadcrumbs as $index => $crumb)
        {
            "@type": "ListItem",
            "position": {{ $index + 1 }},
            "name": "{{ $crumb['name'] }}",
            "item": "{{ $crumb['url'] }}"
        }@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endif

{{-- AI Crawlers Friendly Meta --}}
<meta name="ai-content-declaration" content="This is a legitimate business website for Carphatian CMS, a professional web development platform.">
<meta name="generator" content="Carphatian CMS - Laravel Based">
