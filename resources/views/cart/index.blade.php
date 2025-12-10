<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Web Agency</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    @include('partials.navigation')

    <!-- Page Header -->
    <header class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-4xl font-bold">Shopping Cart</h1>
            <p class="text-lg mt-2">Review your items and proceed to checkout</p>
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
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Your cart is empty</h2>
            <p class="text-gray-600 mb-8">Start shopping to add items to your cart</p>
            <a href="/shop" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 text-lg font-semibold">
                Browse Services
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
                        <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-24 h-24 object-cover rounded-lg">
                        @else
                        <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
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
                                    <label class="text-sm text-gray-600">Qty:</label>
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="10" class="w-20 px-3 py-2 border rounded">
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Update</button>
                                </form>
                                
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold">Remove</button>
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
                    <h2 class="text-2xl font-bold mb-6">Order Summary</h2>
                    
                    <div class="space-y-3 mb-6 pb-6 border-b">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Tax (0%)</span>
                            <span>$0.00</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping</span>
                            <span>Free</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between text-xl font-bold mb-6">
                        <span>Total</span>
                        <span class="text-blue-600">${{ number_format($total, 2) }}</span>
                    </div>
                    
                    <button class="w-full bg-green-600 text-white px-6 py-4 rounded-lg hover:bg-green-700 text-lg font-semibold mb-3">
                        Proceed to Checkout
                    </button>
                    
                    <a href="/shop" class="block w-full bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 text-center font-semibold">
                        Continue Shopping
                    </a>
                    
                    <form action="{{ route('cart.clear') }}" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-red-600 hover:text-red-800 text-sm font-semibold py-2">
                            Clear Cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">Web Agency</h3>
                    <p class="text-sm">Professional web development and digital solutions for modern businesses.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Services</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/shop" class="hover:text-white">Web Development</a></li>
                        <li><a href="/shop" class="hover:text-white">Mobile Apps</a></li>
                        <li><a href="/shop" class="hover:text-white">E-commerce</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/pages/about-us" class="hover:text-white">About Us</a></li>
                        <li><a href="/blog" class="hover:text-white">Blog</a></li>
                        <li><a href="/contact" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm">
                        <li>Email: info@webagency.com</li>
                        <li>Phone: +1 (555) 123-4567</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} Web Agency. All rights reserved.</p>
            </div>
        </div>
    </footer>

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
