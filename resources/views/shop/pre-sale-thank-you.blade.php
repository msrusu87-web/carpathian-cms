<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.inquiry_submitted') }} - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    @include('partials.navigation')

    <main class="container mx-auto px-4 py-12">
        <div class="max-w-3xl mx-auto text-center">
            <div class="bg-white rounded-xl shadow-lg p-12">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check-circle text-green-600 text-5xl"></i>
                </div>

                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ __('messages.inquiry_submitted') }}</h1>
                <p class="text-lg text-gray-600 mb-6">{{ __('messages.we_will_contact_you') }}</p>

                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 mb-8">
                    <p class="text-gray-700 mb-2">{{ __('messages.inquiry_id') }}:</p>
                    <p class="text-3xl font-bold text-blue-600">#{{ $inquiry->id }}</p>
                </div>

                <div class="text-left bg-gray-50 rounded-lg p-6 mb-8">
                    <h3 class="font-bold text-gray-800 mb-4">{{ __('messages.your_inquiry') }}:</h3>
                    <p class="text-gray-700 mb-2"><strong>{{ __('messages.product') }}:</strong> {{ $inquiry->product->name }}</p>
                    <p class="text-gray-700 mb-2"><strong>{{ __('messages.email') }}:</strong> {{ $inquiry->customer_email }}</p>
                    <p class="text-gray-700"><strong>{{ __('messages.status') }}:</strong> <span class="text-yellow-600 font-semibold">{{ ucfirst($inquiry->status) }}</span></p>
                </div>

                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <a href="{{ route('shop.show', $inquiry->product->slug) }}" class="bg-blue-600 text-white px-8 py-4 rounded-lg hover:bg-blue-700 font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>{{ __('messages.back_to_product') }}
                    </a>
                    <a href="{{ route('shop.index') }}" class="bg-gray-200 text-gray-800 px-8 py-4 rounded-lg hover:bg-gray-300 font-semibold">
                        <i class="fas fa-shopping-bag mr-2"></i>{{ __('messages.continue_shopping') }}
                    </a>
                </div>
            </div>
        </div>
    </main>
    
    @php
        $footerWidget = \App\Models\Widget::where('type', 'footer')->where('status', 'active')->first();
    @endphp
    @if($footerWidget)
        @include('widgets.footer', ['widget' => $footerWidget])
    @endif
</body>
</html>
