@extends('client.layout')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <a href="{{ route('client.orders') }}" class="text-purple-600 hover:text-purple-800 text-sm mb-2 inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            {{ __('Back to Orders') }}
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Order') }} #{{ $order->order_number }}</h1>
    </div>
    <div class="flex items-center space-x-2">
        <span class="px-4 py-2 text-sm font-medium rounded-full 
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
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Order Items -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('Order Items') }}</h2>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($order->items as $item)
                    <div class="p-6 flex items-center space-x-4">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900 dark:text-white">{{ $item->product_name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">SKU: {{ $item->product_sku }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Qty') }}</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Price') }}</p>
                            <p class="font-medium text-gray-900 dark:text-white">€{{ number_format($item->price, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total') }}</p>
                            <p class="font-semibold text-gray-900 dark:text-white">€{{ number_format($item->total, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-500 dark:text-gray-400">{{ __('Subtotal') }}</span>
                    <span class="text-gray-900 dark:text-white">€{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->tax > 0)
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-500 dark:text-gray-400">{{ __('Tax') }}</span>
                    <span class="text-gray-900 dark:text-white">€{{ number_format($order->tax, 2) }}</span>
                </div>
                @endif
                @if($order->shipping > 0)
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-500 dark:text-gray-400">{{ __('Shipping') }}</span>
                    <span class="text-gray-900 dark:text-white">€{{ number_format($order->shipping, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200 dark:border-gray-600">
                    <span class="text-gray-900 dark:text-white">{{ __('Total') }}</span>
                    <span class="text-purple-600">€{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Info Sidebar -->
    <div class="space-y-6">
        <!-- Order Status Timeline -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Order Status') }}</h3>
            <div class="space-y-4">
                @php
                    $statuses = ['pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered'];
                    $currentStatus = $order->order_status;
                    $statusReached = true;
                @endphp
                @foreach($statuses as $key => $label)
                    @php
                        $isActive = $currentStatus === $key;
                        $isPast = $statusReached && !$isActive;
                        if ($isActive) $statusReached = false;
                    @endphp
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $isPast || $isActive ? 'bg-purple-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500' }}">
                            @if($isPast)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @else
                                <span class="text-xs">{{ $loop->iteration }}</span>
                            @endif
                        </div>
                        <span class="ml-3 {{ $isActive ? 'font-semibold text-purple-600' : ($isPast ? 'text-gray-900 dark:text-white' : 'text-gray-500') }}">{{ __($label) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Payment Info -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Payment Information') }}</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">{{ __('Method') }}</span>
                    <span class="text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">{{ __('Status') }}</span>
                    <span class="px-2 py-1 text-xs rounded-full {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Shipping Address -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Shipping Address') }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $order->shipping_address }}</p>
        </div>

        <!-- Need Help -->
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-2">{{ __('Need Help?') }}</h3>
            <p class="text-sm text-purple-700 dark:text-purple-300 mb-4">{{ __('If you have questions about this order, contact our support.') }}</p>
            <a href="{{ route('client.support') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md">
                {{ __('Contact Support') }}
            </a>
        </div>
    </div>
</div>
@endsection
