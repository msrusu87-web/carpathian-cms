<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.cart') }} - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    @include('partials.navigation')

    <!-- Page Header -->
    <header class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-4xl font-bold">{{ __('messages.cart') }}</h1>
            <p class="text-lg mt-2">{{ __('messages.cart_review_subtitle') }}</p>
        </div>
    </header>

    <!-- Cart Content -->
    <main class="max-w-7xl mx-auto px-4 py-12">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
        @endif

        @if(empty($cart))
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <svg class="w-32 h-32 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h2 class="text-2xl font-bold text-gray-700 mb-4">{{ __('messages.cart_empty') }}</h2>
            <p class="text-gray-600 mb-8">{{ __('messages.cart_empty_desc') }}</p>
            <a href="/shop" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 text-lg font-semibold">
                {{ __('messages.browse_services') }}
            </a>
        </div>
        @else
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    @foreach($cart as $id => $item)
                    <div class="flex items-center gap-4 p-6 border-b last:border-b-0">
                        @if($item['image'])
                        <img src="{{ strpos($item['image'], 'http') === 0 ? $item['image'] : asset($item['image']) }}" alt="{{ $item['name'] }}" class="w-24 h-24 object-cover rounded-lg">
                        @else
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
                            <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif
                        
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-800">{{ $item['name'] }}</h3>
                            <p class="text-blue-600 font-semibold mt-1">${{ number_format($item['price'], 2) }}</p>
                            
                            <div class="flex items-center gap-4 mt-4">
                                <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <label class="text-sm text-gray-600">{{ __('messages.qty') }}:</label>
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="10" class="w-20 px-3 py-2 border rounded">
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">{{ __('messages.update') }}</button>
                                </form>
                                
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold">{{ __('messages.remove') }}</button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <p class="text-xl font-bold text-gray-800">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-20">
                    <h2 class="text-2xl font-bold mb-6">{{ __('messages.order_summary') }}</h2>
                    
                    <div class="space-y-3 mb-6 pb-6 border-b">
                        <div class="flex justify-between text-gray-600">
                            <span>{{ __('messages.subtotal') }}</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>{{ __('messages.tax') }} (0%)</span>
                            <span>$0.00</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>{{ __('messages.shipping') }}</span>
                            <span>{{ __('messages.free') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between text-xl font-bold mb-6">
                        <span>{{ __('messages.total') }}</span>
                        <span class="text-blue-600">${{ number_format($total, 2) }}</span>
                    </div>
                    
                    <a href="{{ route('checkout.index') }}" class="block w-full bg-green-600 text-white px-6 py-4 rounded-lg hover:bg-green-700 text-lg font-semibold mb-3 text-center">
                        {{ __('messages.proceed_to_checkout') }}
                    </a>
                    
                    <a href="/shop" class="block w-full bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 text-center font-semibold">
                        {{ __('messages.continue_shopping') }}
                    </a>
                    
                    <form action="{{ route('cart.clear') }}" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-red-600 hover:text-red-800 text-sm font-semibold py-2">
                            {{ __('messages.clear_cart') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </main>

    <!-- Footer -->
    @php
        $footerWidget = \App\Models\Widget::where('type', 'footer')->where('status', 'active')->first();
    @endphp
    @if($footerWidget)
        @include('widgets.footer', ['widget' => $footerWidget])
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const openIcon = document.getElementById('menu-open-icon');
            const closeIcon = document.getElementById('menu-close-icon');

            if (menuButton) {
                menuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                    openIcon.classList.toggle('hidden');
                    closeIcon.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>
