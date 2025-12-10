<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->getTranslation('title', app()->getLocale()) }} - {{ config('app.name') }}</title>
    <meta name="description" content="{{ Str::limit($post->getTranslation('excerpt', app()->getLocale()), 160) }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .prose {
            max-width: 65ch;
        }
        .prose p {
            margin-bottom: 1.25em;
            line-height: 1.75;
        }
        .prose h2 {
            font-size: 1.5em;
            font-weight: 700;
            margin-top: 2em;
            margin-bottom: 1em;
        }
        .prose h3 {
            font-size: 1.25em;
            font-weight: 600;
            margin-top: 1.6em;
            margin-bottom: 0.6em;
        }
        .prose ul, .prose ol {
            margin-bottom: 1.25em;
            padding-left: 1.625em;
        }
        .prose li {
            margin-bottom: 0.5em;
        }
        .prose img {
            margin-top: 2em;
            margin-bottom: 2em;
            border-radius: 0.5rem;
        }
        .prose a {
            color: #2563eb;
            text-decoration: underline;
        }
        .prose a:hover {
            color: #1d4ed8;
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('partials.navigation')

    <!-- Article Header -->
    <article class="py-12">
        <!-- Featured Image -->
        @if($post->featured_image)
        <div class="w-full h-96 mb-12 overflow-hidden">
            <img src="{{ asset($post->featured_image) }}" 
                 alt="{{ $post->getTranslation('title', app()->getLocale()) }}" 
                 class="w-full h-full object-cover">
        </div>
        @endif

        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Breadcrumb -->
                <nav class="text-sm mb-6">
                    <ol class="flex items-center space-x-2 text-gray-600">
                        <li><a href="/" class="hover:text-blue-600">Acasă</a></li>
                        <li><i class="fas fa-chevron-right text-xs"></i></li>
                        <li><a href="/blog" class="hover:text-blue-600">Blog</a></li>
                        <li><i class="fas fa-chevron-right text-xs"></i></li>
                        @if($post->category)
                        <li><a href="/blog?category={{ $post->category->slug }}" class="hover:text-blue-600">{{ $post->category->getTranslation('name', app()->getLocale()) }}</a></li>
                        <li><i class="fas fa-chevron-right text-xs"></i></li>
                        @endif
                        <li class="text-blue-600 font-medium truncate">{{ Str::limit($post->getTranslation('title', app()->getLocale()), 50) }}</li>
                    </ol>
                </nav>

                <!-- Article Meta -->
                <div class="mb-8">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        @if($post->category)
                        <span class="inline-flex items-center bg-blue-100 text-blue-600 text-sm font-semibold px-4 py-1.5 rounded-full">
                            <i class="fas fa-tag mr-2"></i>
                            {{ $post->category->getTranslation('name', app()->getLocale()) }}
                        </span>
                        @endif
                        
                        @if($post->is_featured)
                        <span class="inline-flex items-center bg-yellow-400 text-yellow-900 text-sm font-bold px-4 py-1.5 rounded-full">
                            <i class="fas fa-star mr-2"></i>
                            {{ __('messages.featured') }}
                        </span>
                        @endif
                    </div>

                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                        {{ $post->getTranslation('title', app()->getLocale()) }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-6 text-gray-600">
                        <div class="flex items-center">
                            <i class="far fa-calendar-alt mr-2 text-blue-500"></i>
                            <span>{{ $post->published_at->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="far fa-eye mr-2 text-blue-500"></i>
                            <span>{{ $post->views }} {{ __('messages.views') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="far fa-clock mr-2 text-blue-500"></i>
                            <span>5 min citire</span>
                        </div>
                    </div>
                </div>

                <!-- Article Content -->
                <div class="prose prose-lg max-w-none mb-12">
                    @if($post->getTranslation('excerpt', app()->getLocale()))
                    <div class="text-xl text-gray-700 font-medium mb-8 p-6 bg-blue-50 border-l-4 border-blue-500 rounded-r-lg">
                        {{ $post->getTranslation('excerpt', app()->getLocale()) }}
                    </div>
                    @endif

                    <div class="text-gray-800 leading-relaxed">
                        {!! $post->getTranslation('content', app()->getLocale()) !!}
                    </div>
                </div>

                <!-- Share Buttons -->
                <div class="border-t border-b border-gray-200 py-6 mb-12">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribuie articolul:</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                           target="_blank"
                           class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition">
                            <i class="fab fa-facebook-f mr-2"></i>
                            Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->getTranslation('title', app()->getLocale())) }}" 
                           target="_blank"
                           class="inline-flex items-center bg-sky-500 hover:bg-sky-600 text-white px-6 py-3 rounded-lg transition">
                            <i class="fab fa-twitter mr-2"></i>
                            Twitter
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($post->getTranslation('title', app()->getLocale())) }}" 
                           target="_blank"
                           class="inline-flex items-center bg-blue-700 hover:bg-blue-800 text-white px-6 py-3 rounded-lg transition">
                            <i class="fab fa-linkedin-in mr-2"></i>
                            LinkedIn
                        </a>
                    </div>
                </div>

                <!-- Related Posts -->
                @if($relatedPosts->count() > 0)
                <div class="mt-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">Articole Similare</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedPosts as $related)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                            @if($related->featured_image)
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ asset($related->featured_image) }}" 
                                     alt="{{ $related->getTranslation('title', app()->getLocale()) }}" 
                                     class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                            </div>
                            @endif
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                    <a href="/posts/{{ $related->slug }}" class="hover:text-blue-600">
                                        {{ $related->getTranslation('title', app()->getLocale()) }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ Str::limit($related->getTranslation('excerpt', app()->getLocale()), 100) }}
                                </p>
                                <a href="/posts/{{ $related->slug }}" 
                                   class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold text-sm">
                                    {{ __('messages.read_more') }}
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </article>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Back to Blog -->
                <div class="mt-12 text-center">
                    <a href="/blog" 
                       class="inline-flex items-center bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold px-8 py-4 rounded-lg transition shadow-md hover:shadow-xl">
                        <i class="fas fa-arrow-left mr-3"></i>
                        Înapoi la Blog
                    </a>
                </div>
            </div>
        </div>
    </article>

</body>
</html>
