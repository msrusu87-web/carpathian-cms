@extends('client.layout')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('My Orders') }}</h1>
    <p class="text-gray-600 dark:text-gray-400 mt-2">{{ __('View and track all your orders.') }}</p>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($orders as $order)
            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="mb-4 md:mb-0">
                        <div class="flex items-center space-x-3">
                            <h3 class="font-semibold text-gray-900 dark:text-white text-lg">#{{ $order->order_number }}</h3>
                            <span class="px-3 py-1 text-xs font-medium rounded-full 
                                @switch($order->order_status)
                                    @case('pending') bg-yellow-100 text-yellow-800 @break
                                    @case('processing') bg-blue-100 text-blue-800 @break
                                    @case('shipped') bg-purple-100 text-purple-800 @break
                                    @case('delivered') bg-green-100 text-green-800 @break
                                    @case('cancelled') bg-red-100 text-red-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch">
                                {{ ucfirst($order->order_status) }}
                            </span>
                            <span class="px-3 py-1 text-xs font-medium rounded-full 
                                @if($order->payment_status == 'paid') bg-green-100 text-green-800
                                @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ __('Payment') }}: {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ __('Placed on') }} {{ $order->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">€{{ number_format($order->total, 2) }}</span>
                        <a href="{{ route('client.order.show', $order->id) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md transition">
                            {{ __('View Details') }}
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">{{ __('No orders yet') }}</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">{{ __('Start shopping to see your orders here.') }}</p>
                <a href="{{ route('shop.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md">
                    {{ __('Browse Shop') }}
                </a>
            </div>
        @endforelse
    </div>
    
    @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
