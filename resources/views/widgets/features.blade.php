@php
    $content = is_string($widget->content) ? json_decode($widget->content, true) : $widget->content;
    $heading = $content['heading'] ?? null;
    $features = $content['features'] ?? [];
    
    // Get latest blog posts for the features section
    $latestPosts = \App\Models\Post::where('status', 'published')
        ->latest('published_at')
        ->take(6)
        ->get();
    
    // Map posts to features with appropriate icons
    $iconMap = [
        'securitate' => '🔒',
        'mentenanta' => '🔧',
        'branding' => '🎨',
        'software' => '💻',
        'ai' => '🤖',
        'magazin' => '🛒',
        'web' => '🌐',
        'design' => '🎨',
    ];
    
    if($latestPosts->count() > 0) {
        $features = $latestPosts->map(function($post) use ($iconMap) {
            $locale = app()->getLocale();
            $title = $post->getTranslation('title', $locale, false) ?: $post->getTranslation('title', 'ro', false) ?: $post->title;
            $excerpt = $post->getTranslation('excerpt', $locale, false) ?: $post->getTranslation('excerpt', 'ro', false) ?: '';
            
            // Determine icon based on slug
            $icon = '📰';
            foreach ($iconMap as $key => $emoji) {
                if (str_contains(strtolower($post->slug), $key)) {
                    $icon = $emoji;
                    break;
                }
            }
            
            return [
                'icon' => $icon,
                'title' => $title,
                'description' => $excerpt,
                'link' => '/blog/' . $post->slug,
            ];
        })->toArray();
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
                <i class="fas fa-newspaper mr-1"></i>{{ __('messages.blog') }}
            </span>
            <h2 class="text-2xl md:text-3xl font-black text-gray-900 mb-2">
                {{ __('messages.latest_articles') }}
            </h2>
            <p class="text-base text-gray-600 max-w-2xl mx-auto">
                {{ __('messages.blog_subtitle') }}
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($features as $feature)
            <a href="{{ $feature['link'] ?? '#' }}" 
               class="group bg-gradient-to-br from-gray-50 to-white hover:from-blue-50 hover:to-purple-50 rounded-lg p-4 border border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all duration-300 flex flex-col relative overflow-hidden"
               data-aos="fade-up"
               data-aos-delay="{{ $loop->index * 50 }}">
                
                <!-- Hover Effect Background -->
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                <!-- Icon with Animation -->
                <div class="text-3xl mb-2 transform group-hover:scale-110 transition-transform duration-300 relative z-10">
                    {{ $feature['icon'] ?? '📰' }}
                </div>
                
                <!-- Title -->
                <h3 class="text-sm font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors relative z-10 line-clamp-2">
                    {{ $feature['title'] ?? '' }}
                </h3>
                
                <!-- Description -->
                <p class="text-xs text-gray-600 leading-snug relative z-10 line-clamp-2">
                    {{ Str::limit($feature['description'] ?? '', 60) }}
                </p>
                
                <!-- Hover Arrow -->
                <div class="mt-auto pt-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 relative z-10">
                    <span class="text-blue-600 text-xs font-medium">{{ __('messages.read_more') }} <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
            @endforeach
        </div>
        
        <!-- View All Articles Button -->
        <div class="text-center mt-8" data-aos="fade-up">
            <a href="/blog" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-full hover:shadow-lg hover:scale-105 transition-all duration-300">
                <i class="fas fa-book-open mr-2"></i>
                {{ __('messages.view_all_articles') }}
            </a>
        </div>
    </div>
</section>
