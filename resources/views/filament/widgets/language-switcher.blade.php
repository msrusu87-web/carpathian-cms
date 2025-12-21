{{-- Language Switcher Widget - positioned in user menu area --}}
<div x-data="{ languageOpen: false }" class="fi-language-switcher relative">
    <button @click="languageOpen = !languageOpen" 
            type="button"
            class="fi-dropdown-trigger flex items-center justify-center gap-x-2 rounded-lg px-3 py-2 text-sm font-medium outline-none transition duration-75 hover:bg-gray-50 focus-visible:bg-gray-50 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">
        <svg class="fi-dropdown-trigger-icon h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
        </svg>
        <span class="fi-dropdown-trigger-label font-medium text-gray-700 dark:text-gray-200">
            {{ strtoupper(app()->getLocale()) }}
        </span>
        <svg class="fi-dropdown-trigger-icon h-5 w-5 text-gray-400 dark:text-gray-500 transition" :class="{ 'rotate-180': languageOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
         class="fi-dropdown-panel absolute right-0 z-50 mt-2 w-56 divide-y divide-gray-100 overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-gray-950/5 dark:divide-white/5 dark:bg-gray-900 dark:ring-white/10"
         style="display: none;">
        <div class="fi-dropdown-list p-1">
            @foreach(['ro' => 'Română', 'en' => 'English', 'de' => 'Deutsch', 'es' => 'Español', 'fr' => 'Français', 'it' => 'Italiano'] as $code => $name)
                <a href="{{ url('/lang/' . $code) }}"
                   class="fi-dropdown-list-item flex w-full items-center gap-3 whitespace-nowrap rounded-md px-3 py-2 text-sm outline-none transition duration-75
                          {{ app()->getLocale() === $code 
                             ? 'bg-gray-50 font-semibold text-primary-600 dark:bg-white/5 dark:text-primary-400' 
                             : 'hover:bg-gray-50 dark:hover:bg-white/5' }}">
                    <span class="fi-dropdown-list-item-icon flex h-5 w-5 items-center justify-center text-xs font-bold text-gray-400 dark:text-gray-500">
                        {{ strtoupper($code) }}
                    </span>
                    <span class="fi-dropdown-list-item-label flex-1 truncate text-start text-gray-700 dark:text-gray-200">
                        {{ $name }}
                    </span>
                    @if(app()->getLocale() === $code)
                        <svg class="fi-dropdown-list-item-icon h-5 w-5 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>
