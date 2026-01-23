<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.our_portfolio') }} | Carphatian CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-900">
    @include('partials.navigation')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-blue-50 py-20">
    <div class="container mx-auto px-4">
        <!-- Header Section with Animation -->
        <div class="text-center mb-20 animate-fade-in">
            <span class="inline-block px-6 py-2 mb-6 text-sm font-semibold text-purple-700 bg-purple-100 rounded-full shadow-sm hover:shadow-md transition-shadow duration-300">
                {{ __('messages.portfolio') }}
            </span>
            <h1 class="text-6xl md:text-7xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 mb-6 leading-tight">
                {{ __('messages.our_portfolio') }}
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 max-w-4xl mx-auto leading-relaxed font-light">
                {{ __('messages.portfolio_subtitle') }}
            </p>
        </div>

        <!-- Portfolio Grid with Modern Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto mb-16">
            
            <!-- Project 0: zanziBAR Cernavodă - LATEST -->
            <div class="group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-900/10 to-amber-700/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>
                
                <div class="relative h-72 overflow-hidden bg-gradient-to-br from-emerald-900 via-amber-700 to-yellow-600">
                    <img src="/images/portfolio/zanzibarcaffe-footer.png" 
                         alt="zanziBAR Cernavodă" 
                         class="w-full h-full object-contain bg-gray-900 p-4 group-hover:scale-110 transition-transform duration-700"
                         loading="lazy"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-900/90 to-amber-700/90 flex items-center justify-center" style="display:none;">
                        <div class="text-center text-white p-6">
                            <svg class="w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 21h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-base font-bold tracking-wide">{{ __('messages.web_development') }}</p>
                        </div>
                    </div>
                    <div class="absolute top-4 right-4 z-20">
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-emerald-900 shadow-lg backdrop-blur-sm">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            {{ __('messages.web_development') }}
                        </span>
                    </div>
                </div>
                
                <div class="p-8 relative z-20">
                    <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-emerald-900 group-hover:to-amber-700 transition-all duration-300">
                        zanziBAR Cernavodă — Website Redesign & Development
                    </h3>
                    <p class="text-gray-600 mb-6 leading-relaxed line-clamp-3">
                        Client: zanziBAR Lounge & Coffee (Cernavodă). Platformă: Laravel/Blade (Carpathian CMS). Decembrie 2025. Meniu digital restructurat, PDF printabil, design coffee-themed, SEO local și responsivitate completă.
                    </p>
                    
                    <a href="https://zanzibarcaffe.ro" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-900 to-amber-700 text-white font-semibold rounded-xl hover:from-emerald-800 hover:to-amber-600 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        {{ __('messages.visit_site') }}
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Project 1: Carpathian AI SaaS Marketplace -->
            <div class="group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer">
                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>
                
                <!-- Image Container with Gradient Background -->
                <div class="relative h-72 overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600">
                    <img src="/images/portfolio/saas-marketplace.jpg" 
                         alt="Carpathian AI SaaS Marketplace" 
                         class="w-full h-full object-cover object-top group-hover:scale-110 transition-transform duration-700"
                         loading="lazy"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/90 to-purple-600/90 flex items-center justify-center" style="display:none;">
                        <div class="text-center text-white p-6">
                            <svg class="w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="text-base font-bold tracking-wide">{{ __('messages.ai_platform') }}</p>
                        </div>
                    </div>
                    <!-- Floating Badge -->
                    <div class="absolute top-4 right-4 z-20">
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-indigo-700 shadow-lg backdrop-blur-sm">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                            </svg>
                            {{ __('messages.ai_platform') }}
                        </span>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-8 relative z-20">
                    <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-indigo-600 group-hover:to-purple-600 transition-all duration-300">
                        Carpathian AI SaaS Marketplace
                    </h3>
                    <p class="text-gray-600 mb-6 leading-relaxed line-clamp-3">
                        AI-powered freelance platform connecting talented professionals with clients through intelligent matching and seamless collaboration.
                    </p>
                    
                    <a href="https://chat.carphatian.ro" target="_blank" rel="noopener noreferrer" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        {{ __('messages.visit_site') }}
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Project 2: Demo Tools Portfolio -->
            <div class="group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>
                
                <div class="relative h-72 overflow-hidden bg-gradient-to-br from-purple-600 via-blue-600 to-cyan-600">
                    <img src="/images/portfolio/demo-tools.jpg" 
                         alt="Demo Tools Portfolio" 
                         class="w-full h-full object-cover object-top group-hover:scale-110 transition-transform duration-700"
                         loading="lazy"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-600/90 to-blue-600/90 flex items-center justify-center" style="display:none;">
                        <div class="text-center text-white p-6">
                            <svg class="w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                            <p class="text-base font-bold tracking-wide">{{ __('messages.web_tools') }}</p>
                        </div>
                    </div>
                    <div class="absolute top-4 right-4 z-20">
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-purple-700 shadow-lg backdrop-blur-sm">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            {{ __('messages.web_tools') }}
                        </span>
                    </div>
                </div>
                
                <div class="p-8 relative z-20">
                    <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-purple-600 group-hover:to-blue-600 transition-all duration-300">
                        Demo Tools Portfolio
                    </h3>
                    <p class="text-gray-600 mb-6 leading-relaxed line-clamp-3">
                        Professional web tools showcasing various utilities and demonstrations for modern web development.
                    </p>
                    
                    <a href="https://social.carphatian.ro" target="_blank" rel="noopener noreferrer" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-blue-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        {{ __('messages.visit_site') }}
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Project 4: ATMN - Antimony Coin -->
            <div class="group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-teal-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>
                
                <div class="relative h-72 overflow-hidden bg-gradient-to-br from-green-600 via-emerald-600 to-teal-600">
                    <img src="/images/portfolio/antimony-coin.jpg" 
                         alt="ATMN Antimony Coin" 
                         class="w-full h-full object-contain bg-gray-900 p-2 group-hover:scale-110 transition-transform duration-700"
                         loading="lazy"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-600/90 to-teal-600/90 flex items-center justify-center" style="display:none;">
                        <div class="text-center text-white p-6">
                            <svg class="w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-base font-bold tracking-wide">{{ __('messages.blockchain') }}</p>
                        </div>
                    </div>
                    <div class="absolute top-4 right-4 z-20">
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-green-700 shadow-lg backdrop-blur-sm">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                            </svg>
                            {{ __('messages.blockchain') }}
                        </span>
                    </div>
                </div>
                
                <div class="p-8 relative z-20">
                    <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-green-600 group-hover:to-teal-600 transition-all duration-300">
                        ATMN - Antimony Coin
                    </h3>
                    <p class="text-gray-600 mb-6 leading-relaxed line-clamp-3">
                        Blockchain explorer and cryptocurrency platform for Antimony Coin with real-time transaction tracking and analytics.
                    </p>
                    
                    <a href="https://explorer.carphatian.ro" target="_blank" rel="noopener noreferrer" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-teal-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        {{ __('messages.visit_site') }}
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Project 5: Language Detection -->
            <div class="group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-indigo-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>
                
                <div class="relative h-72 overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-600">
                    <img src="/images/portfolio/language-detection.jpg" 
                         alt="Language Detection" 
                         class="w-full h-full object-contain bg-gray-900 p-2 group-hover:scale-110 transition-transform duration-700"
                         loading="lazy"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/90 to-indigo-600/90 flex items-center justify-center" style="display:none;">
                        <div class="text-center text-white p-6">
                            <svg class="w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.613 3 18.129"/>
                            </svg>
                            <p class="text-base font-bold tracking-wide">{{ __('messages.openai') }}</p>
                        </div>
                    </div>
                    <div class="absolute top-4 right-4 z-20">
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-blue-700 shadow-lg backdrop-blur-sm">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                            </svg>
                            {{ __('messages.openai') }}
                        </span>
                    </div>
                </div>
                
                <div class="p-8 relative z-20">
                    <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-indigo-600 transition-all duration-300">
                        Language Detection
                    </h3>
                    <p class="text-gray-600 mb-6 leading-relaxed line-clamp-3">
                        OpenAI-powered language detection tool that accurately identifies and analyzes text in multiple languages.
                    </p>
                    
                    <a href="https://antimony.carphatian.ro" target="_blank" rel="noopener noreferrer" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        {{ __('messages.visit_site') }}
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Project 6: Carpathian CMS -->
            <div class="group relative bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-[1.02] cursor-pointer">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-pink-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>
                
                <div class="relative h-72 overflow-hidden bg-gradient-to-br from-purple-600 via-fuchsia-600 to-pink-600">
                    <img src="/images/portfolio/carpathian-cms.jpg" 
                         alt="Carpathian CMS" 
                         class="w-full h-full object-contain bg-gray-900 p-2 group-hover:scale-110 transition-transform duration-700"
                         loading="lazy"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-600/90 to-pink-600/90 flex items-center justify-center" style="display:none;">
                        <div class="text-center text-white p-6">
                            <svg class="w-20 h-20 mx-auto mb-4 opacity-90 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-base font-bold tracking-wide">{{ __('messages.ai_powered') }}</p>
                        </div>
                    </div>
                    <div class="absolute top-4 right-4 z-20">
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-white/95 text-purple-700 shadow-lg backdrop-blur-sm">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                            </svg>
                            {{ __('messages.ai_powered') }}
                        </span>
                    </div>
                </div>
                
                <div class="p-8 relative z-20">
                    <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-purple-600 group-hover:to-pink-600 transition-all duration-300">
                        Carpathian CMS
                    </h3>
                    <p class="text-gray-600 mb-6 leading-relaxed line-clamp-3">
                        Modern content management system powered by AI, featuring advanced page building and seamless content creation.
                    </p>
                    
                    <a href="https://cms.carphatian.ro" target="_blank" rel="noopener noreferrer" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        {{ __('messages.visit_site') }}
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </div>

        </div>

            
    </div>
</div>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 1s ease-out;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-lg font-bold mb-4">{{ config('app.name') }}</h3>
                <p class="text-gray-400">{{ __('messages.professional_solutions') }}</p>
            </div>
            
            <div>
                <h4 class="font-semibold mb-4">{{ __('messages.pages') }}</h4>
                <ul class="space-y-2">
                    <li><a href="/" class="text-gray-400 hover:text-white">{{ __('messages.home') }}</a></li>
                    <li><a href="/portfolios" class="text-gray-400 hover:text-white">{{ __('messages.portfolio') }}</a></li>
                    <li><a href="/blog" class="text-gray-400 hover:text-white">{{ __('messages.blog') }}</a></li>
                    <li><a href="/contact" class="text-gray-400 hover:text-white">{{ __('messages.contact') }}</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-semibold mb-4">{{ __('messages.services') }}</h4>
                <ul class="space-y-2">
                    <li><a href="/shop" class="text-gray-400 hover:text-white">{{ __('messages.shop') }}</a></li>
                    <li><a href="/products" class="text-gray-400 hover:text-white">{{ __('messages.products') }}</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-semibold mb-4">{{ __('messages.admin') }}</h4>
                <ul class="space-y-2">
                    <li><a href="/admin" class="text-gray-400 hover:text-white">{{ __('messages.dashboard') }}</a></li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>By Carphatian</p>
        </div>
    </div>
</footer>
</body>
</html>
