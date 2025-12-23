<div class="relative inline-block text-left" x-data="{ open: false }" @click.away="open = false">
    <button @click="open = !open" type="button" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
        </svg>
        <span>{{ strtoupper(app()->getLocale()) }}</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-cloak class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" x-transition>
        <div class="py-1">
            <a href="{{ route('lang.switch', 'ro') }}" @click="open = false" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() == 'ro' ? 'bg-gray-50 font-semibold' : '' }}">
                <span class="text-lg">🇷🇴</span>
                <span>Română</span>
            </a>
            <a href="{{ route('lang.switch', 'en') }}" @click="open = false" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() == 'en' ? 'bg-gray-50 font-semibold' : '' }}">
                <span class="text-lg">🇬🇧</span>
                <span>English</span>
            </a>
            <a href="{{ route('lang.switch', 'de') }}" @click="open = false" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() == 'de' ? 'bg-gray-50 font-semibold' : '' }}">
                <span class="text-lg">🇩🇪</span>
                <span>Deutsch</span>
            </a>
            <a href="{{ route('lang.switch', 'es') }}" @click="open = false" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() == 'es' ? 'bg-gray-50 font-semibold' : '' }}">
                <span class="text-lg">🇪🇸</span>
                <span>Español</span>
            </a>
            <a href="{{ route('lang.switch', 'fr') }}" @click="open = false" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() == 'fr' ? 'bg-gray-50 font-semibold' : '' }}">
                <span class="text-lg">🇫🇷</span>
                <span>Français</span>
            </a>
            <a href="{{ route('lang.switch', 'it') }}" @click="open = false" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() == 'it' ? 'bg-gray-50 font-semibold' : '' }}">
                <span class="text-lg">🇮🇹</span>
                <span>Italiano</span>
            </a>
        </div>
    </div>
</div>
