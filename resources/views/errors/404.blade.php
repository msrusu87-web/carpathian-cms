<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - {{ __('messages.page_not_found') ?? 'Page Not Found' }} | Carphatian CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="/images/carphatian-logo-transparent.png">
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 flex items-center justify-center p-6">
    <div class="max-w-2xl w-full text-center">
        <!-- Logo -->
        <div class="mb-8">
            <a href="/">
                <img src="/images/carphatian-logo-transparent.png" alt="Carphatian CMS" class="h-20 mx-auto">
            </a>
        </div>
        
        <!-- Error Card -->
        <div class="bg-white/10 backdrop-blur-lg rounded-3xl shadow-2xl p-10 border border-white/20">
            <!-- Error Code -->
            <div class="text-8xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 mb-4">
                404
            </div>
            
            <!-- Error Title -->
            <h1 class="text-3xl font-bold text-white mb-4">
                {{ __('messages.page_not_found') ?? 'Page Not Found' }}
            </h1>
            
            <!-- Error Description -->
            <p class="text-gray-300 text-lg mb-6">
                {{ __('messages.page_not_found_desc') ?? 'The page you are looking for does not exist or has been moved.' }}
            </p>
            
            <!-- Possible Causes -->
            <div class="bg-white/5 rounded-xl p-6 mb-8 text-left">
                <h3 class="text-white font-semibold mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('messages.possible_causes') ?? 'Possible Causes' }}:
                </h3>
                <ul class="text-gray-400 space-y-2 text-sm">
                    <li class="flex items-start">
                        <span class="text-purple-400 mr-2">•</span>
                        {{ __('messages.cause_typo') ?? 'The URL may have been typed incorrectly' }}
                    </li>
                    <li class="flex items-start">
                        <span class="text-purple-400 mr-2">•</span>
                        {{ __('messages.cause_moved') ?? 'The page may have been moved or deleted' }}
                    </li>
                    <li class="flex items-start">
                        <span class="text-purple-400 mr-2">•</span>
                        {{ __('messages.cause_link') ?? 'The link you followed may be outdated' }}
                    </li>
                </ul>
            </div>
            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold hover:from-purple-700 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    {{ __('messages.go_home') ?? 'Go Home' }}
                </a>
                <a href="/contact" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-white/10 text-white font-semibold hover:bg-white/20 transition-all duration-300 border border-white/20">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ __('messages.contact_us') ?? 'Contact Us' }}
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-8 text-gray-500 text-sm">
            <p>© {{ date('Y') }} Carphatian CMS - Powered by Innovation</p>
        </div>
    </div>
</body>
</html>
