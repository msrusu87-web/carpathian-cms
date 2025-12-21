<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.pre_sale_inquiry') }} - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    @include('partials.navigation')

    <main class="container mx-auto px-4 py-12">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center mb-6">
                    <a href="{{ route('shop.show', $product->slug) }}" class="text-blue-600 hover:underline">
                        <i class="fas fa-arrow-left mr-2"></i> {{ __('messages.back_to_product') }}
                    </a>
                </div>

                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ __('messages.pre_sale_inquiry') }}</h1>
                <p class="text-gray-600 mb-6">{{ __('messages.pre_sale_subtitle') }}</p>

                <div class="bg-blue-50 border-l-4 border-blue-600 p-4 mb-6">
                    <p class="font-semibold text-blue-900">{{ __('messages.product') }}: {{ $product->name }}</p>
                </div>

                <form action="{{ route('pre-sale.submit', $product->id) }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.full_name') }} *</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.email') }} *</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.phone') }}</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.your_message') }} *</label>
                        <textarea name="message" rows="6" required
                                  class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('message') }}</textarea>
                        @error('message')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    @guest
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                        <label class="flex items-center mb-4">
                            <input type="checkbox" name="create_account" value="1" id="create-account-toggle" class="mr-3 w-5 h-5">
                            <span class="font-semibold">{{ __('messages.create_account_optional') }}</span>
                        </label>

                        <div id="account-fields" style="display:none;">
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.password') }}</label>
                                <input type="password" name="password" class="w-full px-4 py-3 border rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.password_confirmation') }}</label>
                                <input type="password" name="password_confirmation" class="w-full px-4 py-3 border rounded-lg">
                            </div>
                        </div>
                    </div>
                    @endguest

                    <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-8 py-4 rounded-lg hover:from-green-700 hover:to-green-800 font-semibold text-lg">
                        <i class="fas fa-paper-plane mr-2"></i>{{ __('messages.submit_inquiry') }}
                    </button>
                </form>
            </div>
        </div>
    </main>

    @php
        $footerWidget = \App\Models\Widget::where('type', 'footer')->where('status', 'active')->first();
    @endphp
    @if($footerWidget)
        @include('widgets.footer', ['widget' => $footerWidget])
    @endif

    <script>
    document.getElementById('create-account-toggle')?.addEventListener('change', function() {
        document.getElementById('account-fields').style.display = this.checked ? 'block' : 'none';
    });
    </script>
</body>
</html>
