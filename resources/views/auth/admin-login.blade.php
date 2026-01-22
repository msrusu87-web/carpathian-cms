<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Carphatian</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="bg-gray-800 p-8 rounded-xl shadow-2xl w-full max-w-md">
        <div class="text-center mb-8">
            <img src="{{ asset('images/carphatian-logo-transparent.png') }}" alt="Carphatian" class="h-16 mx-auto mb-4" onerror="this.style.display='none'">
            <h1 class="text-2xl font-bold text-white">Autentificare Admin</h1>
        </div>
        
        @if ($errors->any())
        <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-400 p-4 rounded-lg mb-6">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        @if (session('status'))
        <div class="bg-green-500 bg-opacity-20 border border-green-500 text-green-400 p-4 rounded-lg mb-6">
            {{ session('status') }}
        </div>
        @endif
        
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       required 
                       autofocus
                       autocomplete="email"
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 outline-none transition"
                       placeholder="email@exemplu.ro">
            </div>
            
            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Parolă</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required 
                       autocomplete="current-password"
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 outline-none transition"
                       placeholder="••••••••">
            </div>
            
            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-yellow-500 focus:ring-yellow-500">
                    <span class="ml-2 text-gray-300 text-sm">Ține-mă minte</span>
                </label>
            </div>
            
            <button type="submit" 
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-3 px-4 rounded-lg transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                Autentificare
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="/" class="text-gray-400 hover:text-yellow-500 text-sm transition">← Înapoi la site</a>
        </div>
    </div>
</body>
</html>
