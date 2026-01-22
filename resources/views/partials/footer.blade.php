<footer class="bg-gray-900 text-gray-400 py-12 mt-auto">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4">{{ config('app.name', 'Carphatian') }}</h3>
                <p class="text-sm">
                    {{ __('Your trusted partner for quality products and services.') }}
                </p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4">{{ __('Quick Links') }}</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="/" class="hover:text-white transition">{{ __('Home') }}</a></li>
                    <li><a href="/shop" class="hover:text-white transition">{{ __('Shop') }}</a></li>
                    <li><a href="/blog" class="hover:text-white transition">{{ __('Blog') }}</a></li>
                    <li><a href="/contact" class="hover:text-white transition">{{ __('Contact') }}</a></li>
                </ul>
            </div>
            
            <!-- Customer Service -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4">{{ __('Customer Service') }}</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="/client/support" class="hover:text-white transition">{{ __('Support') }}</a></li>
                    <li><a href="/privacy" class="hover:text-white transition">{{ __('Privacy Policy') }}</a></li>
                    <li><a href="/terms" class="hover:text-white transition">{{ __('Terms & Conditions') }}</a></li>
                </ul>
            </div>
            
            <!-- Contact -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4">{{ __('Contact Us') }}</h3>
                <ul class="space-y-2 text-sm">
                    <li><i class="fas fa-envelope mr-2"></i> contact@carphatian.ro</li>
                    <li><i class="fas fa-phone mr-2"></i> +40 XXX XXX XXX</li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Carphatian') }}. {{ __('All rights reserved.') }}</p>
        </div>
    </div>
</footer>
