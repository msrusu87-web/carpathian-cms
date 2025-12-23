@extends('layouts.app')

@section('title', __('messages.pre_sale') . ' - ' . $product->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-purple-50/50 to-blue-50/50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Back Button -->
            <a href="{{ route('shop.show', $product->slug) }}" 
               class="inline-flex items-center text-gray-600 hover:text-purple-600 mb-8 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('messages.back_to_product') }}
            </a>

            <!-- Pre-Sale Form Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <span class="inline-block bg-gradient-to-r from-orange-500 to-orange-600 text-white text-xs font-bold px-3 py-1 rounded-full mb-4">
                        <i class="fas fa-clock mr-1"></i>{{ __('messages.coming_soon') }}
                    </span>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                        {{ __('messages.pre_sale_interest') }}
                    </h1>
                    <p class="text-gray-600">
                        {{ __('messages.pre_sale_description') }}
                    </p>
                </div>

                <!-- Product Info -->
                <div class="flex items-center bg-gray-50 rounded-xl p-4 mb-8">
                    @if($product->featured_image)
                        <img src="{{ asset('storage/' . $product->featured_image) }}" 
                             alt="{{ $product->name }}"
                             class="w-20 h-20 object-cover rounded-lg mr-4">
                    @endif
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $product->name }}</h3>
                        <p class="text-purple-600 font-bold">€{{ number_format($product->price, 2) }}</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('pre-sale.submit', $product->id) }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.full_name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                               placeholder="{{ __('messages.enter_your_name') }}"
                               value="{{ old('name') }}">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.email') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                               placeholder="{{ __('messages.enter_your_email') }}"
                               value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.phone') }}
                        </label>
                        <input type="tel" 
                               name="phone" 
                               id="phone"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                               placeholder="{{ __('messages.enter_your_phone') }}"
                               value="{{ old('phone') }}">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.message') }}
                        </label>
                        <textarea name="message" 
                                  id="message" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all resize-none"
                                  placeholder="{{ __('messages.optional_message') }}">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-4 rounded-lg font-semibold shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all">
                        <i class="fas fa-bell mr-2"></i>{{ __('messages.notify_me') }}
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-6">
                    {{ __('messages.pre_sale_privacy') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
