<?php
// Force set locale before Laravel loads
$_GET['locale'] = $_GET['locale'] ?? 'en';

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Force locale based on GET parameter
$availableLocales = ['ro', 'en', 'de', 'fr', 'it', 'es'];
$requestedLocale = $_GET['locale'] ?? 'ro';

if (in_array($requestedLocale, $availableLocales)) {
    app()->setLocale($requestedLocale);
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Force Locale Test</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; }
        .ok { background: #d4edda; border: 2px solid #28a745; }
        .error { background: #f8d7da; border: 2px solid #dc3545; }
        h2 { margin: 0 0 15px 0; }
        pre { background: #eee; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>🔧 Force Locale Test</h1>
    
    <div class="box <?php echo app()->getLocale() === $requestedLocale ? 'ok' : 'error'; ?>">
        <h2>Locale Status</h2>
        <pre>Requested: <?php echo $requestedLocale; ?></pre>
        <pre>Current App Locale: <?php echo app()->getLocale(); ?></pre>
        <pre>Match: <?php echo app()->getLocale() === $requestedLocale ? '✅ YES' : '❌ NO'; ?></pre>
    </div>
    
    <div class="box <?php echo __('messages.features_title') !== 'Funcționalități Puternice' ? 'ok' : 'error'; ?>">
        <h2>Translations</h2>
        <pre>features_title: <?php echo __('messages.features_title'); ?></pre>
        <pre>modern_design: <?php echo __('messages.modern_design'); ?></pre>
        <pre>high_performance: <?php echo __('messages.high_performance'); ?></pre>
    </div>
    
    <div class="box">
        <h2>Test Links</h2>
        <p>
            <a href="?locale=ro">🇷🇴 Română</a> |
            <a href="?locale=en">🇬🇧 English</a> |
            <a href="?locale=de">🇩🇪 Deutsch</a> |
            <a href="?locale=fr">🇫🇷 Français</a>
        </p>
    </div>
    
    <div class="box">
        <h2>Feature Boxes (Direct from widget logic)</h2>
        <?php
        $features = [
            ['icon' => '🎨', 'title_key' => 'modern_design', 'desc_key' => 'modern_design_desc'],
            ['icon' => '⚡', 'title_key' => 'high_performance', 'desc_key' => 'high_performance_desc'],
            ['icon' => '🤖', 'title_key' => 'ai_integration', 'desc_key' => 'ai_integration_desc'],
        ];
        
        foreach ($features as $feature) {
            $title = __('messages.' . $feature['title_key']);
            $desc = __('messages.' . $feature['desc_key']);
            echo "<div style='margin:10px 0; padding:10px; background:#f0f0f0; border-radius:4px;'>";
            echo "<strong>{$feature['icon']} $title</strong><br>";
            echo "<small>$desc</small>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
