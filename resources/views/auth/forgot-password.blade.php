<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.forgot_password') }} - {{ config('app.name', 'Carphatian') }}</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 25%, #4c1d95 50%, #6b21a8 75%, #86198f 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px -10px rgba(139, 92, 246, 0.5);
        }
        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px -10px rgba(139, 92, 246, 0.4);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen gradient-bg flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-flex items-center space-x-3">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <span class="text-white text-2xl font-bold">C</span>
                    </div>
                    <span class="text-3xl font-bold text-white">Carphatian</span>
                </a>
            </div>

            <div class="glass-card rounded-3xl p-8 shadow-2xl">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-key text-2xl text-purple-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('messages.forgot_password') }}</h2>
                    <p class="text-gray-500 mt-2 text-sm">{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}</p>
                </div>

                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-purple-500"></i>{{ __('messages.email') }}
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               class="input-field w-full px-5 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800 transition-all"
                               placeholder="{{ __('messages.enter_your_email') }}">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="btn-gradient w-full py-4 px-6 text-white font-semibold rounded-xl shadow-lg flex items-center justify-center space-x-2">
                        <i class="fas fa-paper-plane"></i>
                        <span>{{ __('Email Password Reset Link') }}</span>
                    </button>
                </form>

                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-800 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>{{ __('messages.back') }} {{ __('messages.login') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
