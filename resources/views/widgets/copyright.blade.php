<!-- Copyright Widget -->
<div class="bg-gray-950 text-gray-400 py-6">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <p class="text-sm mb-4 md:mb-0">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
            <div class="flex space-x-6 text-sm">
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                <a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contact</a>
            </div>
        </div>
    </div>
</div>
