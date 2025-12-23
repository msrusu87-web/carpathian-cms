@php
    $content = is_string($widget->content) ? json_decode($widget->content, true) : $widget->content;
    $heading = $content['heading'] ?? null;
    $features = $content['features'] ?? [];
    
    // Default features if none configured - store translation keys, not translated text
    if(empty($features)) {
        $features = [
            ['icon' => '🎨', 'title_key' => 'modern_design', 'desc_key' => 'modern_design_desc', 'link' => '/posts/design-modern-ghid-complet-interfete-intuitive'],
            ['icon' => '⚡', 'title_key' => 'high_performance', 'desc_key' => 'high_performance_desc', 'link' => '/posts/performanta-ridicata-optimizare-viteza-maxima'],
            ['icon' => '🤖', 'title_key' => 'ai_integration', 'desc_key' => 'ai_integration_desc', 'link' => '/posts/integrare-ai-inteligenta-artificiala-cms'],
            ['icon' => '🔒', 'title_key' => 'security', 'desc_key' => 'security_desc', 'link' => '/posts/securitate-avansata-protectie-date-cms'],
            ['icon' => '📱', 'title_key' => 'multi_platform', 'desc_key' => 'multi_platform_desc', 'link' => '/posts/multi-platform-functionare-orice-dispozitiv'],
            ['icon' => '🔧', 'title_key' => 'customizable', 'desc_key' => 'customizable_desc', 'link' => '/posts/personalizare-completa-configurare-cms'],
        ];
    }
@endphp

<!-- Features Section Widget - Compact Design -->
<section class="py-12 bg-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-purple-600"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-8" data-aos="fade-up">
            <span class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white text-xs font-bold px-3 py-1.5 rounded-full mb-3">
                <i class="fas fa-star mr-1"></i>FEATURES
            </span>
            <h2 class="text-2xl md:text-3xl font-black text-gray-900 mb-2">
                {{ $heading ?? __('messages.features_title') }}
            </h2>
            <p class="text-sm text-gray-600 max-w-2xl mx-auto">
                {{ __('messages.features_discover') }}
            </p>
        </div>
        
        @if(count($features) > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                @foreach($features as $index => $feature)
                    <a href="{{ $feature['link'] ?? '#' }}" class="group relative block" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                        <!-- Card -->
                        <div class="relative bg-gradient-to-br from-white to-gray-50 rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 overflow-hidden cursor-pointer h-full">
                            <!-- Decorative Corner -->
                            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-bl-full transform translate-x-4 -translate-y-4 group-hover:scale-150 transition-transform duration-500"></div>
                            
                            <!-- Icon -->
                            <div class="relative z-10 mb-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-xl transform group-hover:rotate-12 group-hover:scale-110 transition-all duration-500 shadow-md">
                                    {{ $feature['icon'] ?? '⭐' }}
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <h3 class="text-sm font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors relative z-10 line-clamp-1">
                                {{ isset($feature['title_key']) ? __('messages.' . $feature['title_key']) : ($feature['title'] ?? '') }}
                            </h3>
                            <p class="text-xs text-gray-600 leading-snug relative z-10 line-clamp-2">
                                {{ isset($feature['desc_key']) ? Str::limit(__('messages.' . $feature['desc_key']), 50) : Str::limit($feature['description'] ?? '', 50) }}
                            </p>

                            <!-- Animated Border -->
                            <div class="absolute inset-0 rounded-xl border-2 border-blue-500 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Latest Blog Articles - Compact Horizontal Design -->
            @php
                $latestPosts = \App\Models\Post::where('status', 'published')
                    ->latest()
                    ->take(3)
                    ->get();
            @endphp
            
            @if($latestPosts->count() > 0)
            <div class="mt-10" data-aos="fade-up" data-aos-delay="300">
                <!-- Section Header -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="h-px w-12 bg-gradient-to-r from-blue-600 to-purple-600"></div>
                        <span class="text-sm font-bold uppercase tracking-widest text-gray-500">{{ __('messages.from_blog') }}</span>
                    </div>
                    <a href="{{ route('blog') }}" class="text-sm font-semibold text-blue-600 hover:text-purple-600 transition-colors flex items-center gap-2">
                        {{ __('messages.view_all_blog') }} <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
                
                <!-- Horizontal Blog Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($latestPosts as $post)
                    <a href="/posts/{{ $post->slug }}" class="group">
                        <div class="flex items-center gap-4 p-4 bg-white/80 backdrop-blur-sm rounded-2xl border border-gray-100 hover:border-blue-200 hover:shadow-lg transition-all duration-300">
                            <!-- Mini Thumbnail -->
                            <div class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden bg-gradient-to-br from-blue-500 to-purple-600">
                                @if($post->featured_image)
                                    <img src="{{ asset($post->featured_image) }}" alt="{{ $post->getTranslation('title', app()->getLocale()) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-newspaper text-white/80 text-xl"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-blue-600 mb-1">
                                    {{ $post->created_at->format('d M Y') }}
                                </p>
                                <h4 class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">
                                    {{ Str::limit($post->getTranslation('title', app()->getLocale()), 50) }}
                                </h4>
                            </div>
                            
                            <!-- Arrow Icon -->
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100 group-hover:bg-blue-600 flex items-center justify-center transition-all duration-300">
                                <i class="fas fa-arrow-right text-xs text-gray-400 group-hover:text-white transition-colors"></i>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        @else
            <div class="text-center py-16 bg-gray-50 rounded-2xl">
                <i class="fas fa-star text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">{{ __('messages.no_features_configured') }}</p>
            </div>
        @endif
    </div>
</section>
