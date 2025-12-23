<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span class="text-lg font-semibold">Quick Actions</span>
            </div>
        </x-slot>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            @foreach($this->getActions() as $action)
                <a href="{{ $action['url'] }}" 
                   class="group relative overflow-hidden rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-{{ $action['color'] }}-500 dark:hover:border-{{ $action['color'] }}-500 transition-all duration-300 bg-white dark:bg-gray-800 hover:shadow-lg hover:shadow-{{ $action['color'] }}-500/20">
                    
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 p-3 rounded-lg bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900/30 group-hover:scale-110 transition-transform duration-300">
                                <x-dynamic-component 
                                    :component="$action['icon']" 
                                    class="w-6 h-6 text-{{ $action['color'] }}-600 dark:text-{{ $action['color'] }}-400"
                                />
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                                    {{ $action['label'] }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $action['description'] }}
                                </p>
                            </div>
                            
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-{{ $action['color'] }}-500 group-hover:translate-x-1 transition-all" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-{{ $action['color'] }}-400 to-{{ $action['color'] }}-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
