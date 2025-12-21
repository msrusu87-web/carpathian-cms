<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.verify_email') ?? 'Verify Email' }} - {{ config('app.name', 'Carphatian') }}</title>
    
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
        .pulse-ring {
            animation: pulse-ring 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.5; }
            100% { transform: scale(0.8); opacity: 1; }
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

            <div class="glass-card rounded-3xl p-8 shadow-2xl text-center">
                <!-- Animated Icon -->
                <div class="relative w-24 h-24 mx-auto mb-6">
                    <div class="absolute inset-0 bg-purple-200 rounded-full pulse-ring"></div>
                    <div class="relative w-24 h-24 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-envelope-open-text text-3xl text-white"></i>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ __('messages.check_email') ?? 'Verify Your Email' }}</h2>
                
                <p class="text-gray-600 mb-6">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </p>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif

                <div class="space-y-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" 
                                class="btn-gradient w-full py-4 px-6 text-white font-semibold rounded-xl shadow-lg flex items-center justify-center space-x-2">
                            <i class="fas fa-redo"></i>
                            <span>{{ __('Resend Verification Email') }}</span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full py-3 px-6 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-all">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            {{ __('messages.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
