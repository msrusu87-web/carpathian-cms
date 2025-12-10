<div class="fi-wi-language-switcher" style="position: fixed; top: 20px; right: 20px; z-index: 50;">
    <div class="flex items-center gap-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg px-4 py-2 border border-gray-200 dark:border-gray-700">
        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
        </svg>
        
        <form method="POST" action="{{ route('filament.admin.locale.switch') }}" class="flex items-center gap-1">
            @csrf
            <button type="submit" name="locale" value="en" 
                    class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors {{ app()->getLocale() === 'en' ? 'bg-amber-100 text-amber-900 dark:bg-amber-900/50 dark:text-amber-100' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                EN
            </button>
            <span class="text-gray-400">|</span>
            <button type="submit" name="locale" value="ro" 
                    class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors {{ app()->getLocale() === 'ro' ? 'bg-amber-100 text-amber-900 dark:bg-amber-900/50 dark:text-amber-100' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                RO
            </button>
        </form>
    </div>
</div>
