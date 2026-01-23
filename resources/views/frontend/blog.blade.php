@extends('layouts.frontend')

@section('title', 'Blog - Carpathian CMS Demo')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-br from-slate-900 via-green-900 to-slate-900 py-16">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Blog</h1>
        <p class="text-gray-300 text-lg max-w-2xl mx-auto">Latest articles, insights, and updates</p>
    </div>
</section>

<!-- Posts Grid -->
<section class="py-12">
    <div class="container mx-auto px-6">
        @php
            $posts = \App\Models\Post::where('status', 'published')->latest()->paginate(9);
        @endphp
        
        @if($posts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($posts as $post)
            <article class="bg-slate-800 rounded-2xl overflow-hidden border border-slate-700 hover:border-green-500/50 transition-all duration-300 group">
                <!-- Post Image -->
                <div class="h-48 bg-gradient-to-br from-green-600 to-teal-600 flex items-center justify-center">
                    @if($post->featured_image)
                        <img src="{{ asset($post->featured_image) }}" alt="{{ $post->getTranslation('title', 'en') }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-newspaper text-white text-5xl opacity-50 group-hover:scale-110 transition-transform"></i>
                    @endif
                </div>
                
                <!-- Post Info -->
                <div class="p-6">
                    @if($post->category)
                    <span class="text-green-400 text-xs font-semibold uppercase tracking-wide">{{ $post->category->getTranslation('name', 'en') }}</span>
                    @endif
                    <h3 class="text-white font-bold text-lg mt-2 mb-2 line-clamp-2">{{ $post->getTranslation('title', 'en') }}</h3>
                    <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ strip_tags($post->getTranslation('excerpt', 'en') ?: $post->getTranslation('content', 'en')) }}</p>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 text-sm">{{ $post->created_at->format('M d, Y') }}</span>
                        <a href="/posts/{{ $post->slug }}" class="text-green-400 hover:text-green-300 font-semibold text-sm flex items-center gap-1">
                            Read More <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-10">
            {{ $posts->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <i class="fas fa-newspaper text-gray-600 text-6xl mb-4"></i>
            <p class="text-gray-400 text-lg">No blog posts available yet.</p>
        </div>
        @endif
    </div>
</section>
@endsection
