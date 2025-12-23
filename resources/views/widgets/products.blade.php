@php
    $content = is_string($widget->content) ? json_decode($widget->content, true) : $widget->content;
    $limit = $content['limit'] ?? 6;
    $locale = app()->getLocale();
    
    // Get actual shop products with images
    $products = \App\Models\Product::where('is_active', true)
        ->where('stock', '>', 0)
        ->with('category')
        ->take($limit)
        ->get();
@endphp

<!-- Products Section Widget -->
<section class="py-10 bg-gradient-to-b from-gray-50 to-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('messages.products_showcase') }}</h2>
            <p class="text-sm text-gray-600">{{ __('messages.products_subtitle') }}</p>
        </div>
        
        @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <!-- Product Image -->
                        <div class="relative h-32 overflow-hidden group">
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
                                    <i class="fas fa-cube text-white text-2xl opacity-50"></i>
                                </div>
                            @endif
                            
                            <!-- Category Badge -->
                            @if($product->category)
                            <div class="absolute top-1.5 left-1.5">
                                <span class="bg-white/90 backdrop-blur-sm text-blue-600 text-[10px] font-semibold px-2 py-0.5 rounded-full shadow">
                                    {{ $product->category->getTranslation('name', app()->getLocale()) }}
                                </span>
                            </div>
                            @endif
                            
                            <!-- Sale Badge -->
                            @if($product->sale_price && $product->sale_price < $product->price)
                            <div class="absolute top-1.5 right-1.5">
                                @php
                                    $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                                @endphp
                                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow">
                                    -{{ $discount }}%
                                </span>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Product Info -->
                        <div class="p-3">
                            <h3 class="text-sm font-bold text-gray-900 mb-1 line-clamp-1">
                                {{ $product->getTranslation('name', $locale) }}
                            </h3>
                            
                            @if($product->getTranslation('description', $locale))
                            <p class="text-xs text-gray-600 mb-2 line-clamp-2">
                                {{ \Illuminate\Support\Str::limit(strip_tags($product->getTranslation('description', $locale)), 60) }}
                            </p>
                            @endif
                            
                            <!-- Price -->
                            <div class="mb-2">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <div class="flex items-center gap-1 flex-wrap">
                                        <span class="text-sm font-bold text-red-600">
                                            {{ number_format($product->sale_price, 0, ',', '.') }} RON
                                        </span>
                                        <span class="text-[10px] text-gray-400 line-through">
                                            {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-sm font-bold text-blue-600">
                                        {{ number_format($product->price, 0, ',', '.') }} RON
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Action Button -->
                            <a href="{{ route('shop.show', $product->slug) }}" 
                               class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-center py-1.5 px-2 rounded text-xs font-semibold transition-all duration-300 shadow-sm hover:shadow-md">
                                <i class="fas fa-eye mr-1"></i>
                                {{ __('messages.view_details') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-8">
                <a href="{{ route('shop.index') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    {{ __('messages.view_all_products') }}
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        @else
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-200 rounded-full mb-6">
                    <i class="fas fa-box-open text-4xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 text-lg">{{ __('messages.no_products_available') }}</p>
            </div>
        @endif
    </div>
</section>
