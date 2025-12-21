<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Chat Support') }} - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-gray-100">
    <div class="min-h-full">
        <!-- Navigation -->
        <nav class="bg-purple-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <a href="/admin" class="flex items-center space-x-2">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span class="text-white font-bold text-lg">Support Chat</span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="/" target="_blank" class="text-purple-200 hover:text-white text-sm">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            {{ __('View Website') }}
                        </a>
                        <a href="/admin" class="text-purple-200 hover:text-white text-sm">
                            ← {{ __('Back to Admin') }}
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Support Conversations') }}</h1>
                <p class="text-gray-600">{{ __('Manage and respond to customer support requests.') }}</p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-500">{{ __('Open') }}</p>
                    <p class="text-2xl font-bold text-green-600">{{ $conversations->where('status', 'open')->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-500">{{ __('Pending') }}</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $conversations->where('status', 'pending')->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-500">{{ __('Closed') }}</p>
                    <p class="text-2xl font-bold text-gray-600">{{ $conversations->where('status', 'closed')->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-500">{{ __('Total') }}</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $conversations->count() }}</p>
                </div>
            </div>

            <!-- Conversations List -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="divide-y divide-gray-200">
                    @forelse($conversations as $conv)
                        <a href="{{ route('admin.chat.show', $conv->id) }}" class="block hover:bg-gray-50 transition">
                            <div class="px-6 py-4 flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                            <span class="text-purple-600 font-medium">{{ strtoupper(substr($conv->participant_name, 0, 2)) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <p class="font-medium text-gray-900">{{ $conv->participant_name }}</p>
                                            @if($conv->unread_count > 0)
                                                <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $conv->unread_count }}</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-500">{{ $conv->participant_email }}</p>
                                        <p class="text-sm text-gray-700 mt-1">{{ $conv->subject ?? 'Support Request' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $conv->status == 'open' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $conv->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $conv->status == 'closed' ? 'bg-gray-100 text-gray-800' : '' }}
                                    ">
                                        {{ ucfirst($conv->status) }}
                                    </span>
                                    <p class="text-xs text-gray-400 mt-1">{{ $conv->last_message_at?->diffForHumans() ?? $conv->created_at->diffForHumans() }}</p>
                                    <span class="text-xs px-2 py-0.5 rounded {{ $conv->type == 'support' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">
                                        {{ $conv->type == 'support' ? 'Support' : 'Guest' }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="mt-4 text-gray-500">{{ __('No conversations yet.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</body>
</html>
