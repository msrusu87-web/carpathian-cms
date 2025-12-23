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
        .gradient-bg { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 25%, #4c1d95 50%, #6b21a8 75%, #86198f 100%); }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .input-field { transition: all 0.3s ease; }
        .input-field:focus { transform: translateY(-2px); box-shadow: 0 10px 40px -10px rgba(139, 92, 246, 0.4); }
        .btn-gradient { background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%); transition: all 0.3s ease; }
        .btn-gradient:hover { transform: translateY(-2px); box-shadow: 0 10px 40px -10px rgba(139, 92, 246, 0.5); }
        .floating-shapes { position: absolute; width: 100%; height: 100%; overflow: hidden; z-index: 0; pointer-events: none; }
        .shape { position: absolute; border-radius: 50%; background: rgba(255, 255, 255, 0.05); animation: float 20s infinite; }
        .shape-1 { width: 500px; height: 500px; top: -150px; right: -150px; animation-delay: 0s; }
        .shape-2 { width: 350px; height: 350px; bottom: -100px; left: -100px; animation-delay: 7s; }
        .shape-3 { width: 250px; height: 250px; top: 40%; right: 5%; animation-delay: 14s; }
        @keyframes float { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-40px) rotate(180deg); } }
        .section-header { font-size: 0.875rem; font-weight: 600; color: #8B5CF6; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 1.5rem; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #8B5CF6; }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen gradient-bg flex items-center justify-center p-4 relative overflow-hidden">
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>

        <div class="w-full max-w-2xl relative z-10 my-8">
            <div class="text-center mb-6">
                <a href="{{ url('/') }}" class="inline-flex items-center space-x-3">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <span class="text-white text-2xl font-bold">C</span>
                    </div>
                    <span class="text-3xl font-bold text-white">Carphatian</span>
                </a>
                <p class="text-white/70 mt-3">{{ __('messages.create_account') }}</p>
            </div>

            <div class="glass-card rounded-3xl p-8 shadow-2xl max-h-[85vh] overflow-y-auto">
                <h2 class="text-2xl font-bold text-gray-800 mb-1">{{ __('messages.get_started') }}</h2>
                <p class="text-gray-500 mb-6 text-sm">Completați formularul pentru a crea cont</p>
                
                <form method="POST" action="{{ route('register') }}" class="space-y-4" x-data="{ password: '', showCompany: false }">
                    @csrf

                    <!-- Personal Information -->
                    <div class="section-header">
                        <i class="fas fa-user mr-2"></i>Informații Personale
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Prenume *
                            </label>
                            <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus class="input-field w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800" placeholder="Ion">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nume *
                            </label>
                            <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required class="input-field w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800" placeholder="Popescu">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-purple-500"></i>Email *
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="input-field w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800" placeholder="ion.popescu@email.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-phone mr-2 text-purple-500"></i>Telefon
                        </label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="input-field w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800" placeholder="+40 123 456 789">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Information (Optional) -->
                    <div class="section-header">
                        <i class="fas fa-building mr-2"></i>Informații Companie (Opțional)
                        <button type="button" @click="showCompany = !showCompany" class="float-right text-xs text-purple-600 hover:text-purple-700">
                            <span x-text="showCompany ? 'Ascunde' : 'Arată'"></span>
                        </button>
                    </div>

                    <div x-show="showCompany" x-collapse>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="company_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nume Companie
                                </label>
                                <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" class="input-field w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800" placeholder="SC Exemplu SRL">
                                @error('company_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="company_reg_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nr. Înregistrare (CUI)
                                </label>
                                <input id="company_reg_number" type="text" name="company_reg_number" value="{{ old('company_reg_number') }}" class="input-field w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800" placeholder="RO12345678">
                                @error('company_reg_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="vat_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nr. Înregistrare TVA (J12/...)
                            </label>
                            <input id="vat_number" type="text" name="vat_number" value="{{ old('vat_number') }}" cnput-field w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800" placeholder="J12/1234/2024">
                            @error('vat_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Billing Address -->
                    <div class="section-header">
                        <i class="fas fa-map-marker-alt mr-2"></i>Adresă Facturare
                    </div>

                    <div>
                        <label for="billing_address" class="block text-sm font-semibold text-gray-700 mb-2">
                            Adresă *
                        </label>
                        <input id="billing_address" type="text" name="billing_address" value="{{ old('billing_address') }}" required class="input-field w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800" placeholder="Strada Exemplu, Nr. 123">
                        @error('billing_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="billing_city" class="block text-sm font-semibold text-gray-700 mb-2">
                                Oraș *
                            </label>
                            <input id="billing_city" type="text" name="billing_city" value="{{ old('billing_city') }}" required class="input-field w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800" placeholder="Cluj-Napoca">
                            @error('billing_city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="billing_postal_code" class="block text-sm font-semibold text-gray-700 mb-2">
                                Cod Poștal *
                            </label>
                            <input id="billing_postal_code" type="text" name="billing_postal_code" value="{{ old('billing_postal_code') }}" required class="input-field w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800" placeholder="400001">
                            @error('billing_postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="billing_country" class="block text-sm font-semibold text-gray-700 mb-2">
                                Țară *
                            </label>
                            <input id="billing_country" type="text" name="billing_country" value="{{ old('billing_country', 'România') }}" required class="input-field w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800" placeholder="România">
                            @error('billing_country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="section-header">
                        <i class="fas fa-lock mr-2"></i>Securitate
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Parolă *
                            </label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" id="password" name="password" x-model="password" required autocomplete="new-password" class="input-field w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800 pr-12" placeholder="••••••••">
                                <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-purple-500">
                                    <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                Confirmă Parola *
                            </label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" class="input-field w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white focus:outline-none text-gray-800 pr-12" placeholder="••••••••">
                                <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-purple-500">
                                    <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 pt-2">
                        <input type="checkbox" id="terms" required class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500 cursor-pointer mt-0.5">
                        <label for="terms" class="text-sm text-gray-600 cursor-pointer">Accept termenii și condițiile</label>
                    </div>

                    <button type="submit" class="btn-gradient w-full py-4 px-6 text-white font-semibold rounded-xl shadow-lg flex items-center justify-center space-x-2 mt-6">
                        <i class="fas fa-user-plus"></i>
                        <span>Creează Cont</span>
                    </button>
                </form>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">sau</span>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-gray-600 text-sm">Ai deja cont?</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center mt-3 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all dur       <i class="fas fa-sign-in-alt mr-2 text-purple-500"></i>
                        Autentificare
                    </a>
                </div>
            </div>

            <div class="text-center mt-6">
                <a href="{{ url('/') }}" class="text-white/80 hover:text-white transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Înapoi la pagina principală
                </a>
            </div>
        </div>
    </div>
</body>
</html>
