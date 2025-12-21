<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - {{ __('messages.server_error') ?? 'Server Error' }} | Carphatian CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="/images/carphatian-logo-transparent.png">
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-red-900 to-gray-900 flex items-center justify-center p-6">
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
            <div class="text-8xl font-black text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-orange-400 mb-4">
                500
            </div>
            
            <!-- Error Title -->
            <h1 class="text-3xl font-bold text-white mb-4">
                {{ __('messages.server_error') ?? 'Server Error' }}
            </h1>
            
            <!-- Error Description -->
            <p class="text-gray-300 text-lg mb-6">
                {{ __('messages.server_error_desc') ?? 'Something went wrong on our end. Our team has been notified.' }}
            </p>
            
            <!-- Maintenance Notice -->
            <div class="bg-yellow-500/20 border border-yellow-500/40 rounded-xl p-4 mb-6">
                <div class="flex items-center justify-center text-yellow-300">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="font-semibold">{{ __('messages.under_maintenance') ?? 'Website Under Maintenance' }}</span>
                </div>
            </div>
            
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
                        <span class="text-red-400 mr-2">•</span>
                        {{ __('messages.cause_maintenance') ?? 'The website may be undergoing scheduled maintenance' }}
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-400 mr-2">•</span>
                        {{ __('messages.cause_overload') ?? 'The server may be experiencing high traffic' }}
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-400 mr-2">•</span>
                        {{ __('messages.cause_temporary') ?? 'This is usually a temporary issue - please try again in a few minutes' }}
                    </li>
                </ul>
            </div>
            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="javascript:location.reload()" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-red-600 to-orange-600 text-white font-semibold hover:from-red-700 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('messages.try_again') ?? 'Try Again' }}
                </a>
                <a href="/" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-white/10 text-white font-semibold hover:bg-white/20 transition-all duration-300 border border-white/20">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    {{ __('messages.go_home') ?? 'Go Home' }}
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
