<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Autentificare - Carphatian</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #111827; color: #fff; font-family: system-ui, -apple-system, sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1rem; }
        .container { background: #1f2937; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); width: 100%; max-width: 28rem; padding: 2rem; }
        .logo { text-align: center; margin-bottom: 2rem; }
        .logo img { height: 4rem; margin-bottom: 1rem; }
        h1 { font-size: 1.875rem; font-weight: 700; text-align: center; margin-bottom: 0.5rem; }
        .subtitle { text-align: center; color: #9ca3af; font-size: 0.875rem; margin-bottom: 2rem; }
        .alert { padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; }
        .alert-error { background: rgba(239,68,68,0.2); border: 1px solid #ef4444; color: #f87171; }
        .alert-success { background: rgba(34,197,94,0.2); border: 1px solid #22c55e; color: #4ade80; }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; font-size: 0.875rem; font-weight: 500; color: #d1d5db; margin-bottom: 0.5rem; }
        input[type="email"], input[type="password"] { width: 100%; padding: 0.75rem 1rem; background: #374151; border: 1px solid #4b5563; border-radius: 0.5rem; color: #fff; font-size: 1rem; outline: none; transition: all 0.2s; }
        input[type="email"]:focus, input[type="password"]:focus { border-color: #eab308; box-shadow: 0 0 0 3px rgba(234,179,8,0.3); }
        input::placeholder { color: #6b7280; }
        .form-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
        .checkbox-label { display: flex; align-items: center; cursor: pointer; }
        input[type="checkbox"] { width: 1rem; height: 1rem; margin-right: 0.5rem; accent-color: #eab308; }
        .link { color: #eab308; text-decoration: none; font-size: 0.875rem; }
        .link:hover { color: #facc15; }
        .btn { width: 100%; padding: 0.75rem 1rem; background: #eab308; color: #111827; font-weight: 700; font-size: 1rem; border: none; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s; }
        .btn:hover { background: #ca8a04; transform: translateY(-1px); }
        .footer { text-align: center; margin-top: 1.5rem; color: #6b7280; font-size: 0.875rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="{{ asset('images/carphatian-logo-transparent.png') }}" alt="Carphatian" onerror="this.style.display='none'">
        </div>
        
        <h1>Autentificare</h1>
        <p class="subtitle">Intrați în contul dumneavoastră</p>
        
        @if ($errors->any())
        <div class="alert alert-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="email@exemplu.ro">
            </div>
            
            <div class="form-group">
                <label for="password">Parolă</label>
                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            </div>
            
            <div class="form-row">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember">
                    <span>Ține-mă minte</span>
                </label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="link">Ai uitat parola?</a>
                @endif
            </div>
            
            <button type="submit" class="btn">Autentificare</button>
        </form>
        
        <div class="footer">
            @if (Route::has('register'))
            <div>Nu ai cont? <a href="{{ route('register') }}" class="link">Înregistrează-te</a></div>
            @endif
            <div style="margin-top: 0.5rem;"><a href="/" class="link">← Înapoi la site</a></div>
        </div>
    </div>
</body>
</html>
