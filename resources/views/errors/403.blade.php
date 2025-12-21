<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - {{ __('messages.access_forbidden') ?? 'Access Forbidden' }} | Carphatian CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="/images/carphatian-logo-transparent.png">
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-orange-900 to-gray-900 flex items-center justify-center p-6">
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
            <div class="text-8xl font-black text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-yellow-400 mb-4">
                403
            </div>
            
            <!-- Error Title -->
            <h1 class="text-3xl font-bold text-white mb-4">
                {{ __('messages.access_forbidden') ?? 'Access Forbidden' }}
            </h1>
            
            <!-- Error Description -->
            <p class="text-gray-300 text-lg mb-6">
                {{ __('messages.access_forbidden_desc') ?? 'You do not have permission to access this resource.' }}
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
                        <span class="text-orange-400 mr-2">•</span>
                        {{ __('messages.cause_not_logged_in') ?? 'You may need to log in to access this page' }}
                    </li>
                    <li class="flex items-start">
                        <span class="text-orange-400 mr-2">•</span>
                        {{ __('messages.cause_no_permission') ?? 'Your account may not have the required permissions' }}
                    </li>
                    <li class="flex items-start">
                        <span class="text-orange-400 mr-2">•</span>
                        {{ __('messages.cause_restricted') ?? 'This content may be restricted to certain users' }}
                    </li>
                </ul>
            </div>
            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/login" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-orange-600 to-yellow-600 text-white font-semibold hover:from-orange-700 hover:to-yellow-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    {{ __('messages.log_in') ?? 'Log In' }}
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
