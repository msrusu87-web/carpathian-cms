@php
    $posts = \App\Models\Post::where('status', 'published')->latest()->take(3)->get();
@endphp

<!-- Blog Section - Clean Design -->
<section class="py-16 bg-slate-800/30">
    <div class="container mx-auto px-6">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <span class="inline-block bg-green-600/20 text-green-400 text-sm font-semibold px-4 py-1.5 rounded-full mb-4">Blog</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Latest Articles</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Stay updated with the latest insights and tips</p>
        </div>

        @if($posts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($posts as $post)
            <article class="bg-slate-800 rounded-2xl overflow-hidden border border-slate-700 hover:border-green-500/50 transition-all duration-300 group">
                <!-- Post Image -->
                <div class="h-48 bg-gradient-to-br from-green-600 to-teal-600 flex items-center justify-center">
                    <i class="fas fa-newspaper text-white text-5xl opacity-50 group-hover:scale-110 transition-transform"></i>
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

        <!-- View All Button -->
        <div class="text-center mt-10">
            <a href="/blog" class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-white px-6 py-3 rounded-xl font-semibold hover:bg-white/20 transition-all">
                View All Articles <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        @else
        <p class="text-center text-gray-400">No blog posts available yet.</p>
        @endif
    </div>
</section>
