<!DOCTYPE html>
<html>
<head>
    <title>Locale Debug - Through Middleware</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; border: 2px solid #ddd; }
        .ok { border-color: green; background: #f0fff0; }
        .error { border-color: red; background: #fff0f0; }
        h2 { margin-top: 0; }
        pre { background: #eee; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>🔍 Locale Debug (Through Laravel Middleware)</h1>
    
    <div class="box">
        <h2>Request Info</h2>
        <pre>URL: {{ $request->fullUrl() }}</pre>
        <pre>Query Param 'locale': {{ $query_locale }}</pre>
        <pre>URL Segment 1: {{ $segment_locale }}</pre>
        <pre>Cookie 'locale': {{ $cookie_locale }}</pre>
    </div>
    
    <div class="box {{ $app_locale === 'en' ? 'ok' : ($query_locale === 'en' ? 'error' : '') }}">
        <h2>Laravel Locale</h2>
        <pre>App Locale: {{ $app_locale }}</pre>
        <pre>Config app.locale: {{ $config_locale }}</pre>
        <pre>Session locale: {{ $session_locale }}</pre>
    </div>
    
    <div class="box {{ $app_locale === 'en' ? 'ok' : 'error' }}">
        <h2>Translation Test</h2>
        <pre>features_title: {{ __('messages.features_title') }}</pre>
        <pre>modern_design: {{ __('messages.modern_design') }}</pre>
        <pre>high_performance: {{ __('messages.high_performance') }}</pre>
    </div>
    
    <div class="box">
        <h2>Test Links</h2>
        <p>
            <a href="?locale=ro">🇷🇴 Română</a> |
            <a href="?locale=en">🇬🇧 English</a> |
            <a href="?locale=de">🇩🇪 Deutsch</a> |
            <a href="?locale=fr">🇫🇷 Français</a> |
            <a href="?locale=it">🇮🇹 Italiano</a> |
            <a href="?locale=es">🇪🇸 Español</a>
        </p>
        <p><a href="/">🏠 Go to Homepage</a></p>
    </div>
    
    <div class="box">
        <h2>Instructions</h2>
        <p>✅ If "App Locale" changes when you click links = Middleware works!</p>
        <p>✅ If translations change = Everything works!</p>
        <p>❌ If still shows Romanian = Check browser cache (Ctrl+Shift+R)</p>
    </div>
</body>
</html>
