<?php

/**
 * Secret-protected report of missing DB translations.
 * Usage: /db-translation-report.php?secret=...&locales=ro,en,de,es,fr,it
 */

declare(strict_types=1);

$secret = $_GET['secret'] ?? '';
$expected = getenv('ERROR_DIAG_SECRET') ?: '';
if ($expected === '' || !hash_equals($expected, $secret)) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Forbidden';
    exit;
}

$basePath = realpath(__DIR__ . '/..');
require_once $basePath . '/vendor/autoload.php';

$app = require_once $basePath . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Http\Kernel::class);

$locales = $_GET['locales'] ?? 'ro,en,de,es,fr,it';

header('Content-Type: text/plain; charset=utf-8');

echo "DB Translation Report\n";
echo "Locales: {$locales}\n\n";

// Run artisan command programmatically for output simplicity.
/** @var \Illuminate\Contracts\Console\Kernel $kernel */
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$exit = $kernel->call('translations:audit-db', [
    '--locales' => $locales,
]);

echo $kernel->output();

echo "\nExit: {$exit}\n";
