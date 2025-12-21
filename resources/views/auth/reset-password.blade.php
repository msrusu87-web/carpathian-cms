<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Reset Password') }} - {{ config('app.name', 'Carphatian') }}</title>
    
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
                        <i class="fas fa-lock-open text-2xl text-purple-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('Reset Password') }}</h2>
                    <p class="text-gray-500 mt-2 text-sm">{{ __('Enter your new password below') }}</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-5" x-data="{ showPassword: false, showConfirmPassword: false }">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-purple-500"></i>{{ __('messages.email') }}
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email', $request->email) }}" 
                               required 
                               autofocus
                               class="input-field w-full px-5 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800 transition-all">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-purple-500"></i>{{ __('messages.password') }}
                        </label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" 
                                   id="password" 
                                   name="password" 
                                   required
                                   class="input-field w-full px-5 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800 transition-all pr-12"
                                   placeholder="••••••••">
                            <button type="button" 
                                    @click="showPassword = !showPassword" 
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-purple-500 transition-colors">
                                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-purple-500"></i>{{ __('messages.confirm_password') }}
                        </label>
                        <div class="relative">
                            <input :type="showConfirmPassword ? 'text' : 'password'" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required
                                   class="input-field w-full px-5 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800 transition-all pr-12"
                                   placeholder="••••••••">
                            <button type="button" 
                                    @click="showConfirmPassword = !showConfirmPassword" 
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-purple-500 transition-colors">
                                <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="btn-gradient w-full py-4 px-6 text-white font-semibold rounded-xl shadow-lg flex items-center justify-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>{{ __('Reset Password') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
