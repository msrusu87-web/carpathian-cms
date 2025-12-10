<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-2">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Quick Actions') }}</h3>
            
            <div class="flex items-center gap-1 flex-wrap bg-gray-50 dark:bg-gray-900/50 rounded-lg p-2 border border-gray-200 dark:border-gray-700">
                <!-- New Page -->
                <a href="{{ route('filament.admin.resources.pages.create') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 rounded-md hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all border border-gray-200 dark:border-gray-700 hover:border-purple-300 dark:hover:border-purple-700 group text-sm"
                   title="{{ __('New Page') }}">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ __('New Page') }}</span>
                </a>

                <!-- New Blog Post -->
                <a href="{{ route('filament.admin.resources.posts.create') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all border border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-700 group text-sm"
                   title="{{ __('New Blog Post') }}">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ __('Blog Post') }}</span>
                </a>

                <!-- New Product -->
                <a href="{{ route('filament.admin.resources.products.create') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 rounded-md hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-all border border-gray-200 dark:border-gray-700 hover:border-amber-300 dark:hover:border-amber-700 group text-sm"
                   title="{{ __('New Product') }}">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ __('Product') }}</span>
                </a>

                <!-- Divider -->
                <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>

                <!-- AI Writer -->
                <a href="{{ route('filament.admin.pages.ai-content-writer') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/30 dark:to-pink-900/30 rounded-md hover:from-purple-100 hover:to-pink-100 dark:hover:from-purple-900/40 dark:hover:to-pink-900/40 transition-all border border-purple-300 dark:border-purple-700 group text-sm"
                   title="{{ __('AI Writer') }}">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    <span class="text-xs font-semibold text-purple-700 dark:text-purple-300 whitespace-nowrap">{{ __('AI Writer') }}</span>
                </a>

                <!-- Divider -->
                <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>

                <!-- Orders -->
                <a href="{{ route('filament.admin.resources.orders.index') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 rounded-md hover:bg-green-50 dark:hover:bg-green-900/20 transition-all border border-gray-200 dark:border-gray-700 hover:border-green-300 dark:hover:border-green-700 group text-sm"
                   title="{{ __('Orders') }}">
                    <svg class="w-4 h-4 text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ __('Orders') }}</span>
                </a>

                <!-- Media -->
                <a href="{{ route('filament.admin.resources.media.index') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 rounded-md hover:bg-pink-50 dark:hover:bg-pink-900/20 transition-all border border-gray-200 dark:border-gray-700 hover:border-pink-300 dark:hover:border-pink-700 group text-sm"
                   title="{{ __('Media') }}">
                    <svg class="w-4 h-4 text-pink-600 dark:text-pink-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ __('Media') }}</span>
                </a>

                <!-- AI Settings -->
                <a href="{{ route('filament.admin.resources.ai-settings.index') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 rounded-md hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all border border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700 group text-sm"
                   title="{{ __('AI Settings') }}">
                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ __('AI Settings') }}</span>
                </a>

                <!-- Divider -->
                <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>

                <!-- AI Repair -->
                <a href="{{ route('filament.admin.pages.ai-repair') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/30 dark:to-red-900/30 rounded-md hover:from-orange-100 hover:to-red-100 dark:hover:from-orange-900/40 dark:hover:to-red-900/40 transition-all border border-orange-300 dark:border-orange-700 group text-sm"
                   title="{{ __('AI Repair - Database Optimization') }}">
                    <svg class="w-4 h-4 text-orange-600 dark:text-orange-400 group-hover:scale-110 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-xs font-semibold text-orange-700 dark:text-orange-300 whitespace-nowrap">{{ __('AI Repair') }}</span>
                </button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>