<!-- Copyright Widget -->
<div class="bg-gray-950 text-gray-400 py-6">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <p class="text-sm mb-4 md:mb-0">
                By Carphatian
            </p>
            <div class="flex space-x-6 text-sm">
                <a href="#" class="hover:text-white transition-colors">{{ __('messages.privacy_policy') }}</a>
                <a href="#" class="hover:text-white transition-colors">{{ __('messages.terms_conditions') }}</a>
                <a href="{{ route('contact') }}" class="hover:text-white transition-colors">{{ __('messages.contact') }}</a>
            </div>
        </div>
    </div>
</div>
