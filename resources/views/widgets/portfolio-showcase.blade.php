@php
    $portfolios = \App\Models\Portfolio::active()->ordered()->limit(3)->get();
@endphp

<!-- Portfolio Showcase Widget - Compact -->
<section class="py-8 bg-slate-800/80 relative overflow-hidden">
    <div class="container mx-auto px-4 relative z-10">
        <div class="flex items-center justify-between mb-6" data-aos="fade-up">
            <div>
                <span class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 text-white text-xs font-bold px-3 py-1 rounded-full mb-2">
                    <i class="fas fa-briefcase mr-1"></i>{{ __('messages.portfolio') }}
                </span>
                <h2 class="text-xl md:text-2xl font-black text-white">
                    {{ __('messages.our_latest_projects') }}
                </h2>
            </div>
            <a href="{{ route('portfolio.index') }}" 
               class="inline-flex items-center bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-2 rounded-lg font-semibold text-sm shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                {{ __('messages.view_all_projects') }}
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        
        @if($portfolios->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($portfolios as $index => $portfolio)
                    <article class="group bg-slate-700 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1"
                             data-aos="fade-up" 
                             data-aos-delay="{{ $index * 50 }}">
                        <!-- Horizontal Layout -->
                        <div class="flex">
                            <!-- Image -->
                            <div class="relative w-24 h-24 flex-shrink-0 overflow-hidden">
                                @if($portfolio->image)
                                    <img src="{{ asset('storage/' . $portfolio->image) }}"
                                         alt="{{ $portfolio->title }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                @endif
                                <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center" @if($portfolio->image) style="display:none;" @endif>
                                    <svg class="w-8 h-8 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                
                                <!-- Category Badge -->
                                <div class="absolute top-1 left-1">
                                    <span class="bg-white/95 text-purple-600 text-[9px] font-bold px-1.5 py-0.5 rounded-full">
                                        {{ \Illuminate\Support\Str::limit($portfolio->category_label, 10) }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 p-3 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-sm font-bold text-white truncate group-hover:text-purple-600 transition-colors">
                                        {{ \Illuminate\Support\Str::limit($portfolio->title, 25) }}
                                    </h3>
                                    @if($portfolio->is_featured)
                                    <span class="text-yellow-500 text-xs">⭐</span>
                                    @endif
                                </div>
                                
                                <!-- Technologies -->
                                @if($portfolio->technologies && count($portfolio->technologies) > 0)
                                <div class="flex flex-wrap gap-1 mb-2">
                                    @foreach(array_slice($portfolio->technologies, 0, 2) as $tech)
                                    <span class="px-1.5 py-0.5 bg-gray-100 text-gray-300 rounded text-[10px] font-medium">{{ $tech }}</span>
                                    @endforeach
                                    @if(count($portfolio->technologies) > 2)
                                    <span class="px-1.5 py-0.5 bg-gray-100 text-gray-300 rounded text-[10px] font-medium">+{{ count($portfolio->technologies) - 2 }}</span>
                                    @endif
                                </div>
                                @endif
                                
                                <!-- Actions -->
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('portfolio.show', $portfolio->slug) }}"
                                       class="text-xs text-purple-600 hover:text-purple-700 font-semibold">
                                        Detalii →
                                    </a>
                                    @if($portfolio->website_url)
                                    <a href="{{ $portfolio->website_url }}" target="_blank" rel="noopener noreferrer" 
                                       class="text-xs text-gray-500 hover:text-purple-600">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 bg-slate-700 rounded-xl shadow-md">
                <i class="fas fa-briefcase text-gray-300 text-3xl mb-2"></i>
                <p class="text-gray-500 text-sm">{{ __('messages.no_portfolios') }}</p>
            </div>
        @endif
    </div>
</section>
