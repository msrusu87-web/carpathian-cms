<x-filament-widgets::widget>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($this->getStats() as $stat)
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-{{ $stat['color'] }}-500/10 to-{{ $stat['color'] }}-600/10 border border-{{ $stat['color'] }}-500/20 p-6 group hover:scale-105 transition-all duration-300 hover:shadow-xl hover:shadow-{{ $stat['color'] }}-500/20">
                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-{{ $stat['color'] }}-500/20 rounded-xl backdrop-blur-sm">
                            <x-dynamic-component 
                                :component="$stat['icon']" 
                                class="w-6 h-6 text-{{ $stat['color'] }}-400" 
                            />
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full bg-{{ $stat['color'] }}-500/20 text-{{ $stat['color'] }}-300 font-semibold">
                            {{ $stat['change'] }}
                        </span>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mb-1">{{ $stat['title'] }}</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stat['value'] }}</p>
                    </div>
                </div>
                
                <div class="absolute bottom-0 right-0 opacity-10">
                    <x-dynamic-component 
                        :component="$stat['icon']" 
                        class="w-32 h-32 text-{{ $stat['color'] }}-500 transform translate-x-8 translate-y-8" 
                    />
                </div>
            </div>
        @endforeach
    </div>
</x-filament-widgets::widget>
