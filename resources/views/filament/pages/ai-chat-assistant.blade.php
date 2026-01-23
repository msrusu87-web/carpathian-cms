<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Status Banner --}}
        @php
            $status = $this->getSidecarStatus();
        @endphp
        <div class="flex items-center justify-between p-4 rounded-lg {{ $status['status'] === 'online' ? 'bg-success-50 dark:bg-success-950' : 'bg-danger-50 dark:bg-danger-950' }}">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full {{ $status['status'] === 'online' ? 'bg-success-500 animate-pulse' : 'bg-danger-500' }}"></div>
                <span class="text-sm font-medium {{ $status['status'] === 'online' ? 'text-success-700 dark:text-success-300' : 'text-danger-700 dark:text-danger-300' }}">
                    {{ $status['status'] === 'online' ? __('AI Assistant Online') : __('AI Assistant Offline') }}
                </span>
            </div>
            <div class="flex gap-2">
                <x-filament::button 
                    size="sm" 
                    color="warning"
                    wire:click="runBackup"
                    icon="heroicon-o-arrow-down-tray"
                >
                    {{ __('Backup Now') }}
                </x-filament::button>
                <x-filament::button 
                    size="sm" 
                    color="danger"
                    wire:click="restoreLatest"
                    wire:confirm="{{ __('Are you sure you want to restore the latest backup? This will overwrite current data.') }}"
                    icon="heroicon-o-arrow-path"
                >
                    {{ __('Restore Latest') }}
                </x-filament::button>
            </div>
        </div>

        {{-- Chat Container --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            {{-- Chat Messages --}}
            <div class="h-[500px] overflow-y-auto p-4 space-y-4" id="chat-messages">
                @forelse($conversation as $msg)
                    <div class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] rounded-lg p-4 {{ 
                            $msg['role'] === 'user' 
                                ? 'bg-primary-500 text-white' 
                                : ($msg['role'] === 'error' 
                                    ? 'bg-danger-100 dark:bg-danger-900 text-danger-700 dark:text-danger-300' 
                                    : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100') 
                        }}">
                            @if($msg['role'] === 'assistant')
                                <div class="flex items-center gap-2 mb-2">
                                    <x-heroicon-o-cpu-chip class="w-5 h-5 text-primary-500" />
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('AI Assistant') }}</span>
                                </div>
                            @endif
                            
                            <div class="prose dark:prose-invert prose-sm max-w-none">
                                {!! nl2br(e($msg['content'])) !!}
                            </div>

                            @if(!empty($msg['toolCalls']))
                                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('Actions taken:') }}</p>
                                    @foreach($msg['toolCalls'] as $tool)
                                        <div class="flex items-center gap-2 text-xs bg-white/50 dark:bg-black/20 rounded px-2 py-1 mb-1">
                                            <x-heroicon-o-wrench-screwdriver class="w-4 h-4" />
                                            <span>{{ $tool['name'] ?? $tool }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-2 text-xs opacity-60">
                                {{ \Carbon\Carbon::parse($msg['timestamp'])->format('H:i') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center text-gray-500 dark:text-gray-400">
                        <x-heroicon-o-chat-bubble-left-right class="w-16 h-16 mb-4 opacity-50" />
                        <h3 class="text-lg font-medium mb-2">{{ __('Start a conversation') }}</h3>
                        <p class="text-sm max-w-md">
                            {{ __('Ask me to manage your CMS: create products, update pages, run backups, import data, generate content, and more.') }}
                        </p>
                        <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                            <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3 text-left">
                                <p class="font-medium mb-1">{{ __('Example:') }}</p>
                                <p class="opacity-75">"Create a new product called Widget Pro for $99"</p>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3 text-left">
                                <p class="font-medium mb-1">{{ __('Example:') }}</p>
                                <p class="opacity-75">"List all products with low stock"</p>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3 text-left">
                                <p class="font-medium mb-1">{{ __('Example:') }}</p>
                                <p class="opacity-75">"Update the About page content"</p>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3 text-left">
                                <p class="font-medium mb-1">{{ __('Example:') }}</p>
                                <p class="opacity-75">"Create a backup before I make changes"</p>
                            </div>
                        </div>
                    </div>
                @endforelse

                @if($isProcessing)
                    <div class="flex justify-start">
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4">
                            <div class="flex items-center gap-2">
                                <div class="flex space-x-1">
                                    <div class="w-2 h-2 bg-primary-500 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                                    <div class="w-2 h-2 bg-primary-500 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                                    <div class="w-2 h-2 bg-primary-500 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                                </div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Thinking...') }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Input Area --}}
            <div class="border-t border-gray-200 dark:border-gray-700 p-4 bg-gray-50 dark:bg-gray-800/50">
                <div class="flex gap-3">
                    <div class="flex-1">
                        <textarea
                            wire:model="message"
                            wire:keydown.enter.prevent="sendMessage"
                            placeholder="{{ __('Type your message...') }}"
                            rows="2"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 resize-none"
                            @disabled($isProcessing)
                        ></textarea>
                    </div>
                    <div class="flex flex-col gap-2">
                        <x-filament::button
                            wire:click="sendMessage"
                            :disabled="$isProcessing"
                            icon="heroicon-o-paper-airplane"
                        >
                            {{ __('Send') }}
                        </x-filament::button>
                        <x-filament::button
                            wire:click="clearConversation"
                            color="gray"
                            size="sm"
                            icon="heroicon-o-trash"
                        >
                            {{ __('Clear') }}
                        </x-filament::button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button 
                wire:click="$set('message', 'List all products')" 
                class="p-4 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-primary-500 transition-colors text-left"
            >
                <x-heroicon-o-shopping-bag class="w-6 h-6 text-primary-500 mb-2" />
                <span class="text-sm font-medium">{{ __('List Products') }}</span>
            </button>
            <button 
                wire:click="$set('message', 'List all pages')" 
                class="p-4 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-primary-500 transition-colors text-left"
            >
                <x-heroicon-o-document-text class="w-6 h-6 text-primary-500 mb-2" />
                <span class="text-sm font-medium">{{ __('List Pages') }}</span>
            </button>
            <button 
                wire:click="$set('message', 'Show available backups')" 
                class="p-4 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-primary-500 transition-colors text-left"
            >
                <x-heroicon-o-archive-box class="w-6 h-6 text-primary-500 mb-2" />
                <span class="text-sm font-medium">{{ __('View Backups') }}</span>
            </button>
            <button 
                wire:click="$set('message', 'Run a database backup')" 
                class="p-4 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-primary-500 transition-colors text-left"
            >
                <x-heroicon-o-arrow-down-tray class="w-6 h-6 text-warning-500 mb-2" />
                <span class="text-sm font-medium">{{ __('Create Backup') }}</span>
            </button>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-scroll chat to bottom
        document.addEventListener('livewire:navigated', () => {
            const chatMessages = document.getElementById('chat-messages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        });

        Livewire.hook('message.processed', () => {
            const chatMessages = document.getElementById('chat-messages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        });
    </script>
    @endpush
</x-filament-panels::page>
