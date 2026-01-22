@extends('client.layout')

@section('content')
<div x-data="chatApp()" x-init="init()" class="flex flex-col h-[calc(100vh-200px)]">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-t-lg shadow px-6 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('client.support') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="font-semibold text-gray-900 dark:text-white">{{ $conversation->subject ?? 'Support Chat' }}</h2>
                <p class="text-sm text-gray-500">
                    <span class="inline-block w-2 h-2 rounded-full {{ $conversation->status == 'open' ? 'bg-green-500' : 'bg-gray-400' }} mr-1"></span>
                    {{ ucfirst($conversation->status) }}
                </p>
            </div>
        </div>
    </div>
    
    <!-- Messages -->
    <div id="messagesContainer" class="flex-1 bg-gray-50 dark:bg-gray-900 overflow-y-auto p-6 space-y-4">
        <template x-for="msg in messages" :key="msg.id">
            <div :class="msg.is_admin ? 'flex justify-start' : 'flex justify-end'">
                <div :class="msg.is_admin ? 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700' : 'bg-purple-600 text-white'" class="max-w-xs md:max-w-md lg:max-w-lg rounded-lg px-4 py-3 shadow-sm">
                    <div class="flex items-center space-x-2 mb-1">
                        <span class="text-xs font-medium" :class="msg.is_admin ? 'text-purple-600' : 'text-purple-200'" x-text="msg.sender_name"></span>
                    </div>
                    <p class="text-sm" :class="msg.is_admin ? 'text-gray-700 dark:text-gray-300' : 'text-white'" x-text="msg.message"></p>
                    <p class="text-xs mt-1" :class="msg.is_admin ? 'text-gray-400' : 'text-purple-200'" x-text="formatTime(msg.created_at)"></p>
                </div>
            </div>
        </template>
    </div>
    
    <!-- Input -->
    @if($conversation->status == 'open')
    <div class="bg-white dark:bg-gray-800 rounded-b-lg shadow px-6 py-4">
        <form @submit.prevent="sendMessage()" class="flex items-center space-x-4">
            <input 
                type="text" 
                x-model="newMessage"
                placeholder="{{ __('Type your message...') }}"
                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-purple-500 focus:border-purple-500"
                :disabled="sending"
            >
            <button 
                type="submit" 
                class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition disabled:opacity-50"
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
    @else
    <div class="bg-gray-100 dark:bg-gray-700 rounded-b-lg px-6 py-4 text-center text-gray-500 dark:text-gray-400">
        {{ __('This conversation is closed.') }}
    </div>
    @endif
</div>

<script>
function chatApp() {
    return {
        messages: @json($messages ?? []),
        newMessage: '',
        sending: false,
        conversationId: {{ $conversation->id }},
        
        init() {
            this.scrollToBottom();
            setInterval(() => this.fetchMessages(), 3000);
        },
        
        async fetchMessages() {
            try {
                const response = await fetch(`/client/chat/${this.conversationId}/messages`);
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
            try {
                const response = await fetch(`/client/chat/${this.conversationId}/send`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message: this.newMessage })
                });
                
                const data = await response.json();
                if (data.success) {
                    this.messages.push(data.message);
                    this.newMessage = '';
                    this.$nextTick(() => this.scrollToBottom());
                }
            } catch (e) {
                console.error('Error sending message:', e);
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
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
    }
}
</script>
@endsection
