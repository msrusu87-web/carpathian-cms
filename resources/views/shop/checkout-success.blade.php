<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.seo-head', [
        'title' => __('messages.order_success') . ' - Carphatian CMS',
        'description' => 'Comanda ta a fost plasată cu succes.',
        'keywords' => 'comandă, success, Carphatian CMS'
    ])
    <meta name="robots" content="noindex, nofollow">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    @include('partials.navigation')

    <main class="container mx-auto px-4 py-12">
        <div class="max-w-3xl mx-auto">
            <!-- Success Message -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-6 text-center">
                <div class="mb-6">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-green-600 text-5xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ __('messages.order_placed_successfully') }}</h1>
                    <p class="text-lg text-gray-600">{{ __('messages.thank_you_order') }}</p>
                </div>

                @if($order)
                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 mb-6">
                    <p class="text-gray-700 mb-2">{{ __('messages.order_number') }}:</p>
                    <p class="text-3xl font-bold text-blue-600">#{{ $order->id }}</p>
                </div>

                <div class="text-left bg-gray-50 rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                        {{ __('messages.order_details') }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">{{ __('messages.customer_name') }}:</p>
                            <p class="font-semibold text-gray-800">{{ $order->customer_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">{{ __('messages.email') }}:</p>
                            <p class="font-semibold text-gray-800">{{ $order->customer_email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">{{ __('messages.phone') }}:</p>
                            <p class="font-semibold text-gray-800">{{ $order->customer_phone }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">{{ __('messages.total') }}:</p>
                            <p class="font-semibold text-blue-600 text-lg">${{ number_format($order->total, 2) }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Email Confirmation Notice with Spam Warning -->
            <!-- Bank Transfer Payment Instructions -->
            @if($order && $order->payment_method === 'bank_transfer')
            @php
                $paymentDetails = json_decode($order->payment_details, true);
                $gateway = \App\Models\PaymentGateway::find($paymentDetails['gateway_id'] ?? null);
                $credentials = $gateway ? $gateway->credentials : null;
            @endphp
            
            @if($credentials && isset($credentials['iban']))
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-300 rounded-xl shadow-lg p-8 mb-6">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-university text-white text-3xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ __('messages.bank_transfer_details') }}</h2>
                        <p class="text-gray-600">{{ __('messages.complete_payment_instructions') }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">{{ __('messages.account_holder') }}</p>
                            <p class="text-lg font-bold text-gray-800">{{ $credentials['account_holder'] ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">{{ __('messages.bank_name') }}</p>
                            <p class="text-lg font-bold text-gray-800">{{ $credentials['bank_name'] ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="bg-blue-100 border-2 border-blue-400 p-6 rounded-lg">
                        <p class="text-sm text-gray-700 font-semibold mb-2">{{ __('messages.iban') }}:</p>
                        <div class="flex items-center justify-between bg-white p-4 rounded border-2 border-blue-300">
                            <p class="text-2xl font-mono font-bold text-blue-900" id="iban-text">{{ $credentials['iban'] ?? 'N/A' }}</p>
                            <button onclick="copyIBAN()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                                <i class="fas fa-copy mr-2"></i>{{ __('messages.copy') }}
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">{{ __('messages.swift_bic') }}</p>
                            <p class="font-bold text-gray-800">{{ $credentials['swift_bic'] ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">{{ __('messages.currency') }}</p>
                            <p class="font-bold text-gray-800">{{ $credentials['account_currency'] ?? 'RON' }}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">{{ __('messages.amount') }}</p>
                            <p class="text-xl font-bold text-blue-600">${{ number_format($order->total, 2) }}</p>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-4">
                        <p class="font-bold text-yellow-800 mb-2">
                            <i class="fas fa-exclamation-circle mr-2"></i>{{ __('messages.important') }}:
                        </p>
                        <p class="text-sm text-yellow-800">
                            {{ __('messages.payment_reference') }}: <strong class="text-lg">Order #{{ $order->id }}</strong>
                        </p>
                        <p class="text-sm text-yellow-800 mt-2">
                            {{ __('messages.payment_deadline_notice') }}
                        </p>
                    </div>
                </div>
            </div>

            <script>
            function copyIBAN() {
                const iban = document.getElementById('iban-text').textContent;
                navigator.clipboard.writeText(iban).then(() => {
                    alert('{{ __('messages.iban_copied') }}');
                });
            }
            </script>
            @endif
            @endif

            <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-6 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-envelope text-yellow-600 text-3xl mr-4"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            {{ __('messages.confirmation_email_sent') }}
                        </h3>
                        <p class="text-gray-700 mb-3">
                            {{ __('messages.email_sent_to') }} <strong>{{ $order->customer_email ?? 'your email' }}</strong>
                        </p>
                        
                        <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-4 mt-4">
                            <p class="font-bold text-yellow-800 mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                {{ __('messages.check_spam') }}
                            </p>
                            <p class="text-sm text-yellow-800 mb-2">
                                {{ __('messages.spam_folder_notice') }}
                            </p>
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-lightbulb mr-2"></i>
                                <strong>{{ __('messages.tip') }}:</strong> {{ __('messages.add_to_contacts') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- What's Next -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-forward mr-2 text-blue-600"></i>
                    {{ __('messages.whats_next') }}
                </h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                            <span class="text-blue-600 font-bold">1</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ __('messages.check_email') }}</p>
                            <p class="text-sm text-gray-600">{{ __('messages.check_email_desc') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                            <span class="text-blue-600 font-bold">2</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ __('messages.order_processing') }}</p>
                            <p class="text-sm text-gray-600">{{ __('messages.order_processing_desc') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                            <span class="text-blue-600 font-bold">3</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ __('messages.delivery_update') }}</p>
                            <p class="text-sm text-gray-600">{{ __('messages.delivery_update_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a href="{{ url('/') }}" 
                   class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-4 rounded-lg hover:from-blue-700 hover:to-blue-800 transition text-center font-semibold">
                    <i class="fas fa-home mr-2"></i>
                    {{ __('messages.back_to_home') }}
                </a>
                <a href="{{ route('shop.index') }}" 
                   class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-8 py-4 rounded-lg hover:from-purple-700 hover:to-purple-800 transition text-center font-semibold">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    {{ __('messages.continue_shopping') }}
                </a>
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
