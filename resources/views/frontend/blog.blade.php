<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>{{ __('messages.blog_seo_title') }} | Carphatian CMS</title>
    <meta name="title" content="{{ __('messages.blog_seo_title') }}">
    <meta name="description" content="{{ __('messages.blog_seo_description') }}">
    <meta name="keywords" content="web development blog, programming tutorials, laravel tutorials, php development, javascript tips, react guides, vue.js tutorials, software engineering, coding best practices, web design trends, tech blog, developer resources, cms tutorials, fullstack development">
    <meta name="author" content="Carphatian CMS">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="{{ url('/blog') }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/blog') }}">
    <meta property="og:title" content="{{ __('messages.blog_seo_title') }}">
    <meta property="og:description" content="{{ __('messages.blog_seo_description') }}">
    <meta property="og:image" content="{{ asset('images/carpathian-og-image.jpg') }}">
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    <meta property="og:site_name" content="Carphatian CMS">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url('/blog') }}">
    <meta name="twitter:title" content="{{ __('messages.blog_seo_title') }}">
    <meta name="twitter:description" content="{{ __('messages.blog_seo_description') }}">
    <meta name="twitter:image" content="{{ asset('images/carpathian-og-image.jpg') }}">
    
    <!-- Additional SEO -->
    <meta name="geo.region" content="RO">
    <meta name="geo.placename" content="Romania">
    <meta name="language" content="{{ app()->getLocale() }}">
    <meta name="rating" content="general">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
            50% { box-shadow: 0 0 40px rgba(59, 130, 246, 0.8); }
        }
        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('partials.navigation')

    <!-- Hero Section with Animated Background -->
    <div class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 text-white py-24 overflow-hidden">
        <!-- Animated Blobs -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-32 left-1/2 w-96 h-96 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 4s;"></div>
        
        <div class="container mx-auto px-4 text-center relative z-10">
            <div class="inline-block mb-6">
                <div class="bg-white/20 backdrop-blur-sm rounded-full px-6 py-2 text-sm font-semibold">
                    <i class="fas fa-fire text-orange-300 mr-2"></i>
                    {{ $posts->total() }} {{ __('messages.articles_available') }}
                </div>
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold mb-6 leading-tight" data-aos="fade-up">
                📚 {{ __('messages.knowledge_library') }}<br>
                <span class="bg-gradient-to-r from-yellow-300 to-orange-400 bg-clip-text text-transparent">CMS Carphatian</span>
            </h1>
            <p class="text-xl md:text-2xl opacity-95 max-w-3xl mx-auto mb-8" data-aos="fade-up" data-aos-delay="100">
                {{ __('messages.blog_subtitle') }}
            </p>
            
            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                <div class="relative">
                    <input type="text" 
                           placeholder="{{ __('messages.search_articles') }}" 
                           class="w-full px-6 py-4 rounded-full text-gray-800 text-lg shadow-2xl focus:outline-none focus:ring-4 focus:ring-white/50 pl-14">
                    <i class="fas fa-search absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl"></i>
                    <button class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-2 rounded-full font-semibold hover:shadow-lg transition">
                        {{ __('messages.search') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <aside class="lg:w-1/4 space-y-6" data-aos="fade-right">
                <!-- Categories Widget -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-4">
                        <h3 class="text-xl font-bold flex items-center">
                            <i class="fas fa-folder-open mr-3"></i>
                            {{ __('messages.categories') }}
                        </h3>
                    </div>
                    <div class="p-4">
                        @php
                            $categories = App\Models\Category::withCount('posts')->get();
                        @endphp
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('blog') }}" 
                                   class="flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition group {{ !request('category') ? 'bg-gradient-to-r from-blue-50 to-purple-50 text-blue-600' : 'text-gray-700' }}">
                                    <span class="flex items-center font-medium">
                                        <i class="fas fa-th mr-3 text-blue-500"></i>
                                        {{ __('messages.all_articles') }}
                                    </span>
                                    <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-bold">
                                        {{ $posts->total() }}
                                    </span>
                                </a>
                            </li>
                            @foreach($categories as $category)
                            <li>
                                <a href="{{ route('blog') }}?category={{ $category->slug }}" 
                                   class="flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition group {{ request('category') == $category->slug ? 'bg-gradient-to-r from-blue-50 to-purple-50 text-blue-600' : 'text-gray-700' }}">
                                    <span class="font-medium group-hover:text-blue-600 transition">
                                        {{ $category->getTranslation('name', app()->getLocale()) }}
                                    </span>
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm font-semibold group-hover:bg-blue-100 group-hover:text-blue-600 transition">
                                        {{ $category->posts_count }}
                                    </span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Popular Tags -->
                <div class="bg-gradient-to-br from-orange-50 to-pink-50 rounded-2xl shadow-lg p-6 border border-orange-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-tags mr-3 text-orange-500"></i>
                        {{ __('messages.popular_topics') }}
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-white px-4 py-2 rounded-full text-sm font-semibold text-gray-700 shadow hover:shadow-md transition cursor-pointer">
                            #Design
                        </span>
                        <span class="bg-white px-4 py-2 rounded-full text-sm font-semibold text-gray-700 shadow hover:shadow-md transition cursor-pointer">
                            #Performanță
                        </span>
                        <span class="bg-white px-4 py-2 rounded-full text-sm font-semibold text-gray-700 shadow hover:shadow-md transition cursor-pointer">
                            #AI
                        </span>
                        <span class="bg-white px-4 py-2 rounded-full text-sm font-semibold text-gray-700 shadow hover:shadow-md transition cursor-pointer">
                            #Securitate
                        </span>
                        <span class="bg-white px-4 py-2 rounded-full text-sm font-semibold text-gray-700 shadow hover:shadow-md transition cursor-pointer">
                            #SEO
                        </span>
                        <span class="bg-white px-4 py-2 rounded-full text-sm font-semibold text-gray-700 shadow hover:shadow-md transition cursor-pointer">
                            #Tutorial
                        </span>
                    </div>
                </div>

                <!-- Newsletter Widget -->
                <div class="bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl shadow-2xl p-6 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="relative z-10">
                        <div class="text-4xl mb-4 float-animation">📬</div>
                        <h3 class="text-xl font-bold mb-2">{{ __('messages.newsletter') }}</h3>
                        <p class="text-sm opacity-90 mb-4">{{ __('messages.get_latest_articles') }}</p>
                        <input type="email" placeholder="{{ __('messages.your_email') }}" class="w-full px-4 py-2 rounded-lg text-gray-800 mb-3 focus:outline-none focus:ring-2 focus:ring-white">
                        <button class="w-full bg-white text-blue-600 font-bold py-2 rounded-lg hover:bg-opacity-90 transition">
                            {{ __('messages.subscribe') }}
                        </button>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="lg:w-3/4">
                @if($posts->count() > 0)
                    <!-- Filter Bar -->
                    <div class="bg-white rounded-xl shadow-md px-6 py-4 mb-8 flex items-center justify-between flex-wrap gap-4" data-aos="fade-up">
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="fas fa-filter text-blue-500"></i>
                            <span class="font-semibold">{{ __('messages.sort_by') }}:</span>
                            <select class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>{{ __('messages.newest') }}</option>
                                <option>{{ __('messages.most_popular') }}</option>
                                <option>{{ __('messages.most_read') }}</option>
                            </select>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ __('messages.showing_results') }} <span class="font-bold text-blue-600">{{ $posts->firstItem() }}-{{ $posts->lastItem() }}</span> {{ __('messages.of') }} <span class="font-bold">{{ $posts->total() }}</span> {{ __('messages.articles') }}
                        </div>
                    </div>

                    <!-- Blog Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                        @foreach($posts as $index => $post)
                        <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100" 
                                 data-aos="fade-up" 
                                 data-aos-delay="{{ $index * 50 }}">
                            <!-- Featured Image -->
                            <div class="relative h-56 overflow-hidden group">
                                @if($post->featured_image)
                                    <img src="{{ asset($post->featured_image) }}" 
                                         alt="{{ $post->getTranslation('title', app()->getLocale()) }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 h-full flex items-center justify-center">
                                        <i class="fas fa-newspaper text-white text-6xl opacity-40"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                                
                                <!-- Category Badge -->
                                @if($post->category)
                                <div class="absolute top-4 left-4">
                                    <span class="bg-white/95 backdrop-blur-sm text-blue-600 text-xs font-bold px-4 py-2 rounded-full shadow-lg">
                                        <i class="fas fa-tag mr-1"></i>
                                        {{ $post->category->getTranslation('name', app()->getLocale()) }}
                                    </span>
                                </div>
                                @endif
                                
                                <!-- Date -->
                                <div class="absolute bottom-4 right-4 bg-white/95 backdrop-blur-sm rounded-xl px-3 py-2 shadow-lg">
                                    <div class="text-2xl font-bold text-blue-600">{{ $post->published_at->format('d') }}</div>
                                    <div class="text-xs text-gray-600 font-semibold">{{ $post->published_at->translatedFormat('M') }}</div>
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="p-6">
                                <h2 class="text-2xl font-bold mb-3 text-gray-800 hover:text-blue-600 transition line-clamp-2">
                                    <a href="{{ route('post.show', $post->slug) }}">
                                        {{ $post->getTranslation('title', app()->getLocale()) }}
                                    </a>
                                </h2>
                                
                                <p class="text-gray-600 mb-4 line-clamp-3 leading-relaxed">
                                    {{ Str::limit($post->getTranslation('excerpt', app()->getLocale()), 150) }}
                                </p>
                                
                                <!-- Meta Info -->
                                <div class="flex items-center gap-6 text-sm text-gray-500 mb-4 pb-4 border-b border-gray-100">
                                    <span class="flex items-center">
                                        <i class="far fa-eye mr-2 text-blue-500"></i>
                                        <span class="font-semibold">{{ $post->views ?? 0 }}</span>
                                    </span>
                                    <span class="flex items-center">
                                        <i class="far fa-clock mr-2 text-purple-500"></i>
                                        <span>5 min</span>
                                    </span>
                                    <span class="flex items-center">
                                        <i class="far fa-comments mr-2 text-green-500"></i>
                                        <span>12</span>
                                    </span>
                                </div>
                                
                                <!-- Read More Button -->
                                <a href="{{ route('post.show', $post->slug) }}" 
                                   class="group inline-flex items-center justify-center w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <i class="fas fa-book-open mr-2"></i>
                                    {{ __('messages.read_full_article') }}
                                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </article>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    @if($posts->hasPages())
                    <div class="flex justify-center" data-aos="fade-up">
                        <div class="bg-white rounded-xl shadow-lg px-8 py-4">
                            {{ $posts->links() }}
                        </div>
                    </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-20 bg-white rounded-2xl shadow-lg" data-aos="fade-up">
                        <div class="inline-flex items-center justify-center w-32 h-32 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full mb-6 float-animation">
                            <i class="fas fa-newspaper text-5xl text-blue-500"></i>
                        </div>
                        <h2 class="text-4xl font-bold text-gray-800 mb-3">{{ __('messages.no_articles_yet') }}</h2>
                        <p class="text-gray-600 text-lg">{{ __('messages.check_back_soon') }}</p>
                    </div>
                @endif
            </main>
        </div>
    </div>

    <!-- Call to Action Banner -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 py-16 mt-20" data-aos="fade-up">
        <div class="container mx-auto px-4 text-center text-white">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">🚀 {{ __('messages.ready_to_start') }}</h2>
            <p class="text-xl mb-8 opacity-90">{{ __('messages.discover_platform') }}</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('shop.index') }}" class="bg-white text-blue-600 font-bold px-8 py-4 rounded-xl hover:bg-opacity-90 transition shadow-2xl">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    {{ __('messages.explore_products') }}
                </a>
                <a href="#" class="bg-transparent border-2 border-white text-white font-bold px-8 py-4 rounded-xl hover:bg-white hover:text-blue-600 transition">
                    <i class="fas fa-play-circle mr-2"></i>
                    {{ __('messages.watch_demo') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @php
        $footerWidget = \App\Models\Widget::where('type', 'footer')->where('status', 'active')->first();
    @endphp
    @if($footerWidget)
        @include('widgets.footer', ['widget' => $footerWidget])
    @endif

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
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
