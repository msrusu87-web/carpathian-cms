@php
    $content = is_string($widget->content) ? json_decode($widget->content, true) : $widget->content;
    $heading = $content['heading'] ?? 'Ultimele Articole';
    $subheading = $content['subheading'] ?? 'Rămâi la curent cu cele mai noi articole';
    $limit = $content['limit'] ?? 3;
    $posts = \App\Models\Post::where('status', 'published')->latest()->take($limit)->get();
@endphp

<!-- Blog Section Widget - Viral Pro Design -->
<section class="py-20 bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-0 left-0 w-64 h-64 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute top-0 right-0 w-64 h-64 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-64 h-64 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-bold px-4 py-2 rounded-full mb-4">
                <i class="far fa-newspaper mr-2"></i>BLOG
            </span>
            <h2 class="text-5xl md:text-6xl font-black text-gray-900 mb-4 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                {{ $heading }}
            </h2>
            @if($subheading)
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">{{ $subheading }}</p>
            @endif
        </div>
        
        @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                @foreach($posts as $index => $post)
                    <article class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2" 
                             data-aos="fade-up" 
                             data-aos-delay="{{ $index * 100 }}">
                        <!-- Image Container -->
                        <div class="relative overflow-hidden h-64">
                            @if($post->featured_image)
                                <img src="{{ asset($post->featured_image) }}" 
                                     alt="{{ $post->getTranslation('title', app()->getLocale()) }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 via-purple-600 to-pink-500 flex items-center justify-center">
                                    <i class="far fa-newspaper text-white text-6xl opacity-50"></i>
                                </div>
                            @endif
                            
                            <!-- Category Badge -->
                            @if($post->category)
                                <div class="absolute top-4 left-4">
                                    <span class="bg-white/95 backdrop-blur-sm text-blue-600 text-xs font-bold px-4 py-2 rounded-full shadow-lg">
                                        {{ $post->category->getTranslation('name', app()->getLocale()) }}
                                    </span>
                                </div>
                            @endif

                            <!-- Reading Time -->
                            <div class="absolute top-4 right-4">
                                <span class="bg-black/70 backdrop-blur-sm text-white text-xs font-semibold px-3 py-1 rounded-full">
                                    <i class="far fa-clock mr-1"></i>5 min
                                </span>
                            </div>

                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </div>
                        
                        <div class="p-6">
                            <!-- Date & Views -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                <span class="flex items-center">
                                    <i class="far fa-calendar mr-2 text-blue-600"></i>
                                    {{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}
                                </span>
                                <span class="flex items-center">
                                    <i class="far fa-eye mr-1 text-purple-600"></i>
                                    {{ rand(1200, 5400) }}
                                </span>
                            </div>
                            
                            <!-- Title -->
                            <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                <a href="{{ route('post.show', $post->slug) }}">
                                    {{ $post->getTranslation('title', app()->getLocale()) }}
                                </a>
                            </h3>
                            
                            <!-- Excerpt -->
                            <p class="text-gray-600 mb-4 line-clamp-3 leading-relaxed">
                                {{ $post->getTranslation('excerpt', app()->getLocale()) }}
                            </p>
                            
                            <!-- Read More Button -->
                            <a href="{{ route('post.show', $post->slug) }}" 
                               class="inline-flex items-center text-blue-600 hover:text-blue-700 font-bold group/link">
                                Citește Mai Mult
                                <i class="fas fa-arrow-right ml-2 transform group-hover/link:translate-x-2 transition-transform"></i>
                            </a>
                        </div>

                        <!-- Author Footer -->
                        <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold mr-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Admin</p>
                                    <p class="text-xs text-gray-500">Autor</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="text-gray-400 hover:text-red-500 transition-colors">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="text-gray-400 hover:text-blue-500 transition-colors">
                                    <i class="far fa-bookmark"></i>
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            
            <!-- View All Button -->
            <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                <a href="{{ route('blog') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-10 py-5 rounded-xl font-bold text-lg shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300 group">
                    Vezi Toate Articolele
                    <i class="fas fa-arrow-right ml-3 transform group-hover:translate-x-2 transition-transform"></i>
                </a>
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-2xl shadow-lg">
                <i class="far fa-newspaper text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Nu există articole disponibile încă.</p>
            </div>
        @endif
    </div>
</section>

<style>
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
    animation: blob 7s infinite;
}
.animation-delay-2000 {
    animation-delay: 2s;
}
.animation-delay-4000 {
    animation-delay: 4s;
}
</style>
