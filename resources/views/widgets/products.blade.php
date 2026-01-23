@php
    $products = \App\Models\Product::where('is_active', true)->where('stock', '>', 0)->with('category')->take(6)->get();
@endphp

<!-- Products Section - Clean Design -->
<section class="py-16 bg-slate-800/50">
    <div class="container mx-auto px-6">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <span class="inline-block bg-purple-600/20 text-purple-400 text-sm font-semibold px-4 py-1.5 rounded-full mb-4">Our Products</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Premium Solutions</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Discover our carefully crafted digital products and services</p>
        </div>

        @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
            <div class="bg-slate-800 rounded-2xl overflow-hidden border border-slate-700 hover:border-purple-500/50 transition-all duration-300 group">
                <!-- Product Image -->
                <div class="h-48 bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center">
                    <i class="fas fa-cube text-white text-5xl opacity-50 group-hover:scale-110 transition-transform"></i>
                </div>
                
                <!-- Product Info -->
                <div class="p-6">
                    @if($product->category)
                    <span class="text-purple-400 text-xs font-semibold uppercase tracking-wide">{{ $product->category->getTranslation('name', 'en') }}</span>
                    @endif
                    <h3 class="text-white font-bold text-lg mt-2 mb-2">{{ $product->getTranslation('name', 'en') }}</h3>
                    <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $product->getTranslation('description', 'en') }}</p>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-white">£{{ number_format($product->price, 0) }}</span>
                        <a href="/shop/products/{{ $product->slug }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-semibold text-sm transition-colors">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- View All Button -->
        <div class="text-center mt-10">
            <a href="/shop" class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-white px-6 py-3 rounded-xl font-semibold hover:bg-white/20 transition-all">
                View All Products <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        @else
        <p class="text-center text-gray-400">No products available yet.</p>
        @endif
    </div>
</section>
