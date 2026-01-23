<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.our_portfolio') }} | Carphatian CMS</title>
    <meta name="description" content="{{ __('messages.portfolio_subtitle') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-900">
    @include('partials.navigation')
    
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-blue-50 py-20">
    <div class="container mx-auto px-4">
        <!-- Header Section with Animation -->
        <div class="text-center mb-20 animate-fade-in">
            <span class="inline-block px-6 py-2 mb-6 text-sm font-semibold text-purple-700 bg-purple-100 rounded-full shadow-sm hover:shadow-md transition-shadow duration-300">
                {{ __('messages.portfolio') }}
            </span>
            <h1 class="text-6xl md:text-7xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 mb-6 leading-tight">
                {{ __('messages.our_portfolio') }}
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 max-w-4xl mx-auto leading-relaxed font-light">
                {{ __('messages.portfolio_subtitle') }}
            </p>
        </div>

        <!-- Portfolio Grid with Modern Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto mb-16">
            @forelse($portfolios as $portfolio)
            <div class="group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer">
                <div class="absolute inset-0 bg-gradient-to-br from-{{ $portfolio->gradient_from }}/10 to-{{ $portfolio->gradient_to }}/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>
                
                <div class="relative h-72 overflow-hidden bg-gradient-to-br from-{{ $portfolio->gradient_from }} via-purple-600 to-{{ $portfolio->gradient_to }}">
                    @if($portfolio->image)
                    <img src="{{ asset('storage/' . $portfolio->image) }}" 
                         alt="{{ $portfolio->title }}" 
                         class="w-full h-full object-cover object-top group-hover:scale-110 transition-transform duration-700"
                         loading="lazy"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $portfolio->gradient_from }}/90 to-{{ $portfolio->gradient_to }}/90 flex items-center justify-center" @if($portfolio->image) style="display:none;" @endif>
                        <div class="text-center text-white p-6">
                            <svg class="w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-base font-bold tracking-wide">{{ $portfolio->category_label }}</p>
                        </div>
                    </div>
                    <div class="absolute top-4 right-4 z-20">
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-{{ $portfolio->badge_color }} shadow-lg backdrop-blur-sm">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                            </svg>
                            {{ $portfolio->category_label }}
                        </span>
                    </div>
                    @if($portfolio->is_featured)
                    <div class="absolute top-4 left-4 z-20">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-yellow-400 text-yellow-900 shadow-lg">
                            ⭐ Featured
                        </span>
                    </div>
                    @endif
                </div>
                
                <div class="p-8 relative z-20">
                    <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-{{ $portfolio->gradient_from }} group-hover:to-{{ $portfolio->gradient_to }} transition-all duration-300">
                        {{ $portfolio->title }}
                    </h3>
                    <p class="text-gray-600 mb-6 leading-relaxed line-clamp-3">
                        {{ $portfolio->short_description }}
                    </p>
                    
                    <div class="flex flex-wrap gap-2 mb-6">
                        @foreach(array_slice($portfolio->technologies ?? [], 0, 3) as $tech)
                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-medium">{{ $tech }}</span>
                        @endforeach
                        @if(count($portfolio->technologies ?? []) > 3)
                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-medium">+{{ count($portfolio->technologies) - 3 }}</span>
                        @endif
                    </div>
                    
                    <div class="flex gap-3">
                        <a href="{{ route('portfolio.show', $portfolio->slug) }}"
                           class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-{{ $portfolio->gradient_from }} to-{{ $portfolio->gradient_to }} text-white font-semibold rounded-xl hover:opacity-90 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            {{ __('messages.read_more') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        @if($portfolio->website_url)
                        <a href="{{ $portfolio->website_url }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center justify-center px-4 py-3 border-2 border-gray-200 text-gray-700 font-semibold rounded-xl hover:border-{{ $portfolio->gradient_from }} hover:text-{{ $portfolio->gradient_from }} transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-20">
                <p class="text-gray-500 text-xl">{{ __('messages.no_portfolios') }}</p>
            </div>
            @endforelse
        </div>

        <!-- CTA Section with Modern Gradient -->
        <div class="relative mt-24 max-w-5xl mx-auto">
            <div class="absolute inset-0 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 rounded-3xl blur-2xl opacity-20 animate-pulse"></div>
            
            <div class="relative bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 opacity-10"></div>
                
                <div class="relative p-12 md:p-16 text-center">
                    <div class="inline-block p-4 bg-gradient-to-br from-purple-100 to-pink-100 rounded-2xl mb-6">
                        <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    
                    <h2 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 mb-6">
                        {{ __('messages.interested_work') }}
                    </h2>
                    
                    <p class="text-xl md:text-2xl text-gray-700 mb-10 max-w-3xl mx-auto leading-relaxed font-light">
                        {{ __('messages.interested_work_desc') }}
                    </p>
                    
                    <a href="/contact" 
                       class="inline-flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 text-white text-lg font-bold rounded-2xl hover:from-purple-700 hover:via-pink-700 hover:to-blue-700 shadow-2xl hover:shadow-3xl transform hover:scale-105ransition-all duration-300 group">
                        {{ __('messages.get_in_touch') }}
                        <svg class="w-6 h-6 group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 1s ease-out;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

@include('widgets.footer')
</body>
</html>
