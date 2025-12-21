<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>{{ $page->meta_title ?? $page->getTranslation('title', app()->getLocale()) }} | Carphatian CMS - Web Development Services</title>
    <meta name="title" content="{{ $page->meta_title ?? $page->getTranslation('title', app()->getLocale()) }} | Carphatian CMS">
    @if($page->meta_description)
    <meta name="description" content="{{ $page->meta_description }}">
    @else
    <meta name="description" content="{{ Str::limit(strip_tags($page->getTranslation('excerpt', app()->getLocale()) ?? $page->getTranslation('content', app()->getLocale())), 160) }}">
    @endif
    @if($page->meta_keywords)
    <meta name="keywords" content="{{ $page->meta_keywords }}, carphatian cms, web development, software services">
    @else
    <meta name="keywords" content="carphatian cms, web development on demand, custom software, professional services, digital solutions">
    @endif
    <meta name="author" content="Carphatian CMS">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $page->meta_title ?? $page->getTranslation('title', app()->getLocale()) }}">
    <meta property="og:description" content="{{ $page->meta_description ?? Str::limit(strip_tags($page->getTranslation('excerpt', app()->getLocale()) ?? $page->getTranslation('content', app()->getLocale())), 160) }}">
    @if($page->featured_image)
    <meta property="og:image" content="{{ Storage::url($page->featured_image) }}">
    @else
    <meta property="og:image" content="{{ asset('images/carpathian-og-image.jpg') }}">
    @endif
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    <meta property="og:site_name" content="Carphatian CMS">
    @if($page->created_at)
    <meta property="article:published_time" content="{{ $page->created_at->toIso8601String() }}">
    @endif
    @if($page->updated_at)
    <meta property="article:modified_time" content="{{ $page->updated_at->toIso8601String() }}">
    @endif
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $page->meta_title ?? $page->getTranslation('title', app()->getLocale()) }}">
    <meta name="twitter:description" content="{{ $page->meta_description ?? Str::limit(strip_tags($page->getTranslation('excerpt', app()->getLocale()) ?? $page->getTranslation('content', app()->getLocale())), 160) }}">
    @if($page->featured_image)
    <meta name="twitter:image" content="{{ Storage::url($page->featured_image) }}">
    @else
    <meta name="twitter:image" content="{{ asset('images/carpathian-og-image.jpg') }}">
    @endif
    
    <!-- Additional SEO -->
    <meta name="geo.region" content="RO">
    <meta name="geo.placename" content="Romania">
    <meta name="language" content="{{ app()->getLocale() }}">
    <meta name="rating" content="general">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    @include('partials.navigation')

    <!-- Page Content -->
    <main class="py-12">
        <div class="max-w-4xl mx-auto px-4">
            <article class="bg-white rounded-lg shadow-lg p-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-6">{{ $page->getTranslation('title', app()->getLocale()) }}</h1>
                
                @if($page->featured_image)
                <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->getTranslation('title', app()->getLocale()) }}" class="w-full h-64 object-cover rounded-lg mb-6">
                @endif

                @if($page->excerpt)
                <div class="text-xl text-gray-600 mb-6 pb-6 border-b">
                    {{ $page->getTranslation('excerpt', app()->getLocale()) }}
                </div>
                @endif

                <div class="prose max-w-none">
                    {!! $page->getTranslation('content', app()->getLocale()) !!}
                </div>

                @if($page->meta_keywords)
                <div class="mt-8 pt-6 border-t">
                    <p class="text-sm text-gray-500">
                        <strong>{{ __('messages.keywords') }}:</strong> {{ $page->meta_keywords }}
                    </p>
                </div>
                @endif
            </article>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">{{ config('app.name') }}</h3>
                    <p class="text-gray-400">{{ __('messages.professional_solutions') }}</p>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">{{ __('messages.pages') }}</h4>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-400 hover:text-white">{{ __('messages.home') }}</a></li>
                        @php
                            $footerPages = \App\Models\Page::where('status', 'published')
                                ->where('is_homepage', false)
                                ->orderBy('title')
                                ->get();
                        @endphp
                        @foreach($footerPages as $footerPage)
                            <li><a href="/{{ $footerPage->slug }}" class="text-gray-400 hover:text-white">{{ $footerPage->getTranslation('title', app()->getLocale()) }}</a></li>
                        @endforeach
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">{{ __('messages.services') }}</h4>
                    <ul class="space-y-2">
                        <li><a href="/shop" class="text-gray-400 hover:text-white">{{ __('messages.shop') }}</a></li>
                        <li><a href="/blog" class="text-gray-400 hover:text-white">{{ __('messages.blog') }}</a></li>
                        <li><a href="/contact" class="text-gray-400 hover:text-white">{{ __('messages.contact') }}</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">{{ __('messages.admin') }}</h4>
                    <ul class="space-y-2">
                        <li><a href="/admin" class="text-gray-400 hover:text-white">{{ __('messages.dashboard') }}</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>By Carphatian</p>
            </div>
        </div>
    </footer>
</body>
</html>
