<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the request
$request = Illuminate\Http\Request::capture();

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Locale Debug</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; border: 2px solid #ddd; }
        .ok { border-color: green; background: #f0fff0; }
        .error { border-color: red; background: #fff0f0; }
        h2 { margin-top: 0; }
        pre { background: #eee; padding: 10px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔍 Locale Debug Information</h1>
    
    <div class="box">
        <h2>Request Info</h2>
        <pre>URL: <?php echo $request->fullUrl(); ?></pre>
        <pre>Query Param 'locale': <?php echo $request->query('locale', 'NOT SET'); ?></pre>
        <pre>URL Segment 1: <?php echo $request->segment(1) ?? 'NOT SET'; ?></pre>
        <pre>Cookie 'locale': <?php echo $request->cookie('locale', 'NOT SET'); ?></pre>
    </div>
    
    <div class="box">
        <h2>Laravel Locale</h2>
        <pre>App Locale: <?php echo app()->getLocale(); ?></pre>
        <pre>Config app.locale: <?php echo config('app.locale'); ?></pre>
        <pre>Session locale: <?php echo session('locale', 'NOT SET'); ?></pre>
    </div>
    
    <div class="box <?php echo app()->getLocale() === 'en' ? 'ok' : 'error'; ?>">
        <h2>Translation Test (should change based on locale)</h2>
        <pre>features_title: <?php echo __('messages.features_title'); ?></pre>
        <pre>modern_design: <?php echo __('messages.modern_design'); ?></pre>
        <pre>high_performance: <?php echo __('messages.high_performance'); ?></pre>
    </div>
    
    <div class="box">
        <h2>Available Locales</h2>
        <pre><?php print_r(['ro', 'en', 'de', 'fr', 'it', 'es']); ?></pre>
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
    </div>
</body>
</html>
