@php
    // Get all active menus from header location
    $menus = \App\Models\Menu::where('location', 'header')
        ->where('is_active', true)
        ->with(['items' => function($query) {
            $query->where('is_active', true)->orderBy('order')->with('page');
        }])
        ->get();
    
    // Translation map for menu items
    $translations = [
        'home' => __('messages.home'),
        'about us' => __('messages.about'),
        'about' => __('messages.about'),
        'services' => __('messages.services'),
        'blog' => __('messages.blog'),
        'shop' => __('messages.shop'),
        'portfolios' => __('messages.portfolio'),
        'contact' => __('messages.contact'),
    ];
@endphp

<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-24">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="/" class="flex items-center">
                    <img src="/images/carphatian-logo-transparent.png" alt="Carphatian CMS" style="width: 280px; height: auto;">
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center flex-wrap gap-3 lg:gap-4 xl:gap-6">
                <!-- Home Link -->
                <a href="/" class="text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap {{ request()->is('/') ? 'font-semibold text-purple-600' : '' }}">
                    {{ __('messages.home') }}
                </a>
                
                <!-- Plugin Dropdown Menus -->
                @foreach($menus as $menu)
                    <div class="relative group">
                        @php
                            $menuNameKey = strtolower(trim($menu->name));
                            $displayMenuName = $translations[$menuNameKey] ?? $menu->name;
                        @endphp
                        <button class="text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap flex items-center gap-1">
                            {{ $displayMenuName }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Dropdown -->
                        <div class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-2">
                                @foreach($menu->items as $item)
                                    @php
                                        $itemUrl = $item->type === 'page' && $item->page
                                            ? ($item->page->is_homepage ? '/' : '/' . $item->page->slug)
                                            : $item->url;
                                        $isActive = request()->is(ltrim($itemUrl, '/'));
                                        
                                        // Get translated title
                                        $titleKey = strtolower(trim($item->title));
                                        $displayTitle = $translations[$titleKey] ?? $item->title;
                                    @endphp
                                    
                                    <a href="{{ $itemUrl }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 {{ $isActive ? 'bg-purple-50 text-purple-600 font-semibold' : '' }}"
                                       @if($item->target === '_blank') target="_blank" rel="noopener noreferrer" @endif>
                                        @if($item->icon)
                                            <i class="{{ $item->icon }} mr-2"></i>
                                        @endif
                                        {{ $displayTitle }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <!-- Portfolio Link -->
                <a href="/portfolios" class="text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap {{ request()->is('portfolios*') ? 'font-semibold text-purple-600' : '' }}">
                    {{ __('messages.portfolio') }}
                </a>
                
                <!-- Default Links -->
                <a href="/blog" class="text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap {{ request()->is('blog*') ? 'font-semibold text-purple-600' : '' }}">
                    {{ __('messages.blog') }}
                </a>
                <a href="/contact" class="text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap {{ request()->is('contact*') ? 'font-semibold text-purple-600' : '' }}">
                    {{ __('messages.contact') }}
                </a>

                <!-- Language Switcher -->
                <x-language-switcher />

                <!-- Cart Icon with Badge -->
                <div class="relative" x-data="{ cartCount: 0 }" x-init="fetch('/cart/count').then(r => r.json()).then(d => cartCount = d.count).catch(() => cartCount = 0)">
                    <a href="/cart" class="text-gray-700 hover:text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span x-show="cartCount > 0" 
                              x-text="cartCount" 
                              class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold"
                              style="display: none;">
                        </span>
                    </a>
                </div>

                <!-- Auth Links -->
                @auth
                    @if(Auth::user()->canAccessAdmin())
                        <!-- Admin Link - only for admins -->
                        <a href="/admin" class="bg-purple-600 text-white px-3 py-1.5 lg:px-4 lg:py-2 rounded-lg hover:bg-purple-700 transition text-sm lg:text-base font-semibold whitespace-nowrap">
                            {{ __('messages.admin') }}
                        </a>
                    @endif
                    <!-- My Account Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 text-sm lg:text-base text-gray-700 hover:text-purple-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ Auth::user()->name }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-2">
                                <a href="/client/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    {{ __('Dashboard') }}
                                </a>
                                <a href="/client/orders" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    {{ __('My Orders') }}
                                </a>
                                <a href="/client/support" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    {{ __('Support') }}
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        {{ __('Logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Login & Register Links for guests -->
                    <a href="/login" class="text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap">
                        {{ __('Login') }}
                    </a>
                    <a href="/register" class="bg-purple-600 text-white px-3 py-1.5 lg:px-4 lg:py-2 rounded-lg hover:bg-purple-700 transition text-sm lg:text-base font-semibold whitespace-nowrap">
                        {{ __('Register') }}
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center space-x-3">
                <!-- Language Switcher Mobile -->
                <x-language-switcher />
                
                <button id="mobile-menu-button" class="text-gray-700 hover:text-purple-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4">
            <a href="/" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                {{ __('messages.home') }}
            </a>
            
            @foreach($menus as $menu)
                <div class="border-t border-gray-200">
                    <div class="px-4 py-2 text-sm font-semibold text-gray-900 bg-gray-50">
                        @php
                            $menuNameKey = strtolower(trim($menu->name));
                            $displayMenuName = $translations[$menuNameKey] ?? $menu->name;
                        @endphp
                        {{ $displayMenuName }}
                    </div>
                    @foreach($menu->items as $item)
                        @php
                            $itemUrl = $item->type === 'page' && $item->page
                                ? ($item->page->is_homepage ? '/' : '/' . $item->page->slug)
                                : $item->url;
                            
                            // Get translated title
                            $titleKey = strtolower(trim($item->title));
                            $displayTitle = $translations[$titleKey] ?? $item->title;
                        @endphp
                        
                        <a href="{{ $itemUrl }}" 
                           class="block pl-8 pr-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600"
                           @if($item->target === '_blank') target="_blank" @endif>
                            {{ $displayTitle }}
                        </a>
                    @endforeach
                </div>
            @endforeach
            
            <a href="/portfolios" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600 {{ request()->is('portfolios*') ? 'font-semibold text-purple-600 bg-purple-50' : '' }}">
                {{ __('messages.portfolio') }}
            </a>
            <a href="/blog" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                {{ __('messages.blog') }}
            </a>
            <a href="/contact" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                {{ __('messages.contact') }}
            </a>
            <a href="/cart" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                {{ __('messages.cart') }}
            </a>
            
            <!-- Mobile Auth Links -->
            <div class="border-t border-gray-200 mt-2 pt-2">
                @auth
                    @if(Auth::user()->canAccessAdmin())
                        <a href="/admin" class="block px-4 py-2 text-purple-600 font-semibold hover:bg-purple-50">
                            {{ __('messages.admin') }}
                        </a>
                    @endif
                    <a href="/client/dashboard" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                        {{ __('Dashboard') }}
                    </a>
                    <a href="/client/orders" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                        {{ __('My Orders') }}
                    </a>
                    <a href="/client/support" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                        {{ __('Support') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="px-4 py-2">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-700">
                            {{ __('Logout') }}
                        </button>
                    </form>
                @else
                    <a href="/login" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                        {{ __('Login') }}
                    </a>
                    <a href="/register" class="block px-4 py-2 text-purple-600 font-semibold hover:bg-purple-50">
                        {{ __('Register') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    });
</script>
