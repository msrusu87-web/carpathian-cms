<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order {{ $order->order_number }} - Carphatian CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    @include('partials.navigation')

    <!-- Page Header -->
    <header class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold">Order Details</h1>
                    <p class="text-lg mt-2">Order #{{ $order->order_number }}</p>
                </div>
                <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-6 py-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Order Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-bold text-gray-800">Order Items</h2>
                    </div>
                    <div class="p-6">
                        @foreach($order->orderItems as $item)
                        <div class="flex items-center justify-between border-b border-gray-200 pb-4 mb-4 last:border-b-0 last:mb-0 last:pb-0">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $item->product_name }}</h3>
                                <p class="text-sm text-gray-500">SKU: {{ $item->product_sku }}</p>
                                <p class="text-sm text-gray-600 mt-1">Quantity: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-800">${{ number_format($item->total, 2) }}</p>
                                <p class="text-sm text-gray-500">${{ number_format($item->price, 2) }} each</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-bold text-gray-800">Shipping Address</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-800 font-semibold">{{ $order->customer_name }}</p>
                        <p class="text-gray-600 mt-2">{{ $order->shipping_address }}</p>
                        <p class="text-gray-600 mt-1">
                            <i class="fas fa-phone mr-2"></i>{{ $order->customer_phone }}
                        </p>
                        <p class="text-gray-600 mt-1">
                            <i class="fas fa-envelope mr-2"></i>{{ $order->customer_email }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <!-- Order Status -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-bold text-gray-800">Order Status</h2>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 mb-1">Order Status</p>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                @if($order->order_status == 'completed') bg-green-100 text-green-800
                                @elseif($order->order_status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->order_status == 'processing') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </div>
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 mb-1">Payment Status</p>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                @if($order->payment_status == 'completed') bg-green-100 text-green-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Order Date</p>
                            <p class="text-gray-800 font-semibold">{{ $order->created_at->format('F d, Y') }}</p>
                            <p class="text-sm text-gray-500">{{ $order->created_at->format('h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-bold text-gray-800">Payment Summary</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between mb-3">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-800 font-semibold">${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        @if($order->tax > 0)
                        <div class="flex justify-between mb-3">
                            <span class="text-gray-600">Tax</span>
                            <span class="text-gray-800 font-semibold">${{ number_format($order->tax, 2) }}</span>
                        </div>
                        @endif
                        @if($order->shipping > 0)
                        <div class="flex justify-between mb-3">
                            <span class="text-gray-600">Shipping</span>
                            <span class="text-gray-800 font-semibold">${{ number_format($order->shipping, 2) }}</span>
                        </div>
                        @endif
                        @if(isset($order->payment_details['fee']) && $order->payment_details['fee'] > 0)
                        <div class="flex justify-between mb-3">
                            <span class="text-gray-600">Payment Fee</span>
                            <span class="text-gray-800 font-semibold">${{ number_format($order->payment_details['fee'], 2) }}</span>
                        </div>
                        @endif
                        <div class="border-t pt-3 mt-3">
                            <div class="flex justify-between">
                                <span class="text-lg font-bold text-gray-800">Total</span>
                                <span class="text-2xl font-bold text-blue-600">${{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-1">Payment Method</p>
                            <p class="text-gray-800 font-semibold">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                            @if(isset($order->payment_details['gateway_name']))
                            <p class="text-sm text-gray-500 mt-1">{{ $order->payment_details['gateway_name'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
