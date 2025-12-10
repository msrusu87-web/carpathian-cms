@php
    $content = is_string($widget->content) ? json_decode($widget->content, true) : $widget->content;
    $heading = $content['heading'] ?? 'Products Showcase';
    $subheading = $content['subheading'] ?? 'Descoperă soluțiile noastre';
    $limit = $content['limit'] ?? 6;
    
    // Get actual shop products with images
    $products = \App\Models\Product::where('is_active', true)
        ->where('stock', '>', 0)
        ->with('category')
        ->take($limit)
        ->get();
@endphp

<!-- Products Section Widget -->
<section class="py-16 bg-gradient-to-b from-gray-50 to-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ $heading }}</h2>
            @if($subheading)
                <p class="text-xl text-gray-600">{{ $subheading }}</p>
            @endif
        </div>
        
        @if($products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($products as $product)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                        <!-- Product Image -->
                        <div class="relative h-56 overflow-hidden group">
                            @php
                                $productImages = $product->images ? json_decode($product->images, true) : [];
                                $firstImage = !empty($productImages) ? $productImages[0] : null;
                            @endphp
                            
                            @if($firstImage)
                                <img src="{{ asset($firstImage) }}" 
                                     alt="{{ $product->getTranslation('name', app()->getLocale()) }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 flex items-center justify-center">
                                    <i class="fas fa-cube text-white text-5xl opacity-50"></i>
                                </div>
                            @endif
                            
                            <!-- Category Badge -->
                            @if($product->category)
                            <div class="absolute top-3 left-3">
                                <span class="bg-white/90 backdrop-blur-sm text-blue-600 text-xs font-semibold px-3 py-1 rounded-full shadow">
                                    {{ $product->category->getTranslation('name', app()->getLocale()) }}
                                </span>
                            </div>
                            @endif
                            
                            <!-- Sale Badge -->
                            @if($product->sale_price && $product->sale_price < $product->price)
                            <div class="absolute top-3 right-3">
                                @php
                                    $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                                @endphp
                                <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow">
                                    -{{ $discount }}%
                                </span>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Product Info -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">
                                {{ $product->getTranslation('name', app()->getLocale()) }}
                            </h3>
                            
                            @if($product->getTranslation('description', app()->getLocale()))
                            <p class="text-gray-600 mb-4 text-sm line-clamp-2">
                                {{ Str::limit($product->getTranslation('description', app()->getLocale()), 80) }}
                            </p>
                            @endif
                            
                            <!-- Price -->
                            <div class="mb-4">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <div class="flex items-center gap-2">
                                        <span class="text-2xl font-bold text-red-600">
                                            {{ number_format($product->sale_price, 0, ',', '.') }} RON
                                        </span>
                                        <span class="text-sm text-gray-400 line-through">
                                            {{ number_format($product->price, 0, ',', '.') }} RON
                                        </span>
                                    </div>
                                @else
                                    <span class="text-2xl font-bold text-blue-600">
                                        {{ number_format($product->price, 0, ',', '.') }} RON
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Action Button -->
                            <a href="{{ route('shop.show', $product->slug) }}" 
                               class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-center py-3 px-6 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-xl">
                                <i class="fas fa-eye mr-2"></i>
                                Vezi Detalii
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('shop.index') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-shopping-bag mr-3"></i>
                    Vezi Toate Produsele
                    <i class="fas fa-arrow-right ml-3"></i>
                </a>
            </div>
        @else
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-200 rounded-full mb-6">
                    <i class="fas fa-box-open text-4xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 text-lg">Nu există produse disponibile încă.</p>
            </div>
        @endif
    </div>
</section>
