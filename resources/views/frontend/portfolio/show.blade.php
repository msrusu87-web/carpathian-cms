<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $portfolio->title }} | {{ __('messages.portfolio') }} | Carphatian CMS</title>
    <meta name="description" content="{{ $portfolio->short_description }}">
    <meta name="keywords" content="{{ implode(', ', $portfolio->technologies ?? []) }}, {{ implode(', ', $portfolio->services ?? []) }}">
    
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $portfolio->title }}">
    <meta property="og:description" content="{{ $portfolio->short_description }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($portfolio->image)
    <meta property="og:image" content="{{ asset('storage/' . $portfolio->image) }}">
    @endif
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CreativeWork",
        "name": "{{ $portfolio->title }}",
        "description": "{{ $portfolio->short_description }}",
        "creator": {
            "@type": "Organization",
            "name": "Carphatian CMS",
            "url": "https://carphatian.ro"
        },
        @if($portfolio->client)
        "client": "{{ $portfolio->client }}",
        @endif
        @if($portfolio->completion_date)
        "dateCreated": "{{ $portfolio->completion_date->format('Y-m-d') }}",
        @endif
        @if($portfolio->website_url)
        "url": "{{ $portfolio->website_url }}",
        @endif
        "keywords": "{{ implode(', ', array_merge($portfolio->technologies ?? [], $portfolio->services ?? [])) }}"
    }
    </script>
</head>
<body class="bg-slate-900">
    @include('partials.navigation')

    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-{{ $portfolio->gradient_from }} via-purple-600 to-{{ $portfolio->gradient_to }} py-20">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center text-white">
                <span class="inline-block px-4 py-2 mb-6 text-sm font-semibold bg-white/20 rounded-full backdrop-blur-sm">
                    {{ $portfolio->category_label }}
                </span>
                <h1 class="text-4xl md:text-6xl font-black mb-6 leading-tight">
                    {{ $portfolio->title }}
                </h1>
                <p class="text-xl md:text-2xl opacity-90 max-w-3xl mx-auto">
                    {{ $portfolio->short_description }}
                </p>
                
                @if($portfolio->website_url)
                <a href="{{ $portfolio->website_url }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 mt-8 px-8 py-4 bg-white text-gray-900 font-bold rounded-xl hover:bg-gray-100 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    {{ __('messages.visit_site') }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-16">
        <div class="max-w-6xl mx-auto">
            <!-- Project Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 -mt-24 mb-16 relative z-20">
                @if($portfolio->client)
                <div class="bg-white rounded-2xl shadow-xl p-6 text-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('messages.client') }}</h3>
                    <p class="text-lg font-bold text-gray-900">{{ $portfolio->client }}</p>
                </div>
                @endif

                @if($portfolio->completion_date)
                <div class="bg-white rounded-2xl shadow-xl p-6 text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('messages.completion_date') }}</h3>
                    <p class="text-lg font-bold text-gray-900">{{ $portfolio->completion_date->format('F Y') }}</p>
                </div>
                @endif

                <div class="bg-white rounded-2xl shadow-xl p-6 text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('messages.category') }}</h3>
                    <p class="text-lg font-bold text-gray-900">{{ $portfolio->category_label }}</p>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Left Column - Description -->
                <div class="lg:col-span-2">
                    @if($portfolio->image)
                    <div class="mb-10">
                        <img src="{{ asset('storage/' . $portfolio->image) }}" 
                             alt="{{ $portfolio->title }}" 
                             class="w-full rounded-2xl shadow-xl"
                             onerror="this.style.display='none'">
                    </div>
                    @endif

                    @if($portfolio->full_description)
                    <div class="bg-white rounded-2xl shadow-lg p-8 mb-10">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('messages.about_project') }}</h2>
                        <div class="prose prose-lg max-w-none text-gray-600">
                            {!! $portfolio->full_description !!}
                        </div>
                    </div>
                    @endif

                    <!-- Gallery -->
                    @if($portfolio->gallery && count($portfolio->gallery) > 0)
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('messages.project_gallery') }}</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($portfolio->gallery as $image)
                            <a href="{{ asset('storage/' . $image) }}" target="_blank" class="block overflow-hidden rounded-xl hover:shadow-lg transition-shadow">
                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $portfolio->title }}" class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300">
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column - Tech & Services -->
                <div class="space-y-8">
                    @if($portfolio->technologies && count($portfolio->technologies) > 0)
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                            {{ __('messages.technologies_used') }}
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($portfolio->technologies as $tech)
                            <span class="px-4 py-2 bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 rounded-full text-sm font-semibold">
                                {{ $tech }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($portfolio->services && count($portfolio->services) > 0)
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            {{ __('messages.services_provided') }}
                        </h3>
                        <ul class="space-y-3">
                            @foreach($portfolio->services as $service)
                            <li class="flex items-center gap-3 text-gray-700">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $service }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- CTA Card -->
                    <div class="bg-gradient-to-br from-{{ $portfolio->gradient_from }} to-{{ $portfolio->gradient_to }} rounded-2xl shadow-lg p-6 text-white">
                        <h3 class="text-xl font-bold mb-3">{{ __('messages.interested_similar') }}</h3>
                        <p class="opacity-90 mb-6">{{ __('messages.lets_discuss') }}</p>
                        <a href="/contact" class="block w-full text-center py-3 bg-white text-gray-900 font-bold rounded-xl hover:bg-gray-100 transition-colors">
                            {{ __('messages.get_in_touch') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Related Projects -->
            @if($relatedPortfolios->count() > 0)
            <div class="mt-20">
                <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">{{ __('messages.related_projects') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedPortfolios as $related)
                    <a href="{{ route('portfolio.show', $related->slug) }}" class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="h-48 bg-gradient-to-br from-{{ $related->gradient_from }} to-{{ $related->gradient_to }} flex items-center justify-center">
                            @if($related->image)
                            <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover">
                            @else
                            <span class="text-white/80 text-6xl">📁</span>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors">{{ $related->title }}</h3>
                            <p class="text-gray-600 text-sm mt-2 line-clamp-2">{{ $related->short_description }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Back to Portfolio -->
            <div class="mt-16 text-center">
                <a href="/portfolios" class="inline-flex items-center gap-2 text-purple-600 font-semibold hover:text-purple-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('messages.back_to_portfolio') }}
                </a>
            </div>
        </div>
    </div>

    @include('widgets.footer')
</body>
</html>
