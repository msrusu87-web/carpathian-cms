<x-filament-widgets::widget>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <x-slot name="heading">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-envelope class="w-5 h-5 text-cyan-400" />
                        <span class="text-white font-semibold">Contact Messages</span>
                    </div>
                    @if($this->getCounts()['emails'] > 0)
                        <span class="px-2 py-1 text-xs font-bold text-white bg-cyan-500 rounded-full">
                            {{ $this->getCounts()['emails'] }}
                        </span>
                    @endif
                </div>
            </x-slot>
            
            <div class="space-y-2 mt-3">
                @php
                    $contactNotifications = array_filter($this->getNotifications(), fn($n) => $n['type'] === 'contact');
                @endphp
                @forelse($contactNotifications as $notification)
                    <a href="{{ $notification['url'] }}" class="block p-3 rounded-lg bg-cyan-500/10 hover:bg-cyan-500/20 border border-cyan-500/20 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-cyan-500/20 rounded-lg">
                                <x-dynamic-component :component="$notification['icon']" class="w-4 h-4 text-cyan-400" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white">{{ $notification['title'] }}</p>
                                <p class="text-xs text-cyan-200 truncate">{{ $notification['message'] }}</p>
                                <p class="text-xs text-cyan-300 mt-1">{{ $notification['time'] }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-cyan-300 text-center py-4">No new messages</p>
                @endforelse
            </div>
        </x-filament::section>

            <x-slot name="heading">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-chat-bubble-left-right class="w-5 h-5 text-green-400" />
                        <span class="text-white font-semibold">Support Chat</span>
                    </div>
                    @if($this->getCounts()['chats'] > 0)
                        <span class="px-2 py-1 text-xs font-bold text-white bg-green-500 rounded-full">
                            {{ $this->getCounts()['chats'] }}
                        </span>
                    @endif
                </div>
            </x-slot>
            
            <div class="space-y-2 mt-3">
                @php
                    $chatNotifications = array_filter($this->getNotifications(), fn($n) => $n['type'] === 'chat');
                @endphp
                @forelse($chatNotifications as $notification)
                    <a href="{{ $notification['url'] }}" class="block p-3 rounded-lg bg-green-500/10 hover:bg-green-500/20 border border-green-500/20 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-green-500/20 rounded-lg">
                                <x-dynamic-component :component="$notification['icon']" class="w-4 h-4 text-green-400" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white">{{ $notification['title'] }}</p>
                                <p class="text-xs text-green-200 truncate">{{ $notification['message'] }}</p>
                                <p class="text-xs text-green-300 mt-1">{{ $notification['time'] }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-green-300 text-center py-4">No new chats</p>
                @endforelse
            </div>
        </x-filament::section>

            <x-slot name="heading">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-shopping-bag class="w-5 h-5 text-amber-400" />
                        <span class="text-white font-semibold">Pending Orders</span>
                    </div>
                    @if($this->getCounts()['orders'] > 0)
                        <span class="px-2 py-1 text-xs font-bold text-white bg-amber-500 rounded-full">
                            {{ $this->getCounts()['orders'] }}
                        </span>
                    @endif
                </div>
            </x-slot>
            
            <div class="space-y-2 mt-3">
                @php
                    $orderNotifications = array_filter($this->getNotifications(), fn($n) => $n['type'] === 'order');
                @endphp
                @forelse($orderNotifications as $notification)
                    <a href="{{ $notification['url'] }}" class="block p-3 rounded-lg bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/20 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-amber-500/20 rounded-lg">
                                <x-dynamic-component :component="$notification['icon']" class="w-4 h-4 text-amber-400" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white">{{ $notification['title'] }}</p>
                                <p class="text-xs text-amber-200 truncate">{{ $notification['message'] }}</p>
                                <p class="text-xs text-amber-300 mt-1">{{ $notification['time'] }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-amber-300 text-center py-4">No pending orders</p>
                @endforelse
            </div>
        </x-filament::section>
    </div>
</x-filament-widgets::widget>
