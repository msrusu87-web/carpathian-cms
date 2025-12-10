<!-- Footer Widget -->
<footer class="bg-gray-900 text-gray-300 py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About Column -->
            <div>
                <img src="/images/carpathian-logo-dark.svg" alt="{{ __("messages.carpathian") }} CMS" style="width: 350px; height: auto;" class="mb-4">
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
        
        <div class="border-t border-gray-800 mt-8 pt-8 text-center">
            <p class="text-gray-400">© {{ date('Y') }} {{ __('messages.carpathian') }}. {{ __('messages.all_rights_reserved') }}</p>
        </div>
    </div>
</footer>
