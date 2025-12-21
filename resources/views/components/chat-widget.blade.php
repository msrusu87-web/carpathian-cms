<!-- Chat Widget for Homepage -->
<div x-data="chatWidget()" x-cloak class="fixed bottom-6 right-6 z-50">
    <!-- Chat Button -->
    <button 
        @click="toggleChat()"
        class="w-14 h-14 bg-purple-600 hover:bg-purple-700 text-white rounded-full shadow-lg flex items-center justify-center transition-transform hover:scale-105"
        :class="{ 'rotate-0': !isOpen, 'rotate-180': isOpen }"
    >
        <svg x-show="!isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        <svg x-show="isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <!-- Chat Window -->
    <div 
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute bottom-16 right-0 w-80 sm:w-96 bg-white rounded-lg shadow-2xl overflow-hidden"
    >
        <!-- Header -->
        <div class="bg-purple-600 text-white px-4 py-3">
            <h3 class="font-semibold">{{ __('Chat with Us') }}</h3>
            <p class="text-purple-200 text-sm">{{ __('Ask us anything about our products!') }}</p>
        </div>

        <!-- Registration Form (if not started) -->
        <div x-show="!conversationId && !hasSession" class="p-4">
            <form @submit.prevent="startConversation()">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Name') }} <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        x-model="guestName"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500"
                        placeholder="{{ __('Your name') }}"
                    >
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }} <span class="text-red-500">*</span></label>
                    <input 
                        type="email" 
                        x-model="guestEmail"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500"
                        placeholder="{{ __('your@email.com') }}"
                    >
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Phone') }} <span class="text-gray-400">({{ __('optional') }})</span></label>
                    <input 
                        type="tel" 
                        x-model="guestPhone"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500"
                        placeholder="{{ __('+40 xxx xxx xxx') }}"
                    >
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Message') }} <span class="text-red-500">*</span></label>
                    <textarea 
                        x-model="initialMessage"
                        required
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500"
                        placeholder="{{ __('How can we help you?') }}"
                    ></textarea>
                </div>
                <button 
                    type="submit"
                    class="w-full py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-md transition"
                    :disabled="starting"
                >
                    <span x-show="!starting">{{ __('Start Chat') }}</span>
                    <span x-show="starting">{{ __('Starting...') }}</span>
                </button>
            </form>
        </div>

        <!-- Chat Messages (if conversation started) -->
        <div x-show="conversationId || hasSession" class="flex flex-col h-80">
            <div id="widgetMessages" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50">
                <template x-for="msg in messages" :key="msg.id">
                    <div :class="msg.is_admin ? 'flex justify-start' : 'flex justify-end'">
                        <div :class="msg.is_admin ? 'bg-white border' : 'bg-purple-600 text-white'" class="max-w-[80%] rounded-lg px-3 py-2 text-sm shadow-sm">
                            <p x-text="msg.message"></p>
                            <p class="text-xs mt-1 opacity-70" x-text="formatTime(msg.created_at)"></p>
                        </div>
                    </div>
                </template>
            </div>
            
            <!-- Message Input -->
            <div class="p-3 bg-white border-t">
                <form @submit.prevent="sendMessage()" class="flex space-x-2">
                    <input 
                        type="text" 
                        x-model="newMessage"
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500"
                        placeholder="{{ __('Type a message...') }}"
                        :disabled="sending"
                    >
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition disabled:opacity-50"
                        :disabled="sending || !newMessage.trim()"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function chatWidget() {
    return {
        isOpen: false,
        conversationId: null,
        hasSession: false,
        messages: [],
        newMessage: '',
        guestName: '',
        guestEmail: '',
        guestPhone: '',
        initialMessage: '',
        sending: false,
        starting: false,
        pollInterval: null,

        init() {
            // Check for existing session
            const sessionId = localStorage.getItem('chat_conversation_id');
            if (sessionId) {
                this.conversationId = sessionId;
                this.hasSession = true;
                this.checkConversation();
            }
        },

        toggleChat() {
            this.isOpen = !this.isOpen;
            if (this.isOpen && this.conversationId) {
                this.startPolling();
                this.$nextTick(() => this.scrollToBottom());
            } else {
                this.stopPolling();
            }
        },

        async checkConversation() {
            try {
                const response = await fetch(`/chat/check?conversation_id=${this.conversationId}`);
                const data = await response.json();
                if (data.exists) {
                    this.messages = data.messages || [];
                    this.hasSession = true;
                } else {
                    localStorage.removeItem('chat_conversation_id');
                    this.conversationId = null;
                    this.hasSession = false;
                }
            } catch (e) {
                console.error('Error checking conversation:', e);
            }
        },

        async startConversation() {
            if (!this.guestName || !this.guestEmail || !this.initialMessage) return;
            
            this.starting = true;
            try {
                const response = await fetch('/chat/start', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: this.guestName,
                        email: this.guestEmail,
                        phone: this.guestPhone,
                        message: this.initialMessage
                    })
                });
                
                const data = await response.json();
                if (data.success) {
                    this.conversationId = data.conversation_id;
                    localStorage.setItem('chat_conversation_id', data.conversation_id);
                    this.messages = [data.message];
                    this.hasSession = true;
                    this.startPolling();
                }
            } catch (e) {
                console.error('Error starting conversation:', e);
            }
            this.starting = false;
        },

        async sendMessage() {
            if (!this.newMessage.trim() || this.sending) return;
            
            this.sending = true;
            try {
                const response = await fetch('/chat/messages', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        conversation_id: this.conversationId,
                        message: this.newMessage
                    })
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

        async fetchMessages() {
            if (!this.conversationId) return;
            
            try {
                const response = await fetch(`/chat/messages?conversation_id=${this.conversationId}`);
                const data = await response.json();
                if (data.messages && data.messages.length !== this.messages.length) {
                    this.messages = data.messages;
                    this.$nextTick(() => this.scrollToBottom());
                }
            } catch (e) {
                console.error('Error fetching messages:', e);
            }
        },

        startPolling() {
            if (this.pollInterval) return;
            this.pollInterval = setInterval(() => this.fetchMessages(), 3000);
        },

        stopPolling() {
            if (this.pollInterval) {
                clearInterval(this.pollInterval);
                this.pollInterval = null;
            }
        },

        scrollToBottom() {
            const container = document.getElementById('widgetMessages');
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

<style>
    [x-cloak] { display: none !important; }
</style>
