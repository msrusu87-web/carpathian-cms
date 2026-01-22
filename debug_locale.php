<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Simulate request with cookie
$_COOKIE['locale'] = 'en';

$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

echo "Request Cookie: " . ($request->cookie('locale') ?? 'NULL') . "\n";
echo "Raw Cookie: " . ($_COOKIE['locale'] ?? 'NULL') . "\n";
echo "App Locale: " . app()->getLocale() . "\n";
echo "Config Locale: " . config('app.locale') . "\n";
