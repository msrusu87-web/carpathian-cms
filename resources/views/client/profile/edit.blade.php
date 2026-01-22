@extends('client.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Profilul Meu</h1>
            <p class="text-gray-600 mt-2">Actualizează informațiile tale personale și de facturare</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('client.profile.update') }}" class="bg-white rounded-lg shadow-md p-8">
            @csrf
            @method('PUT')

            <!-- Personal Information -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user mr-2 text-purple-600"></i>
                    Informații Personale
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Prenume *
                        </label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nume *
                        </label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email *
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Telefon
                        </label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Company Information -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-building mr-2 text-purple-600"></i>
                    Informații Companie (Opțional)
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nume Companie
                        </label>
                        <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $user->company_name) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('company_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="company_reg_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Nr. Înregistrare (CUI)
                        </label>
                        <input type="text" id="company_reg_number" name="company_reg_number" value="{{ old('company_reg_number', $user->company_reg_number) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('company_reg_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="vat_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Nr. Înregistrare TVA
                        </label>
                        <input type="text" id="vat_number" name="vat_number" value="{{ old('vat_number', $user->vat_number) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('vat_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Billing Address -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt mr-2 text-purple-600"></i>
                    Adresă Facturare
                </h2>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Adresă *
                        </label>
                        <input type="text" id="billing_address" name="billing_address" value="{{ old('billing_address', $user->billing_address) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('billing_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-2">
                                Oraș *
                            </label>
                            <input type="text" id="billing_city" name="billing_city" value="{{ old('billing_city', $user->billing_city) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            @error('billing_city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="billing_postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                Cod Poștal *
                            </label>
                            <input type="text" id="billing_postal_code" name="billing_postal_code" value="{{ old('billing_postal_code', $user->billing_postal_code) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            @error('billing_postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="billing_country" class="block text-sm font-medium text-gray-700 mb-2">
                                Țară *
                            </label>
                            <input type="text" id="billing_country" name="billing_country" value="{{ old('billing_country', $user->billing_country) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            @error('billing_country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Change -->
            <div class="pb-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-lock mr-2 text-purple-600"></i>
                    Schimbă Parola (Opțional)
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Parola Curentă
                        </label>
                        <input type="password" id="current_password" name="current_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Parola Nouă
                        </label>
                        <input type="password" id="new_password" name="new_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('new_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmă Parola Nouă
                        </label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-tr            </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between">
                <a href="{{ route('client.dashboard') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Înapoi la Dashboard
                </a>
                <button type="submit" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Salvează Modificările
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
