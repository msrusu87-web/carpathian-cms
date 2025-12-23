<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-2">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Quick Actions') }}</h3>
            
            <div class="flex items-center gap-1 flex-wrap bg-gray-50 dark:bg-gray-900/50 rounded-lg p-2 border border-gray-200 dark:border-gray-700">
                <!-- New Page -->
                <a href="{{ route('filament.admin.resources.pages.create') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 rounded-md hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all border border-gray-200 dark:border-gray-700 hover:border-purple-300 dark:hover:border-purple-700 group text-sm"
                   title="{{ __('New Page') }}">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ __('New Page') }}</span>
                </a>

                <!-- New Blog Post -->
                <a href="{{ route('filament.admin.resources.posts.create') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all border border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-700 group text-sm"
                   title="{{ __('New Blog Post') }}">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ __('Blog Post') }}</span>
                </a>

                <!-- New Product -->
                <a href="{{ route('filament.admin.resources.products.create') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 rounded-md hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-all border border-gray-200 dark:border-gray-700 hover:border-amber-300 dark:hover:border-amber-700 group text-sm"
                   title="{{ __('New Product') }}">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ __('Product') }}</span>
                </a>

                <!-- Divider -->
                <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>

                <!-- AI Writer -->
                <a href="{{ route('filament.admin.pages.ai-content-writer') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/30 dark:to-pink-900/30 rounded-md hover:from-purple-100 hover:to-pink-100 dark:hover:from-purple-900/40 dark:hover:to-pink-900/40 transition-all border border-purple-300 dark:border-purple-700 group text-sm"
                   title="{{ __('AI Writer') }}">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z" />
                    </svg>
                    <span class="text-xs font-semibold text-purple-700 dark:text-purple-300 whitespace-nowrap">{{ __('AI Writer') }}</span>
                </a>

                <!-- Divider -->
                <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>

                <!-- Orders -->
                <a href="{{ route('filament.admin.resources.orders.index') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 rounded-md hover:bg-green-50 dark:hover:bg-green-900/20 transition-all border border-gray-200 dark:border-gray-700 hover:border-green-300 dark:hover:border-green-700 group text-sm"
                   title="{{ __('Orders') }}">
                    <svg class="w-4 h-4 text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                    </svg>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ __('Orders') }}</span>
                </a>

                <!-- Media -->
                <a href="{{ route('filament.admin.resources.media.index') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 rounded-md hover:bg-pink-50 dark:hover:bg-pink-900/20 transition-all border border-gray-200 dark:border-gray-700 hover:border-pink-300 dark:hover:border-pink-700 group text-sm"
                   title="{{ __('Media') }}">
                    <svg class="w-4 h-4 text-pink-600 dark:text-pink-400 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ __('Media') }}</span>
                </a>

                <!-- AI Settings -->
                <a href="{{ route('filament.admin.resources.ai-settings.index') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 rounded-md hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all border border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700 group text-sm"
                   title="{{ __('AI Settings') }}">
                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ __('AI Settings') }}</span>
                </a>

                <!-- Divider -->
                <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>

                <!-- AI Repair -->
                <a href="{{ route('filament.admin.pages.ai-repair') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/30 dark:to-red-900/30 rounded-md hover:from-orange-100 hover:to-red-100 dark:hover:from-orange-900/40 dark:hover:to-red-900/40 transition-all border border-orange-300 dark:border-orange-700 group text-sm"
                   title="{{ __('AI Repair - Database Optimization') }}">
                    <svg class="w-4 h-4 text-orange-600 dark:text-orange-400 group-hover:scale-110 group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-xs font-semibold text-orange-700 dark:text-orange-300 whitespace-nowrap">{{ __('AI Repair') }}</span>
                </a>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>