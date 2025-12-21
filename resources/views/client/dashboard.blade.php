@extends('client.layout')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Welcome') }}, {{ Auth::user()->name }}!</h1>
    <p class="text-gray-600 dark:text-gray-400 mt-2">{{ __('Manage your orders and get support from here.') }}</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Orders') }}</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $orders->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Completed') }}</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $orders->where('order_status', 'delivered')->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Support Tickets') }}</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $conversations->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('Recent Orders') }}</h2>
        <a href="{{ route('client.orders') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">{{ __('View All') }} →</a>
    </div>
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($orders->take(5) as $order)
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">#{{ $order->order_number }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="px-3 py-1 text-xs font-medium rounded-full 
                        @switch($order->order_status)
                            @case('pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @break
                            @case('processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
                            @case('shipped') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 @break
                            @case('delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                            @case('cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @break
                            @default bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                        @endswitch">
                        {{ ucfirst($order->order_status) }}
                    </span>
                    <span class="font-semibold text-gray-900 dark:text-white">€{{ number_format($order->total, 2) }}</span>
                    <a href="{{ route('client.order.show', $order->id) }}" class="text-purple-600 hover:text-purple-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <p class="mt-2">{{ __('No orders yet.') }}</p>
                <a href="{{ route('shop.index') }}" class="mt-4 inline-block text-purple-600 hover:text-purple-800">{{ __('Browse our shop') }} →</a>
            </div>
        @endforelse
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg shadow p-6 text-white">
        <h3 class="text-xl font-semibold mb-2">{{ __('Need Help?') }}</h3>
        <p class="mb-4 text-purple-100">{{ __('Our support team is here to help you with any questions.') }}</p>
        <a href="{{ route('client.support') }}" class="inline-block bg-white text-purple-600 px-4 py-2 rounded-md font-medium hover:bg-purple-50 transition">
            {{ __('Contact Support') }}
        </a>
    </div>
    
    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-lg shadow p-6 text-white">
        <h3 class="text-xl font-semibold mb-2">{{ __('Explore Services') }}</h3>
        <p class="mb-4 text-blue-100">{{ __('Check out our latest web development and design services.') }}</p>
        <a href="{{ route('shop.index') }}" class="inline-block bg-white text-blue-600 px-4 py-2 rounded-md font-medium hover:bg-blue-50 transition">
            {{ __('View Shop') }}
        </a>
    </div>
</div>
@endsection
