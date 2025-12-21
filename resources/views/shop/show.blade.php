<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @php
        $productName = $product->getTranslation('name', app()->getLocale());
        $productDesc = $product->getTranslation('description', app()->getLocale()) ?? $productName;
        $productImage = $product->image ? asset('storage/' . $product->image) : asset('images/carpathian-og-image.jpg');
        $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
        $images = $images ?? [];
    @endphp
    
    @include('partials.seo-head', [
        'title' => $productName . ' - Carphatian CMS',
        'description' => Str::limit(strip_tags($productDesc), 160),
        'keywords' => 'servicii web, ' . $productName . ', dezvoltare web, Carphatian CMS',
        'type' => 'product',
        'image' => $productImage,
        'breadcrumbs' => [
            ['name' => __('messages.home'), 'url' => url('/')],
            ['name' => __('messages.shop'), 'url' => url('/shop')],
            ['name' => $productName, 'url' => url()->current()]
        ]
    ])
    
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "{{ $productName }}",
        "description": "{{ Str::limit(strip_tags($productDesc), 200) }}",
        "image": "{{ $productImage }}",
        "url": "{{ url()->current() }}",
        "brand": { "@type": "Brand", "name": "Carphatian CMS" },
        "offers": {
            "@type": "Offer",
            "price": "{{ $product->getPriceInCurrency() }}",
            "priceCurrency": "{{ app(\App\Services\CurrencyService::class)->getCurrentCurrency() }}",
            "availability": "https://schema.org/InStock",
            "seller": { "@type": "Organization", "name": "Aziz Ride Sharing S.R.L." }
        }
    }
    </script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .thumbnail-active { border: 3px solid #2563eb; transform: scale(1.05); }
        .product-image { transition: transform 0.3s ease; }
        .product-image:hover { transform: scale(1.02); }
    </style>
</head>
<body class="bg-gray-50">
    @include('partials.navigation')

    <!-- Breadcrumb & Currency -->
    <div class="bg-white border-b shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <nav class="flex text-sm text-gray-600">
                    <a href="{{ route('home') }}" class="hover:text-blue-600"><i class="fas fa-home mr-1"></i>{{ __('messages.home') }}</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('shop.index') }}" class="hover:text-blue-600">{{ __('messages.shop') }}</a>
                    @if($product->category)
                        <span class="mx-2">/</span>
                        <a href="{{ route('shop.category', $product->category->slug) }}" class="hover:text-blue-600">
                            {{ $product->category->getTranslation('name', app()->getLocale()) }}
                        </a>
                    @endif
                    <span class="mx-2">/</span>
                    <span class="text-gray-900 font-semibold">{{ Str::limit($productName, 40) }}</span>
                </nav>
                
                <!-- Currency Switcher -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 transition text-sm">
                        @php
                            $currencyService = app(\App\Services\CurrencyService::class);
                            $currentCurrency = $currencyService->getCurrentCurrency();
                            $currencies = $currencyService->getCurrencies();
                        @endphp
                        <i class="fas fa-coins text-gray-600"></i>
                        <span class="font-semibold">{{ $currentCurrency }}</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <div x-show="open" @click.away="open = false"
                         x-transition
                         class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50"
                         style="display: none;">
                        @foreach($currencies as $code => $currency)
                            <form action="{{ route('currency.switch') }}" method="POST">
                                @csrf
                                <input type="hidden" name="currency" value="{{ $code }}">
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-blue-50 transition flex items-center gap-3 {{ $currentCurrency === $code ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700' }}">
                                    <span class="text-lg w-8">{{ $currency['symbol'] }}</span>
                                    <span class="flex-1">{{ $code }} - {{ $currency['name'] }}</span>
                                    @if($currentCurrency === $code)
                                        <i class="fas fa-check text-blue-600"></i>
                                    @endif
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Section - BUSINESS STYLE -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid lg:grid-cols-5 gap-8" x-data="{ selectedImage: 0, quantity: 1 }">
            
            <!-- LEFT: Photo Gallery (3 columns) -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Main Image -->
                    <div class="relative bg-gray-50 border-b">
                        @if(count($images) > 0)
                            <template x-for="(image, index) in {{ json_encode($images) }}" :key="index">
                                <img x-show="selectedImage === index" 
                                     :src="image.startsWith('http') ? image : `{{ asset('') }}${image}`"
                                     alt="{{ $productName }}"
                                     class="w-full h-[500px] object-contain product-image">
                            </template>
                        @else
                            <div class="w-full h-[500px] bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-8xl"></i>
                            </div>
                        @endif
                        
                        <!-- Badges -->
                        @if($product->is_featured || $product->sale_price)
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                @if($product->is_featured)
                                    <span class="bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded shadow-lg">
                                        <i class="fas fa-star mr-1"></i>FEATURED
                                    </span>
                                @endif
                                @if($product->sale_price)
                                    <span class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded shadow-lg">
                                        -{{ $product->getDiscountPercent() }}% SALE
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnails Gallery -->
                    @if(count($images) > 1)
                        <div class="p-4 bg-white">
                            <div class="grid grid-cols-6 gap-3">
                                @foreach($images as $index => $image)
                                    <div @click="selectedImage = {{ $index }}" 
                                         :class="selectedImage === {{ $index }} ? 'thumbnail-active' : 'border-2 border-gray-200'"
                                         class="cursor-pointer rounded overflow-hidden hover:border-blue-400 transition aspect-square">
                                        <img src="{{ strpos($image, 'http') === 0 ? $image : asset($image) }}" 
                                             alt="Thumbnail {{ $index + 1 }}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Short Description BELOW Photos -->
                <div class="mt-6 bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-3 border-b pb-2">{{ __('messages.description') }}</h3>
                    <div class="text-gray-700 leading-relaxed prose prose-sm max-w-none">
                        {!! $product->getTranslation('description', app()->getLocale()) !!}
                    </div>
                </div>
            </div>

            <!-- RIGHT: Product Info (2 columns) - COMPACT -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    
                    <!-- Category -->
                    @if($product->category)
                        <a href="{{ route('shop.category', $product->category->slug) }}" 
                           class="inline-block bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full mb-3 hover:bg-blue-200 transition">
                            {{ $product->category->getTranslation('name', app()->getLocale()) }}
                        </a>
                    @endif

                    <!-- Title -->
                    <h1 class="text-2xl font-bold text-gray-900 mb-3 leading-tight">
                        {{ $productName }}
                    </h1>

                    <!-- Rating & Stock -->
                    <div class="flex items-center gap-4 mb-4 text-sm">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-yellow-400"></i>
                            @endfor
                            <span class="ml-2 text-gray-600">5.0 (127)</span>
                        </div>
                        <span class="text-gray-300">|</span>
                        <span class="text-green-600 font-semibold">
                            <i class="fas fa-check-circle mr-1"></i>{{ __('messages.in_stock') }}
                        </span>
                    </div>

                    <!-- Price -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
                        @if($product->getSalePriceInCurrency())
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-lg text-gray-400 line-through">{{ $product->getFormattedPrice() }}</span>
                                <span class="bg-red-600 text-white text-xs px-2 py-0.5 rounded font-bold">
                                    SAVE {{ $product->getDiscountPercent() }}%
                                </span>
                            </div>
                            <div class="text-3xl font-black text-green-600">
                                {{ $product->getFormattedSalePrice() }}
                            </div>
                        @else
                            <div class="text-3xl font-black text-gray-900">
                                {{ $product->getFormattedPrice() }}
                            </div>
                        @endif
                        <p class="text-xs text-gray-500 mt-1">{{ __('messages.prices_include_vat') }}</p>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.quantity') }}</label>
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden w-32">
                            <button @click="quantity = Math.max(1, quantity - 1)" 
                                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 font-bold">
                                <i class="fas fa-minus text-sm"></i>
                            </button>
                            <input type="number" x-model="quantity" min="1" 
                                   class="w-16 text-center border-0 focus:ring-0 font-semibold">
                            <button @click="quantity++" 
                                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 font-bold">
                                <i class="fas fa-plus text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Add to Cart -->
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-4">
                        @csrf
                        <input type="hidden" name="quantity" x-model="quantity">
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-bold text-lg shadow-lg hover:shadow-xl transition-all">
                            <i class="fas fa-shopping-cart mr-2"></i>{{ __('messages.add_to_cart') }}
                        </button>
                    </form>

                    <!-- Pre-Sale Inquiry Button -->
                    <a href="{{ route('pre-sale.form', $product->id) }}" class="block w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3 rounded-lg hover:from-orange-600 hover:to-orange-700 font-semibold text-center shadow-lg hover:shadow-xl transition-all mb-4">
                        <i class="fas fa-question-circle mr-2"></i>{{ __('messages.pre_sale_button') }}
                    </a>

                    <!-- Quick Info -->
                    <div class="border-t pt-4 space-y-3 text-sm">
                        <div class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-truck text-blue-600 w-5"></i>
                            <span>{{ __('messages.fast_delivery') }}: 2-3 {{ __('messages.business_days') }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-shield-alt text-green-600 w-5"></i>
                            <span>{{ __('messages.warranty') }}: 24 {{ __('messages.months') }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-undo text-orange-600 w-5"></i>
                            <span>{{ __('messages.return_policy') }}: 30 {{ __('messages.days') }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-headset text-purple-600 w-5"></i>
                            <span>{{ __('messages.support') }}: 24/7</span>
                        </div>
                    </div>

                    @if($product->sku)
                        <div class="mt-4 pt-4 border-t text-xs text-gray-500">
                            SKU: <span class="font-mono">{{ $product->sku }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Tabs - COMPACT BUSINESS STYLE -->
        <div class="mt-8 bg-white rounded-lg shadow-md" x-data="{ activeTab: 'specifications' }">
            <!-- Tab Navigation -->
            <div class="flex border-b">
                <button @click="activeTab = 'specifications'" 
                        :class="activeTab === 'specifications' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'"
                        class="px-6 py-3 font-semibold text-sm hover:text-blue-600 transition">
                    <i class="fas fa-list mr-2"></i>{{ __('messages.specifications') }}
                </button>
                <button @click="activeTab = 'features'" 
                        :class="activeTab === 'features' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'"
                        class="px-6 py-3 font-semibold text-sm hover:text-blue-600 transition">
                    <i class="fas fa-star mr-2"></i>{{ __('messages.features') }}
                </button>
                <button @click="activeTab = 'reviews'" 
                        :class="activeTab === 'reviews' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'"
                        class="px-6 py-3 font-semibold text-sm hover:text-blue-600 transition">
                    <i class="fas fa-comments mr-2"></i>{{ __('messages.reviews') }} (127)
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Specifications -->
                <div x-show="activeTab === 'specifications'">
                    @if($product->attributes && count($product->attributes) > 0)
                        <div class="grid md:grid-cols-2 gap-6">
                            <table class="w-full text-sm">
                                <tbody class="divide-y">
                                    @foreach($product->attributes as $key => $value)
                                        @if(is_string($value))
                                            <tr class="hover:bg-gray-50">
                                                <td class="py-3 font-semibold text-gray-700 w-1/2">{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                <td class="py-3 text-gray-900">{{ $value }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="bg-blue-50 rounded-lg p-6">
                                <h4 class="font-bold text-gray-900 mb-4">{{ __('messages.whats_included') }}</h4>
                                <ul class="space-y-2 text-sm text-gray-700">
                                    <li><i class="fas fa-check text-green-600 mr-2"></i>{{ __('messages.product_itself') }}</li>
                                    <li><i class="fas fa-check text-green-600 mr-2"></i>{{ __('messages.documentation') }}</li>
                                    <li><i class="fas fa-check text-green-600 mr-2"></i>{{ __('messages.technical_support') }}</li>
                                    <li><i class="fas fa-check text-green-600 mr-2"></i>{{ __('messages.warranty') }}</li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">{{ __('messages.no_specifications_available') }}</p>
                    @endif
                </div>

                <!-- Features -->
                <div x-show="activeTab === 'features'">
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <i class="fas fa-rocket text-blue-600 text-2xl mb-2"></i>
                            <h4 class="font-bold text-sm mb-1">Fast Performance</h4>
                            <p class="text-xs text-gray-600">Optimized for speed</p>
                        </div>
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <i class="fas fa-shield-alt text-green-600 text-2xl mb-2"></i>
                            <h4 class="font-bold text-sm mb-1">Secure & Reliable</h4>
                            <p class="text-xs text-gray-600">Enterprise security</p>
                        </div>
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <i class="fas fa-sync text-purple-600 text-2xl mb-2"></i>
                            <h4 class="font-bold text-sm mb-1">Free Updates</h4>
                            <p class="text-xs text-gray-600">Lifetime updates</p>
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                <div x-show="activeTab === 'reviews'">
                    <div class="flex items-center gap-6 mb-6">
                        <div class="text-center">
                            <div class="text-4xl font-black text-yellow-500">5.0</div>
                            <div class="flex justify-center my-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-yellow-400"></i>
                                @endfor
                            </div>
                            <p class="text-xs text-gray-500">127 {{ __('messages.reviews') }}</p>
                        </div>
                        <div class="flex-1 text-sm space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="w-12">5★</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-400 h-2 rounded-full" style="width: 95%"></div>
                                </div>
                                <span class="w-8 text-right">120</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-12">4★</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-400 h-2 rounded-full" style="width: 4%"></div>
                                </div>
                                <span class="w-8 text-right">5</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-12">3★</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-400 h-2 rounded-full" style="width: 1%"></div>
                                </div>
                                <span class="w-8 text-right">2</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @php
        $footerWidget = \App\Models\Widget::where('type', 'footer')->where('status', 'active')->first();
    @endphp
    @if($footerWidget)
        @include('widgets.footer', ['widget' => $footerWidget])
    @endif
</body>
</html>
