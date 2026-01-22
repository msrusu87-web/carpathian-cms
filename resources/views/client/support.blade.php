@extends('client.layout')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Support') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">{{ __('Get help from our team.') }}</p>
    </div>
    <button onclick="document.getElementById('newConversationModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-md">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        {{ __('New Conversation') }}
    </button>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($conversations as $conv)
            <a href="{{ route('client.chat.show', $conv->id) }}" class="block p-6 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $conv->subject ?? 'Support Request' }}</h3>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $conv->status == 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($conv->status) }}
                            </span>
                        </div>
                        @if($conv->messages->first())
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 truncate">
                                {{ Str::limit($conv->messages->first()->message, 100) }}
                            </p>
                        @endif
                        <p class="text-xs text-gray-400 mt-2">
                            {{ $conv->last_message_at?->diffForHumans() ?? $conv->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        @empty
            <div class="p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">{{ __('No conversations yet') }}</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">{{ __('Start a new conversation to get help.') }}</p>
            </div>
        @endforelse
    </div>
</div>

<!-- New Conversation Modal -->
<div id="newConversationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('New Conversation') }}</h3>
            <button onclick="document.getElementById('newConversationModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('client.chat.new') }}" method="POST" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Subject') }}</label>
                <input type="text" name="subject" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-purple-500 focus:border-purple-500" placeholder="{{ __('What do you need help with?') }}">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Message') }}</label>
                <textarea name="message" rows="4" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-purple-500 focus:border-purple-500" placeholder="{{ __('Describe your issue...') }}"></textarea>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-md transition">
                {{ __('Start Conversation') }}
            </button>
        </form>
    </div>
</div>
@endsection
