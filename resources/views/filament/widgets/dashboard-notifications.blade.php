<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="text-lg font-semibold">Recent Notifications</span>
                </div>
                @if($this->getCounts()['total'] > 0)
                    <span class="px-3 py-1 text-sm font-bold text-white bg-red-500 rounded-full animate-pulse">
                        {{ $this->getCounts()['total'] }}
                    </span>
                @endif
            </div>
        </x-slot>
        
        <div class="mt-4">
            @php
                $notifications = $this->getNotifications();
            @endphp
            
            @if(count($notifications) > 0)
                <div class="space-y-3">
                    @foreach($notifications as $notification)
                        <a href="{{ $notification['url'] }}" 
                           class="block group p-4 rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:border-{{ $notification['color'] }}-500 dark:hover:border-{{ $notification['color'] }}-500 transition-all duration-200 bg-white dark:bg-gray-800 hover:shadow-md">
                            
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 p-2 rounded-lg bg-{{ $notification['color'] }}-100 dark:bg-{{ $notification['color'] }}-900/30">
                                    <x-dynamic-component 
                                        :component="$notification['icon']" 
                                        class="w-5 h-5 text-{{ $notification['color'] }}-600 dark:text-{{ $notification['color'] }}-400"
                                    />
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $notification['title'] }}
                                        </h4>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $notification['time'] }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                                        {{ $notification['message'] }}
                                    </p>
                                    
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-medium text-{{ $notification['color'] }}-600 dark:text-{{ $notification['color'] }}-400">
                                            From: {{ $notification['from'] }}
                                        </span>
                                    </div>
                                </div>
                                
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-{{ $notification['color'] }}-500 group-hover:translate-x-1 transition-all flex-shrink-0" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">
                        All caught up! No new notifications.
                    </p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
