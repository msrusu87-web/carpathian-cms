<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat with {{ $conversation->participant_name }} - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-gray-100">
    <div x-data="adminChatApp()" x-init="init()" class="h-full flex flex-col">
        <!-- Navigation -->
        <nav class="bg-purple-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.chat.index') }}" class="text-white hover:text-purple-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div>
                            <p class="text-white font-medium">{{ $conversation->participant_name }}</p>
                            <p class="text-purple-200 text-sm">{{ $conversation->participant_email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="px-3 py-1 text-sm rounded-full 
                            {{ $conversation->status == 'open' ? 'bg-green-500 text-white' : '' }}
                            {{ $conversation->status == 'pending' ? 'bg-yellow-500 text-white' : '' }}
                            {{ $conversation->status == 'closed' ? 'bg-gray-500 text-white' : '' }}
                        ">
                            {{ ucfirst($conversation->status) }}
                        </span>
                        @if($conversation->status != 'closed')
                        <form action="{{ route('admin.chat.close', $conversation->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-sm rounded-md">
                                {{ __('Close Chat') }}
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Customer Info -->
        <div class="bg-white border-b px-6 py-3">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center space-x-6 text-sm">
                    <span class="text-gray-500">{{ __('Subject') }}: <span class="text-gray-900">{{ $conversation->subject ?? 'N/A' }}</span></span>
                    @if($conversation->guest_phone)
                    <span class="text-gray-500">{{ __('Phone') }}: <span class="text-gray-900">{{ $conversation->guest_phone }}</span></span>
                    @endif
                    <span class="text-gray-500">{{ __('Type') }}: <span class="px-2 py-0.5 rounded text-xs {{ $conversation->type == 'support' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">{{ ucfirst($conversation->type) }}</span></span>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div id="messagesContainer" class="flex-1 overflow-y-auto p-6 space-y-4 max-w-4xl mx-auto w-full">
            <template x-for="msg in messages" :key="msg.id">
                <div :class="msg.is_admin ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="msg.is_admin ? 'bg-purple-600 text-white' : 'bg-white border border-gray-200'" class="max-w-lg rounded-lg px-4 py-3 shadow-sm">
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="text-xs font-medium" :class="msg.is_admin ? 'text-purple-200' : 'text-purple-600'" x-text="msg.sender_name"></span>
                        </div>
                        <p class="text-sm" :class="msg.is_admin ? 'text-white' : 'text-gray-700'" x-text="msg.message"></p>
                        <p class="text-xs mt-1" :class="msg.is_admin ? 'text-purple-200' : 'text-gray-400'" x-text="formatTime(msg.created_at)"></p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Input -->
        @if($conversation->status != 'closed')
        <div class="bg-white border-t px-6 py-4">
            <div class="max-w-4xl mx-auto">
                <form @submit.prevent="sendMessage()" class="flex items-center space-x-4">
                    <input 
                        type="text" 
                        x-model="newMessage"
                        @keydown.enter="sendMessage()"
                        placeholder="{{ __('Type your reply...') }}"
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                        :disabled="sending"
                    >
                    <button 
                        type="submit" 
                        class="px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition disabled:opacity-50"
                        :disabled="sending || !newMessage.trim()"
                    >
                        <span x-show="!sending">{{ __('Send') }}</span>
                        <svg x-show="sending" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="bg-gray-100 border-t px-6 py-4 text-center text-gray-500">
            {{ __('This conversation is closed.') }}
        </div>
        @endif
    </div>

    <script>
    function adminChatApp() {
        return {
            messages: @json($messages),
            newMessage: '',
            sending: false,
            conversationId: {{ $conversation->id }},
            
            init() {
                this.scrollToBottom();
                setInterval(() => this.fetchMessages(), 3000);
            },
            
            async fetchMessages() {
                try {
                    const response = await fetch(`/admin-chat/${this.conversationId}/messages`);
                    const data = await response.json();
                    if (data.messages && data.messages.length !== this.messages.length) {
                        this.messages = data.messages;
                        this.$nextTick(() => this.scrollToBottom());
                    }
                } catch (e) {
                    console.error('Error fetching messages:', e);
                }
            },
            
            async sendMessage() {
                if (!this.newMessage.trim() || this.sending) return;
                
                this.sending = true;
                const messageText = this.newMessage;
                
                try {
                    const response = await fetch(`/admin-chat/${this.conversationId}/send`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ message: messageText })
                    });
                    
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    
                    const data = await response.json();
                    console.log('Response:', data);
                    
                    if (data.success && data.message) {
                        this.messages.push(data.message);
                        this.newMessage = '';
                        this.$nextTick(() => this.scrollToBottom());
                    }
                } catch (e) {
                    console.error('Error sending message:', e);
                    alert('Error sending message. Please try again.');
                }
                this.sending = false;
            },
            
            scrollToBottom() {
                const container = document.getElementById('messagesContainer');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            },
            
            formatTime(timestamp) {
                const date = new Date(timestamp);
                return date.toLocaleString();
            }
        }
    }
    </script>
</body>
</html>
