<div x-data="{ languageOpen: false }" class="fi-language-switcher" style="position: fixed; top: 20px; right: 20px; z-index: 50;">
    <div class="relative">
        <button @click="languageOpen = !languageOpen" type="button" 
                class="flex items-center gap-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg px-4 py-2 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
            </svg>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ strtoupper(app()->getLocale()) }}</span>
            <svg class="w-4 h-4 text-gray-500 transition-transform" :class="{ 'rotate-180': languageOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="languageOpen" 
             @click.away="languageOpen = false"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-0 mt-2 w-56 origin-top-right rounded-lg bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-200 dark:border-gray-700"
             style="display: none;">
            <div class="py-1">
                <a href="{{ route('lang.switch', 'en') }}" 
                   class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ app()->getLocale() === 'en' ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-900 dark:text-amber-100 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    <span class="text-lg">🇬🇧</span>
                    <span>English</span>
                </a>
                <a href="{{ route('lang.switch', 'ro') }}" 
                   class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ app()->getLocale() === 'ro' ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-900 dark:text-amber-100 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    <span class="text-lg">🇷🇴</span>
                    <span>Română</span>
                </a>
                <a href="{{ route('lang.switch', 'de') }}" 
                   class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ app()->getLocale() === 'de' ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-900 dark:text-amber-100 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    <span class="text-lg">🇩🇪</span>
                    <span>Deutsch</span>
                </a>
                <a href="{{ route('lang.switch', 'es') }}" 
                   class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ app()->getLocale() === 'es' ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-900 dark:text-amber-100 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    <span class="text-lg">🇪🇸</span>
                    <span>Español</span>
                </a>
                <a href="{{ route('lang.switch', 'fr') }}" 
                   class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ app()->getLocale() === 'fr' ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-900 dark:text-amber-100 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    <span class="text-lg">🇫🇷</span>
                    <span>Français</span>
                </a>
                <a href="{{ route('lang.switch', 'it') }}" 
                   class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ app()->getLocale() === 'it' ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-900 dark:text-amber-100 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    <span class="text-lg">🇮🇹</span>
                    <span>Italiano</span>
                </a>
            </div>
        </div>
    </div>
</div>
