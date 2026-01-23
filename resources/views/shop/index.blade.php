@extends('layouts.frontend')

@section('title', 'Shop - Carpathian CMS Demo')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 py-16">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Shop</h1>
        <p class="text-gray-300 text-lg max-w-2xl mx-auto">Browse our premium digital products and services</p>
    </div>
</section>

<!-- Products Grid -->
<section class="py-12">
    <div class="container mx-auto px-6">
        @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
            <div class="bg-slate-800 rounded-2xl overflow-hidden border border-slate-700 hover:border-purple-500/50 transition-all duration-300 group">
                <!-- Product Image -->
                <div class="h-48 bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center">
                    @if($product->images && count(json_decode($product->images, true)) > 0)
                        <img src="{{ asset(json_decode($product->images, true)[0]) }}" alt="{{ $product->getTranslation('name', 'en') }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-cube text-white text-5xl opacity-50 group-hover:scale-110 transition-transform"></i>
                    @endif
                </div>
                
                <!-- Product Info -->
                <div class="p-6">
                    @if($product->category)
                    <span class="text-purple-400 text-xs font-semibold uppercase tracking-wide">{{ $product->category->getTranslation('name', 'en') }}</span>
                    @endif
                    <h3 class="text-white font-bold text-lg mt-2 mb-2">{{ $product->getTranslation('name', 'en') }}</h3>
                    <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $product->getTranslation('description', 'en') }}</p>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-white">{{ $product->base_currency ?? '£' }}{{ number_format($product->price, 0) }}</span>
                        <a href="/shop/products/{{ $product->slug }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-semibold text-sm transition-colors">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-10">
            {{ $products->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <i class="fas fa-box-open text-gray-600 text-6xl mb-4"></i>
            <p class="text-gray-400 text-lg">No products available yet.</p>
        </div>
        @endif
    </div>
</section>
@endsection
