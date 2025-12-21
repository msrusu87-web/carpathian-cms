<!-- Footer Widget -->
<footer class="bg-gray-900 text-gray-300 py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About Column -->
            <div>
                <img src="/images/carphatian-logo-transparent.png" alt="{{ __("messages.carpathian") }} CMS" style="width: 350px; height: auto;" class="mb-4">
                <p class="text-gray-400 mb-4">
                    {{ __('messages.professional_solutions') }}
                </p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 class="text-white text-lg font-bold mb-4">{{ __('messages.quick_links') }}</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">{{ __('messages.home') }}</a></li>
                    <li><a href="{{ route('blog') }}" class="hover:text-white transition-colors">{{ __('messages.blog') }}</a></li>
                    <li><a href="{{ route('shop.index') }}" class="hover:text-white transition-colors">{{ __('messages.shop') }}</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">{{ __('messages.contact') }}</a></li>
                </ul>
            </div>
            
            @if(($widget->settings['show_pages'] ?? true))
                @php
                    $pages = \App\Models\Page::where('status', 'published')
                                ->where('show_in_menu', true)
                                ->take(5)
                                ->get();
                @endphp
                @if($pages->count() > 0)
                    <!-- Pages Column -->
                    <div>
                        <h3 class="text-white text-lg font-bold mb-4">{{ __('messages.pages') }}</h3>
                        <ul class="space-y-2">
                            @foreach($pages as $page)
                                <li>
                                    <a href="{{ route('page.show', $page->slug) }}" 
                                       class="hover:text-white transition-colors">
                                        {{ $page->getTranslation('title', app()->getLocale()) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endif
            
            <!-- Contact Info -->
            <div>
                <h3 class="text-white text-lg font-bold mb-4">{{ __('messages.contact') }}</h3>
                @php
                    $contactSettings = \App\Models\ContactSetting::first();
                @endphp
                @if($contactSettings)
                    <ul class="space-y-2 text-gray-400">
                        @if($contactSettings->email)
                            <li>📧 {{ $contactSettings->email }}</li>
                        @endif
                        @if($contactSettings->phone)
                            <li>📞 {{ $contactSettings->phone }}</li>
                        @endif
                        @if($contactSettings->address)
                            <li>📍 {{ $contactSettings->address }}</li>
                        @endif
                    </ul>
                @endif
            </div>
        </div>
        
        <div class="border-t border-gray-800 mt-8 pt-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Company Info -->
                <div class="text-center md:text-left">
                    <p class="text-gray-400">© {{ date('Y') }} <strong>Aziz Ride Sharing S.R.L.</strong> - By Carphatian</p>
                    <p class="text-gray-500 text-sm mt-1">{{ __('messages.all_rights_reserved') }}</p>
                </div>
                
                <!-- ANPC Link -->
                <div class="flex items-center gap-3">
                    <a href="https://anpc.ro/" target="_blank" rel="noopener noreferrer" class="hover:opacity-80 transition-opacity" title="ANPC - Protecția Consumatorilor">
                        <img src="/images/anpc-logo.svg" alt="ANPC - Protecția Consumatorilor" class="h-10 w-auto">
                    </a>
                    <a href="https://ec.europa.eu/consumers/odr" target="_blank" rel="noopener noreferrer" class="hover:opacity-80 transition-opacity" title="SOL - Soluționarea Online a Litigiilor">
                        <img src="/images/anpc-sol.svg" alt="SOL - Soluționarea Online a Litigiilor" class="h-10 w-auto">
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
