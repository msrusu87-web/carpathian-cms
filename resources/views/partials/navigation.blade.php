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
        'about us' => __('messages.about_us'),
        'services' => __('messages.services'),
        'blog' => __('messages.blog'),
        'shop' => __('messages.shop'),
        'contact' => __('messages.contact'),
    ];
@endphp

<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-24">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="/" class="flex items-center">
                    <img src="/images/carpathian-logo.svg" alt="Carphatian CMS" style="width: 280px; height: auto;">
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
                        <button class="text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap flex items-center gap-1">
                            {{ $menu->name }}
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
                
                <!-- Default Links -->
                <a href="/blog" class="text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap {{ request()->is('blog*') ? 'font-semibold text-purple-600' : '' }}">
                    {{ __('messages.blog') }}
                </a>
                <a href="/contact" class="text-sm lg:text-base text-gray-700 hover:text-purple-600 transition whitespace-nowrap {{ request()->is('contact*') ? 'font-semibold text-purple-600' : '' }}">
                    {{ __('messages.contact') }}
                </a>

                <!-- Language Switcher -->
                <x-language-switcher />

                <!-- Cart Icon -->
                <a href="/cart" class="text-gray-700 hover:text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </a>

                <!-- Admin Link -->
                <a href="/admin" class="bg-purple-600 text-white px-3 py-1.5 lg:px-4 lg:py-2 rounded-lg hover:bg-purple-700 transition text-sm lg:text-base font-semibold whitespace-nowrap">
                    {{ __('messages.admin') }}
                </a>
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
                        {{ $menu->name }}
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
            
            <a href="/blog" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                {{ __('messages.blog') }}
            </a>
            <a href="/contact" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                {{ __('messages.contact') }}
            </a>
            <a href="/cart" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                {{ __('messages.cart') }}
            </a>
            <a href="/admin" class="block px-4 py-2 text-purple-600 font-semibold hover:bg-purple-50">
                {{ __('messages.admin') }}
            </a>
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
