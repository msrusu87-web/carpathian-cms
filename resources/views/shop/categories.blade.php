<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorii - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    @include('partials.navigation')

    <main class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-6">
            <ol class="flex items-center space-x-2 text-gray-600">
                <li><a href="/" class="hover:text-blue-600">Acasă</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('shop.index') }}" class="hover:text-blue-600">Shop</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-blue-600 font-medium">Categorii</li>
            </ol>
        </nav>

        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Categorii Servicii</h1>
            <p class="text-gray-600">Explorează serviciile noastre organizate pe categorii</p>
        </div>

        @if($categories->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories->whereNull('parent_id') as $category)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                        <div class="relative h-64 overflow-hidden">
                            @if($category->icon)
                                <img src="{{ asset(str_replace('/categories/', '/categories/thumbs/', $category->icon)) }}" 
                                     alt="{{ $category->getTranslation('name', app()->getLocale()) }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-6">
                                    <h2 class="text-2xl font-bold text-white drop-shadow-lg">
                                        {{ $category->getTranslation('name', app()->getLocale()) }}
                                    </h2>
                                </div>
                            @elseif($category->image)
                                <img src="{{ asset(str_replace('/product-categories/', '/product-categories/thumbs/', $category->image)) }}" 
                                     alt="{{ $category->getTranslation('name', app()->getLocale()) }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-6">
                                    <h2 class="text-2xl font-bold text-white drop-shadow-lg">
                                        {{ $category->getTranslation('name', app()->getLocale()) }}
                                    </h2>
                                </div>
                            @else
                                <div class="bg-gradient-to-br from-blue-500 to-purple-600 h-full flex flex-col items-center justify-center">
                                    <i class="fas fa-folder-open text-white text-5xl opacity-75 mb-4"></i>
                                    <h2 class="text-2xl font-bold text-white px-6 text-center">
                                        {{ $category->getTranslation('name', app()->getLocale()) }}
                                    </h2>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            @if($category->description)
                                <p class="text-gray-600 mb-4 line-clamp-2">
                                    {{ $category->getTranslation('description', app()->getLocale()) }}
                                </p>
                            @endif
                            
                            <div class="mb-4 flex items-center justify-between">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-box mr-2"></i> {{ $category->products_count }} {{ $category->products_count == 1 ? 'serviciu' : 'servicii' }}
                                </span>
                            </div>

                            @if($category->children->count() > 0)
                                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs font-semibold text-gray-700 mb-2">Subcategorii:</p>
                                    <ul class="space-y-1">
                                        @foreach($category->children as $child)
                                            <li class="text-sm text-gray-600">
                                                <i class="fas fa-angle-right text-xs mr-1"></i>
                                                {{ $child->getTranslation('name', app()->getLocale()) }}
                                                <span class="text-gray-400">({{ $child->products_count }})</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <a href="{{ route('shop.category', $category->slug) }}" 
                               class="block bg-gradient-to-r from-blue-600 to-purple-600 text-white text-center px-6 py-3 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-300 font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-arrow-right mr-2"></i> Explorează Serviciile
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-folder-open text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Nu există categorii disponibile momentan.</p>
            </div>
        @endif
    </main>

    
</body>
</html>
