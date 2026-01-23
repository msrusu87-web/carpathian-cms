<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>{{ __('messages.home_seo_title') }}</title>
    <meta name="title" content="{{ __('messages.home_seo_title') }}">
    <meta name="description" content="{{ __('messages.home_seo_description') }}">
    <meta name="keywords" content="carphatian cms, web development on demand, custom software development, website builder, cms platform, ecommerce development, laravel cms, professional web design, scalable applications, enterprise software, full stack development, php development, modern web applications, business automation, digital transformation">
    <meta name="author" content="Carphatian CMS">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="{{ url('/') }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ __('messages.home_seo_title') }}">
    <meta property="og:description" content="{{ __('messages.home_seo_description') }}">
    <meta property="og:image" content="{{ asset('images/carpathian-og-image.jpg') }}">
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    <meta property="og:site_name" content="Carphatian CMS">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url('/') }}">
    <meta name="twitter:title" content="{{ __('messages.home_seo_title') }}">
    <meta name="twitter:description" content="{{ __('messages.home_seo_description') }}">
    <meta name="twitter:image" content="{{ asset('images/carpathian-og-image.jpg') }}">
    
    <!-- Additional SEO -->
    <meta name="geo.region" content="RO">
    <meta name="geo.placename" content="Romania">
    <meta name="language" content="{{ app()->getLocale() }}">
    <meta name="rating" content="general">
    
    <!-- Structured Data (JSON-LD) for Google -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "Carphatian CMS",
        "url": "{{ url('/') }}",
        "description": "{{ __('messages.home_seo_description') }}",
        "inLanguage": ["ro", "en", "es", "it", "de", "fr"]
    }
    </script>
    
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Carphatian CMS",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/carpathian-logo.svg') }}",
        "description": "{{ __('messages.home_seo_description') }}",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "RO"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "Customer Service",
            "availableLanguage": ["Romanian", "English", "Spanish", "Italian", "German", "French"]
        }
    }
    </script>
    
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "Carphatian CMS",
        "applicationCategory": "WebApplication",
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "EUR"
        },
        "operatingSystem": "Web-based"
    }
    </script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    @if(isset($templateColors))
    <style>
        :root {
            --color-primary: {{ $templateColors['primary'] ?? '#9333ea' }};
            --color-secondary: {{ $templateColors['secondary'] ?? '#6b21a8' }};
            --color-accent: {{ $templateColors['accent'] ?? '#a855f7' }};
            --color-background: {{ $templateColors['background'] ?? '#ffffff' }};
            --color-text: {{ $templateColors['text'] ?? '#1f2937' }};
        }
        
        /* Apply template colors */
        .bg-primary { background-color: var(--color-primary) !important; }
        .bg-secondary { background-color: var(--color-secondary) !important; }
        .text-primary { color: var(--color-primary) !important; }
        .text-secondary { color: var(--color-secondary) !important; }
        .border-primary { border-color: var(--color-primary) !important; }
        
        /* Override Tailwind purple with template colors */
        .bg-purple-600 { background-color: var(--color-primary) !important; }
        .bg-purple-700 { background-color: var(--color-secondary) !important; }
        .text-purple-600 { color: var(--color-primary) !important; }
        .hover\:bg-purple-700:hover { background-color: var(--color-secondary) !important; }
        .hover\:text-purple-600:hover { color: var(--color-primary) !important; }
        .bg-purple-50 { background-color: {{ $templateColors['primary'] ?? '#9333ea' }}10 !important; }
    </style>
    @endif
    
    @if(isset($templateTypography))
    <style>
        body {
            font-family: {{ $templateTypography['font_family'] ?? 'Inter, system-ui, sans-serif' }};
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: {{ $templateTypography['heading_font'] ?? 'Inter, sans-serif' }};
        }
    </style>
    @endif
</head>
<body class="bg-slate-900 text-gray-100">
    @include('partials.navigation')

    <!-- Widgets Area -->
    @if(isset($widgets) && $widgets->count() > 0)
        @foreach($widgets as $widget)
            @if($widget->type === 'hero')
                @include('widgets.hero', ['widget' => $widget])
            @elseif($widget->type === 'features')
                @include('widgets.features', ['widget' => $widget])
            @elseif($widget->type === 'products')
                @include('widgets.products', ['widget' => $widget])
            @elseif($widget->type === 'blog')
                {{-- Show portfolio showcase instead of blog --}}
                @include('widgets.blog', ['widget' => $widget])
                {{-- @include('widgets.blog', ['widget' => $widget]) --}}
            @elseif($widget->type === 'documentation')
                @include('widgets.documentation', ['widget' => $widget])
            @elseif($widget->type !== 'footer')
                @include('widgets.' . $widget->type, ['widget' => $widget])
            @endif
        @endforeach
    @endif

    <!-- Footer -->
    @php
        $footerWidget = \App\Models\Widget::query()->where('type', 'footer')->active()->first();
        if (!$footerWidget) {
            $footerWidget = \App\Models\Widget::query()->where('type', 'footer')->first();
        }
    @endphp
    @if($footerWidget)
        @include('widgets.footer', ['widget' => $footerWidget])
    @endif

    <!-- Initialize AOS -->
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
    </script>
</body>
</html>
