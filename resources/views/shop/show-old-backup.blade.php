<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @php
        $productName = $product->getTranslation('name', app()->getLocale());
        $productDesc = $product->getTranslation('description', app()->getLocale()) ?? $productName;
        $productImage = $product->image ? asset('storage/' . $product->image) : asset('images/carpathian-og-image.jpg');
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
    
    {{-- Product Structured Data --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "{{ $productName }}",
        "description": "{{ Str::limit(strip_tags($productDesc), 200) }}",
        "image": "{{ $productImage }}",
        "url": "{{ url()->current() }}",
        "brand": {
            "@type": "Brand",
            "name": "Carphatian CMS"
        },
        "offers": {
            "@type": "Offer",
            "price": "{{ $product->price }}",
            "priceCurrency": "RON",
            "availability": "https://schema.org/InStock",
            "seller": {
                "@type": "Organization",
                "name": "Aziz Ride Sharing S.R.L."
            }
        }
    }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .product-image-zoom { transition: transform 0.5s ease; }
        .product-image-zoom:hover { transform: scale(1.05); }
        .rating-star { transition: all 0.2s ease; }
        .rating-star:hover { transform: scale(1.2); color: #fbbf24; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        @keyframes slideInFromLeft { from { opacity: 0; transform: translateX(-50px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes slideInFromRight { from { opacity: 0; transform: translateX(50px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate-slide-in-left { animation: slideInFromLeft 0.6s ease-out; }
        .animate-slide-in-right { animation: slideInFromRight 0.6s ease-out; }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out; }
        .pulse-badge { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    </style>
</head>
<body class="bg-gray-50">
    @include('partials.navigation')

    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
            <nav class="flex text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-blue-600"><i class="fas fa-home mr-1"></i>{{ __('messages.home') }}</a>
                <span class="mx-2">/</span>
                <a href="{{ route('shop.index') }}" class="hover:text-blue-600">{{ __('messages.shop') }}</a>
                @if($product->category)
                    <span class="mx-2">/</span>
                    <a href="{{ route('shop.category', $product->category->slug) }}" class="hover:text-blue-600">{{ $product->category->getTranslation('name', app()->getLocale()) }}</a>
                @endif
                <span class="text-gray-900 font-semibold">{{ $product->getTranslation('name', app()->getLocale()) }}</span>
            </nav>
            @include('components.currency-switcher')
            </div>
        </div>
    </div>

    <main class="container mx-auto px-4 py-12">
        <!-- Product Main Section -->
        <div class="grid lg:grid-cols-2 gap-12 mb-16" x-data="{ selectedImage: 0, quantity: 1, addedToCart: false }">
            <!-- Image Gallery - Left -->
            <div class="animate-slide-in-left">
                <!-- Main Image -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-4 relative group">
                    @php
                        $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                        $images = $images ?? [];
                    @endphp
                    
                    @if(count($images) > 0)
                        <template x-for="(image, index) in {{ json_encode($images) }}" :key="index">
                            <img x-show="selectedImage === index" 
                                 :src="image.startsWith('http') ? image : `{{ asset('') }}${image}`" 
                                 alt="{{ $product->getTranslation('name', app()->getLocale()) }}"
                                 class="w-full h-[500px] object-cover product-image-zoom">
                        </template>
                    @else
                        <div class="bg-gradient-to-br from-blue-500 via-purple-600 to-pink-500 h-[500px] flex items-center justify-center">
                            <i class="fas fa-image text-white text-9xl opacity-50"></i>
                        </div>
                    @endif
                    
                    <!-- Badges -->
                    <div class="absolute top-4 left-4 space-y-2">
                        @if($product->is_featured)
                            <span class="block bg-yellow-400 text-yellow-900 text-sm font-bold px-4 py-2 rounded-full shadow-lg pulse-badge">
                                <i class="fas fa-star mr-1"></i>{{ __('messages.featured') }}
                            </span>
                        @endif
                        @if($product->sale_price)
                            <span class="block bg-red-500 text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg">
                                -{{ $product->getDiscountPercent() }}% OFF
                            </span>
                        @endif
                    </div>
                    
                    <!-- Share Button -->
                    <div class="absolute top-4 right-4">
                        <button class="bg-white text-gray-700 p-3 rounded-full shadow-lg hover:bg-gray-100 transition" @click="alert('Share functionality')">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </div>
                </div>

                <!-- Thumbnail Gallery -->
                @if(count($images) > 1)
                    <div class="grid grid-cols-4 gap-4">
                        @foreach($images as $index => $image)
                            <div @click="selectedImage = {{ $index }}" 
                                 :class="selectedImage === {{ $index }} ? 'ring-4 ring-blue-500' : 'ring-1 ring-gray-200'"
                                 class="cursor-pointer rounded-lg overflow-hidden hover:ring-4 hover:ring-blue-300 transition">
                                <img src="{{ strpos($image, 'http') === 0 ? $image : asset($image) }}" alt="Thumbnail {{ $index + 1 }}" class="w-full h-24 object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info - Right -->
            <div class="animate-slide-in-right">
                <!-- Category Badge -->
                @if($product->category)
                    <a href="{{ route('shop.category', $product->category->slug) }}" class="inline-block bg-blue-100 text-blue-700 text-sm font-semibold px-4 py-2 rounded-full mb-4 hover:bg-blue-200 transition">
                        <i class="fas fa-tag mr-1"></i>{{ $product->category->getTranslation('name', app()->getLocale()) }}
                    </a>

                @endif
                <!-- Title -->
                <h1 class="text-5xl font-black mb-4 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    {{ $product->getTranslation('name', app()->getLocale()) }}
                </h1>

                <!-- Rating & Reviews -->
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-yellow-400 text-xl rating-star"></i>
                        @endfor
                        <span class="ml-2 text-gray-700 font-semibold">5.0</span>
                    </div>
                    <span class="text-gray-500">|</span>
                    <a href="#reviews" class="text-blue-600 hover:underline font-semibold">
                        <i class="far fa-comment mr-1"></i>127 recenzii
                    </a>
                    <span class="text-gray-500">|</span>
                    <span class="text-green-600 font-semibold">
                        <i class="fas fa-check-circle mr-1"></i>{{ __('messages.in_stock') }}
                    </span>
                </div>

                <!-- Product Description -->
                <div class="text-xl text-gray-700 mb-8 leading-relaxed prose prose-lg max-w-none">
                    {!! $product->getTranslation('description', app()->getLocale()) !!}
                </div>

                <!-- Price -->
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-8 mb-8 border-2 border-blue-200">
                    @if($product->getSalePriceInCurrency())
                        <div class="flex items-center gap-6 mb-2">
                            <span class="text-3xl text-gray-400 line-through font-semibold">{{ $product->getFormattedPrice() }}</span>
                            <span class="bg-red-500 text-white text-lg px-4 py-2 rounded-full font-bold">
                                -{{ $product->getDiscountPercent() }}% OFF
                            </span>
                        </div>
                        <div class="text-6xl font-black text-green-600">
                            {{ $product->getFormattedSalePrice() }}
                        </div>
                    @else
                        <div class="text-6xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            {{ $product->getFormattedPrice() }}
                        </div>
                    @endif
                    <p class="text-sm text-gray-600 mt-2"><i class="fas fa-info-circle mr-1"></i>{{ __('messages.prices_include_vat') }}</p>
                </div>

                <!-- Quick Features -->
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-xl transition">
                        <i class="fas fa-rocket text-blue-600 text-2xl mb-2"></i>
                        <h4 class="font-bold text-gray-900">Livrare Rapidă</h4>
                        <p class="text-sm text-gray-600">2-3 zile lucrătoare</p>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-xl transition">
                        <i class="fas fa-shield-alt text-green-600 text-2xl mb-2"></i>
                        <h4 class="font-bold text-gray-900">Garanție 24 luni</h4>
                        <p class="text-sm text-gray-600">Suport tehnic inclus</p>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-xl transition">
                        <i class="fas fa-sync text-purple-600 text-2xl mb-2"></i>
                        <h4 class="font-bold text-gray-900">Actualizări Gratuite</h4>
                        <p class="text-sm text-gray-600">Pe viață</p>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-xl transition">
                        <i class="fas fa-headset text-orange-600 text-2xl mb-2"></i>
                        <h4 class="font-bold text-gray-900">Suport 24/7</h4>
                        <p class="text-sm text-gray-600">Echipă dedicată</p>
                    </div>
                </div>

                <!-- Quantity & Add to Cart -->
                <div class="flex gap-4 mb-6">
                    <div class="flex items-center border-2 border-gray-300 rounded-xl overflow-hidden">
                        <button @click="quantity = Math.max(1, quantity - 1)" class="px-6 py-4 bg-gray-100 hover:bg-gray-200 font-bold text-xl">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" x-model="quantity" min="1" class="w-20 text-center text-xl font-bold border-0 focus:ring-0">
                        <button @click="quantity++" class="px-6 py-4 bg-gray-100 hover:bg-gray-200 font-bold text-xl">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1" x-data="{ submitting: false }">
                        @csrf
                        <input type="hidden" name="quantity" x-model="quantity">
                        <button type="submit" 
                            @click="submitting = true; setTimeout(() => submitting = false, 3000)"
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-4 rounded-xl hover:from-green-600 hover:to-green-700 text-xl font-bold shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            <span x-text="submitting ? 'Adăugat în Coș! ✓' : '{{ __('messages.add_to_cart') }}'"></span>
                        </button>
                    </form>
                </div>

                <!-- Buy Now & Contact -->
                <div class="flex gap-4">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="quantity" x-model="quantity">
                        <input type="hidden" name="buy_now" value="1">
                        <button type="submit" class="w-full text-center bg-gradient-to-r from-blue-500 to-blue-600 text-white px-8 py-4 rounded-xl hover:from-blue-600 hover:to-blue-700 text-lg font-bold shadow-lg hover:shadow-xl transition">
                            <i class="fas fa-bolt mr-2"></i>Cumpără Acum
                        </button>
                    </form>
                    <button class="px-6 py-4 border-2 border-gray-300 rounded-xl hover:border-red-500 hover:text-red-500 transition">
                        <i class="far fa-heart text-2xl"></i>
                    </button>
                </div>

                <!-- Trust Badges -->
                <div class="flex items-center justify-around mt-8 pt-8 border-t">
                    <div class="text-center">
                        <i class="fas fa-lock text-green-600 text-3xl mb-2"></i>
                        <p class="text-xs text-gray-600 font-semibold">Plată Securizată</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-undo text-blue-600 text-3xl mb-2"></i>
                        <p class="text-xs text-gray-600 font-semibold">Returnare 30 zile</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-certificate text-purple-600 text-3xl mb-2"></i>
                        <p class="text-xs text-gray-600 font-semibold">Produs Certificat</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-truck text-orange-600 text-3xl mb-2"></i>
                        <p class="text-xs text-gray-600 font-semibold">Transport Gratuit</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-16 animate-fade-in-up" x-data="{ activeTab: 'description' }">
            <!-- Tab Navigation -->
            <div class="flex border-b bg-gray-50">
                <button @click="activeTab = 'description'" 
                        :class="activeTab === 'description' ? 'bg-white border-b-4 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-blue-600'"
                        class="flex-1 px-8 py-6 font-bold text-lg transition">
                    <i class="fas fa-align-left mr-2"></i>{{ __('messages.description') }}
                </button>
                <button @click="activeTab = 'specifications'" 
                        :class="activeTab === 'specifications' ? 'bg-white border-b-4 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-blue-600'"
                        class="flex-1 px-8 py-6 font-bold text-lg transition">
                    <i class="fas fa-list-ul mr-2"></i>{{ __('messages.specifications') }}
                </button>
                <button @click="activeTab = 'features'" 
                        :class="activeTab === 'features' ? 'bg-white border-b-4 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-blue-600'"
                        class="flex-1 px-8 py-6 font-bold text-lg transition">
                    <i class="fas fa-star mr-2"></i>{{ __('messages.features') }}
                </button>
                <button @click="activeTab = 'reviews'" 
                        :class="activeTab === 'reviews' ? 'bg-white border-b-4 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-blue-600'"
                        class="flex-1 px-8 py-6 font-bold text-lg transition">
                    <i class="fas fa-comments mr-2"></i>Recenzii (127)
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-12">
                <!-- Description Tab -->
                <div x-show="activeTab === 'description'" class="prose prose-lg max-w-none">
                    <h2 class="text-3xl font-bold mb-6 text-gray-900">{{ __('messages.detailed_description') }}</h2>
                    @if($product->getTranslation('content', app()->getLocale()))
                        {!! $product->getTranslation('content', app()->getLocale()) !!}
                    @else
                        {!! $product->getTranslation('description', app()->getLocale()) !!}
                    @endif
                </div>

                <!-- Specifications Tab -->
                <div x-show="activeTab === 'specifications'">
                    <h2 class="text-3xl font-bold mb-8 text-gray-900">{{ __('messages.technical_specifications') }}</h2>
                    
                    @if($product->attributes && count($product->attributes) > 0)
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                                    <h3 class="text-xl font-bold text-white"><i class="fas fa-info-circle mr-2"></i>{{ __('messages.product_details') }}</h3>
                                </div>
                                <table class="w-full">
                                    <tbody class="divide-y">
                                        @foreach($product->attributes as $key => $value)
                                            @if(is_string($value))
                                                <tr class="hover:bg-gray-50">
                                                    <td class="py-4 px-6 font-semibold text-gray-700">{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                    <td class="py-4 px-6 text-gray-900">{{ $value }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        @if($product->sku)
                                            <tr class="hover:bg-gray-50">
                                                <td class="py-4 px-6 font-semibold text-gray-700">SKU</td>
                                                <td class="py-4 px-6 text-gray-900 font-mono">{{ $product->sku }}</td>
                                            </tr>
                                        @endif
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-4 px-6 font-semibold text-gray-700">{{ __('messages.stock') }}</td>
                                            <td class="py-4 px-6">
                                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold">
                                                    {{ $product->stock }} {{ __('messages.available') }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl p-8 border-2 border-blue-200">
                                <h3 class="text-xl font-bold mb-6 text-gray-900"><i class="fas fa-box mr-2 text-blue-600"></i>{{ __('messages.whats_included') }}</h3>
                                <ul class="space-y-4">
                                    <li class="flex items-start gap-3">
                                        <i class="fas fa-check-circle text-green-600 text-xl mt-1"></i>
                                        <span class="text-gray-700">{{ __('messages.product_itself') }}</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <i class="fas fa-check-circle text-green-600 text-xl mt-1"></i>
                                        <span class="text-gray-700">{{ __('messages.documentation') }}</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <i class="fas fa-check-circle text-green-600 text-xl mt-1"></i>
                                        <span class="text-gray-700">{{ __('messages.technical_support') }}</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <i class="fas fa-check-circle text-green-600 text-xl mt-1"></i>
                                        <span class="text-gray-700">{{ __('messages.warranty') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500">
                            <i class="fas fa-inbox text-6xl mb-4 opacity-50"></i>
                            <p class="text-xl">{{ __('messages.no_specifications_available') }}</p>
                        </div>
                    @endif
                </div>

                <!-- Features Tab -->
                <div x-show="activeTab === 'features'">
                    <h2 class="text-3xl font-bold mb-8 text-gray-900">Caracteristici Principale</h2>
                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 hover:shadow-xl transition">
                            <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl mb-4">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Analytics Avansat</h3>
                            <p class="text-gray-700">Dashboard complet cu statistici în timp real, rapoarte personalizabile și integrare Google Analytics.</p>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 hover:shadow-xl transition">
                            <div class="bg-green-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl mb-4">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Multi-User Management</h3>
                            <p class="text-gray-700">Sistem avansat de roluri și permisiuni pentru echipe de orice dimensiune.</p>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 hover:shadow-xl transition">
                            <div class="bg-purple-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl mb-4">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Mobile First Design</h3>
                            <p class="text-gray-700">Design responsive optimizat pentru toate dispozitivele mobile și tablete.</p>
                        </div>
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 hover:shadow-xl transition">
                            <div class="bg-orange-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl mb-4">
                                <i class="fas fa-search"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3">SEO Optimizat</h3>
                            <p class="text-gray-700">Meta tags dinamice, sitemap XML, rich snippets și optimizare pentru motoarele de căutare.</p>
                        </div>
                        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 hover:shadow-xl transition">
                            <div class="bg-red-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl mb-4">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Securitate Enterprise</h3>
                            <p class="text-gray-700">SSL, WAF, backup automat zilnic, autentificare 2FA și protecție DDoS.</p>
                        </div>
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-6 hover:shadow-xl transition">
                            <div class="bg-indigo-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl mb-4">
                                <i class="fas fa-plug"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3">API & Integrări</h3>
                            <p class="text-gray-700">REST API documentat, webhooks, integrare CRM, email marketing și multe altele.</p>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div x-show="activeTab === 'reviews'" id="reviews">
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold mb-4 text-gray-900">Recenzii Clienți</h2>
                        <div class="flex items-center gap-8 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-8">
                            <div class="text-center">
                                <div class="text-6xl font-black text-yellow-500 mb-2">5.0</div>
                                <div class="flex justify-center mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star text-yellow-400 text-2xl"></i>
                                    @endfor
                                </div>
                                <p class="text-gray-600">Bazat pe 127 recenzii</p>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-2">
                                    <span class="w-16 text-sm font-semibold">5 stele</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-3">
                                        <div class="bg-yellow-400 h-3 rounded-full" style="width: 95%"></div>
                                    </div>
                                    <span class="w-16 text-right text-sm font-semibold">120</span>
                                </div>
                                <div class="flex items-center gap-4 mb-2">
                                    <span class="w-16 text-sm font-semibold">4 stele</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-3">
                                        <div class="bg-yellow-400 h-3 rounded-full" style="width: 4%"></div>
                                    </div>
                                    <span class="w-16 text-right text-sm font-semibold">5</span>
                                </div>
                                <div class="flex items-center gap-4 mb-2">
                                    <span class="w-16 text-sm font-semibold">3 stele</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-3">
                                        <div class="bg-yellow-400 h-3 rounded-full" style="width: 1%"></div>
                                    </div>
                                    <span class="w-16 text-right text-sm font-semibold">2</span>
                                </div>
                                <div class="flex items-center gap-4 mb-2">
                                    <span class="w-16 text-sm font-semibold">2 stele</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-3">
                                        <div class="bg-gray-300 h-3 rounded-full" style="width: 0%"></div>
                                    </div>
                                    <span class="w-16 text-right text-sm font-semibold">0</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="w-16 text-sm font-semibold">1 stea</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-3">
                                        <div class="bg-gray-300 h-3 rounded-full" style="width: 0%"></div>
                                    </div>
                                    <span class="w-16 text-right text-sm font-semibold">0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Individual Reviews -->
                    <div class="space-y-6">
                        <div class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:border-blue-300 transition">
                            <div class="flex items-start gap-4 mb-4">
                                <div class="bg-gradient-to-br from-blue-500 to-purple-600 text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl">
                                    AM
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-bold text-lg">Andrei Marinescu</h4>
                                        <span class="text-sm text-gray-500">3 zile în urmă</span>
                                    </div>
                                    <div class="flex mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star text-yellow-400"></i>
                                        @endfor
                                    </div>
                                    <p class="text-gray-700 leading-relaxed mb-3">Soluție excepțională pentru portal-ul companiei noastre! Implementarea a fost rapidă, echipa foarte profesionistă. Am reușit să centralizăm toate departamentele într-o singură platformă. Recomand cu căldură!</p>
                                    <div class="flex gap-2">
                                        <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">Cumpărător Verificat</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full">Recomandă</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:border-blue-300 transition">
                            <div class="flex items-start gap-4 mb-4">
                                <div class="bg-gradient-to-br from-green-500 to-teal-600 text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl">
                                    MV
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-bold text-lg">Maria Vasilescu</h4>
                                        <span class="text-sm text-gray-500">1 săptămână în urmă</span>
                                    </div>
                                    <div class="flex mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star text-yellow-400"></i>
                                        @endfor
                                    </div>
                                    <p class="text-gray-700 leading-relaxed mb-3">Platformă foarte bine gândită, intuitivă și ușor de administrat. Suportul tehnic este prompt și eficient. Performanțele sunt excelente, chiar și cu trafic mare. Merită fiecare ban investit!</p>
                                    <div class="flex gap-2">
                                        <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">Cumpărător Verificat</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full">Recomandă</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:border-blue-300 transition">
                            <div class="flex items-start gap-4 mb-4">
                                <div class="bg-gradient-to-br from-orange-500 to-red-600 text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl">
                                    IP
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-bold text-lg">Ion Popescu</h4>
                                        <span class="text-sm text-gray-500">2 săptămâni în urmă</span>
                                    </div>
                                    <div class="flex mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star text-yellow-400"></i>
                                        @endfor
                                    </div>
                                    <p class="text-gray-700 leading-relaxed mb-3">Am achiziționat Portal Corporate pentru firma noastră și suntem extrem de mulțumiți. Funcționalitățile sunt exact ce aveam nevoie. Training-ul oferit a fost detaliat și util. 5 stele fără ezitare!</p>
                                    <div class="flex gap-2">
                                        <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">Cumpărător Verificat</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full">Recomandă</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Review Button -->
                    <div class="text-center mt-8">
                        <button class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl hover:from-blue-700 hover:to-purple-700 text-lg font-bold shadow-lg hover:shadow-xl transition">
                            <i class="fas fa-edit mr-2"></i>Scrie o Recenzie
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="animate-fade-in-up">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-4xl font-bold text-gray-900">{{ __('messages.related_products') }}</h2>
                    @if($product->category)
                        <a href="{{ route('shop.category', $product->category->slug) }}" class="text-blue-600 hover:text-blue-700 font-semibold text-lg">
                            Vezi toate <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    @foreach($relatedProducts as $related)
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden group transform hover:-translate-y-2">
                            <div class="relative overflow-hidden h-64">
                                @php
                                    $relatedImages = is_string($related->images) ? json_decode($related->images, true) : $related->images;
                                    $relatedImage = $relatedImages[0] ?? null;
                                @endphp
                                
                                @if($relatedImage)
                                    <img src="{{ strpos($relatedImage, 'http') === 0 ? $relatedImage : asset($relatedImage) }}" 
                                         alt="{{ $related->getTranslation('name', app()->getLocale()) }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="bg-gradient-to-br from-blue-500 to-purple-600 h-full flex items-center justify-center">
                                        <i class="fas fa-box text-white text-6xl opacity-50"></i>
                                    </div>
                                @endif
                                
                                @if($related->sale_price)
                                    <span class="absolute top-4 right-4 bg-red-500 text-white text-sm font-bold px-3 py-1 rounded-full">
                                        -{{ $related->getDiscountPercent() }}%
                                    </span>
                                @endif
                            </div>
                            <div class="p-6">
                                @if($related->category)
                                    <a href="{{ route('shop.category', $related->category->slug) }}" class="text-xs text-blue-600 font-semibold mb-2 block hover:underline">
                                        {{ $related->category->getTranslation('name', app()->getLocale()) }}
                                    </a>
                                @endif
                                <h3 class="font-bold text-lg mb-3 text-gray-900 line-clamp-2">
                                    {{ $related->getTranslation('name', app()->getLocale()) }}
                                </h3>
                                <div class="flex items-center mb-4">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600">(5.0)</span>
                                </div>
                                <div class="flex items-center justify-between mb-4">
                                    @if($related->sale_price)
                                        <div>
                                            <span class="text-gray-400 line-through text-sm">{{ number_format($related->price, 0, ',', '.') }} RON</span>
                                            <span class="text-2xl font-bold text-green-600 ml-2">{{ number_format($related->sale_price, 0, ',', '.') }} RON</span>
                                        </div>
                                    @else
                                        <span class="text-2xl font-bold text-blue-600">{{ number_format($related->price, 0, ',', '.') }} RON</span>
                                    @endif
                                </div>
                                <a href="{{ route('shop.show', $related->slug) }}" 
                                   class="block text-center bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-blue-800 font-semibold transition-all shadow-md hover:shadow-lg">
                                    Vezi Detalii <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </main>
    
    @php
        $footerWidget = \App\Models\Widget::where('type', 'footer')->where('status', 'active')->first();
    @endphp
    @if($footerWidget)
        @include('widgets.footer', ['widget' => $footerWidget])
    @endif
</body>
</html>
