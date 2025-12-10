<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->meta_title ?? $page->getTranslation('title', app()->getLocale()) }} - {{ config('app.name') }}</title>
    @if($page->meta_description)
    <meta name="description" content="{{ $page->meta_description }}">
    @endif
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
                        <strong>Keywords:</strong> {{ $page->meta_keywords }}
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
                    <p class="text-gray-400">Your partner in digital success.</p>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Pages</h4>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-400 hover:text-white">Home</a></li>
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
                    <h4 class="font-semibold mb-4">Services</h4>
                    <ul class="space-y-2">
                        <li><a href="/shop" class="text-gray-400 hover:text-white">Shop</a></li>
                        <li><a href="/blog" class="text-gray-400 hover:text-white">Blog</a></li>
                        <li><a href="/contact" class="text-gray-400 hover:text-white">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Admin</h4>
                    <ul class="space-y-2">
                        <li><a href="/admin" class="text-gray-400 hover:text-white">Dashboard</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
