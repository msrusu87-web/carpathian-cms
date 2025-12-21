<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.register') }} - {{ config('app.name', 'Carphatian') }}</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 25%, #4c1d95 50%, #6b21a8 75%, #86198f 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .input-field {
            transition: all 0.3s ease;
        }
        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px -10px rgba(139, 92, 246, 0.4);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px -10px rgba(139, 92, 246, 0.5);
        }
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
            pointer-events: none;
        }
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            animation: float 20s infinite;
        }
        .shape-1 { width: 500px; height: 500px; top: -150px; right: -150px; animation-delay: 0s; }
        .shape-2 { width: 350px; height: 350px; bottom: -100px; left: -100px; animation-delay: 7s; }
        .shape-3 { width: 250px; height: 250px; top: 40%; right: 5%; animation-delay: 14s; }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-40px) rotate(180deg); }
        }
        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen gradient-bg flex items-center justify-center p-4 relative overflow-hidden">
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>

        <div class="w-full max-w-md relative z-10">
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-flex items-center space-x-3">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <span class="text-white text-2xl font-bold">C</span>
                    </div>
                    <span class="text-3xl font-bold text-white">Carphatian</span>
                </a>
                <p class="text-white/70 mt-3">{{ __('messages.create_account') }}</p>
            </div>

            <div class="glass-card rounded-3xl p-8 shadow-2xl">
                <h2 class="text-2xl font-bold text-gray-800 mb-1">{{ __('messages.get_started') }}</h2>
                <p class="text-gray-500 mb-6 text-sm">{{ __('messages.register_benefits') }}</p>

                <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ password: '' }">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-purple-500"></i>{{ __('messages.full_name') }}
                        </label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="input-field w-full px-5 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800" placeholder="{{ __('messages.enter_your_name') }}">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-purple-500"></i>{{ __('messages.email') }}
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="input-field w-full px-5 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800" placeholder="{{ __('messages.enter_your_email') }}">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-purple-500"></i>{{ __('messages.password') }}
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" id="password" name="password" x-model="password" required autocomplete="new-password" class="input-field w-full px-5 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800 pr-12" placeholder="••••••••">
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-purple-500 transition-colors">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-2 flex space-x-1" x-show="password">
                        <div class="password-strength flex-1" :class="password.length >= 1 ? (password.length >= 8 ? 'bg-green-500' : 'bg-yellow-500') : 'bg-gray-200'"></div>
                        <div class="password-strength flex-1" :class="password.length >= 4 ? (password.length >= 8 ? 'bg-green-500' : 'bg-yellow-500') : 'bg-gray-200'"></div>
                        <div class="password-strength flex-1" :class="password.length >= 8 ? 'bg-green-500' : 'bg-gray-200'"></div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-purple-500"></i>{{ __('messages.confirm_password') }}
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" class="input-field w-full px-5 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800 pr-12" placeholder="••••••••">
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-purple-500 transition-colors">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-start space-x-3">
                        <input type="checkbox" id="terms" required class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500 cursor-pointer mt-0.5">
                        <label for="terms" class="text-sm text-gray-600 cursor-pointer">{{ __('messages.terms_agreement') }}</label>
                    </div>

                    <button type="submit" class="btn-gradient w-full py-4 px-6 text-white font-semibold rounded-xl shadow-lg flex items-center justify-center space-x-2">
                        <i class="fas fa-user-plus"></i>
                        <span>{{ __('messages.create_account') }}</span>
                    </button>
                </form>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">{{ __('messages.or') }}</span>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-gray-600 text-sm">{{ __('messages.have_account') }}</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center mt-3 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-300 hover:shadow-md">
                        <i class="fas fa-sign-in-alt mr-2 text-purple-500"></i>
                        {{ __('messages.log_in') }}
                    </a>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="{{ url('/') }}" class="text-white/80 hover:text-white transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('messages.back_to_home') }}
                </a>
            </div>
        </div>
    </div>
</body>
</html>
