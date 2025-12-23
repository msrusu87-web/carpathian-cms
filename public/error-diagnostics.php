<?php

/**
 * Secret-protected error diagnostics page.
 *
 * Usage:
 *   /error-diagnostics.php?secret=...&rid=...
 *
 * This reads storage/logs/laravel.log and extracts lines matching the request id.
 * Keep it protected with a strong secret via ERROR_DIAG_SECRET.
 */

declare(strict_types=1);

$secret = $_GET['secret'] ?? '';
$rid = $_GET['rid'] ?? '';

$expected = getenv('ERROR_DIAG_SECRET') ?: '';

if ($expected === '' || !hash_equals($expected, $secret)) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Forbidden";
    exit;
}

$rid = trim((string) $rid);
if ($rid === '') {
    http_response_code(400);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Missing rid";
    exit;
}

$basePath = realpath(__DIR__ . '/..');
$logPath = $basePath . '/storage/logs/laravel.log';

header('Content-Type: text/html; charset=utf-8');

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$lines = [];
if (is_file($logPath) && is_readable($logPath)) {
    // Read last ~20000 lines safely by scanning from the end.
    $fp = fopen($logPath, 'rb');
    if ($fp) {
        $buffer = '';
        $chunkSize = 1024 * 128;
        fseek($fp, 0, SEEK_END);
        $pos = ftell($fp);
        $maxBytes = 10 * 1024 * 1024; // 10MB max scan
        $scanned = 0;

        while ($pos > 0 && $scanned < $maxBytes) {
            $read = min($chunkSize, $pos);
            $pos -= $read;
            fseek($fp, $pos);
            $data = fread($fp, $read);
            $scanned += strlen($data);
            $buffer = $data . $buffer;
            // Stop once we have enough newlines.
            if (substr_count($buffer, "\n") > 20000) {
                break;
            }
        }

        fclose($fp);
        $lines = preg_split('/\r?\n/', $buffer);
    }
}

$matches = [];
$needle1 = '"rid":"' . $rid . '"';
$needle2 = 'rid=' . $rid;

foreach ($lines as $line) {
    if ($line === null || $line === '') {
        continue;
    }
    if (stripos($line, $needle1) !== false || stripos($line, $needle2) !== false || stripos($line, $rid) !== false) {
        $matches[] = $line;
    }
}

?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error diagnostics - <?= h($rid) ?></title>
    <style>
        body { font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 0; background: #0b1020; color: #e5e7eb; }
        .wrap { max-width: 1100px; margin: 0 auto; padding: 24px; }
        .card { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 14px; padding: 18px; }
        .muted { color: #9ca3af; }
        code, pre { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }
        pre { background: rgba(0,0,0,0.35); padding: 14px; border-radius: 12px; overflow: auto; white-space: pre-wrap; word-break: break-word; }
        a { color: #93c5fd; }
        .pill { display:inline-block; padding: 2px 10px; border:1px solid rgba(255,255,255,0.18); border-radius: 999px; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1 style="margin:0 0 8px 0; font-size: 18px;">Error diagnostics</h1>
        <div class="muted">Request ID: <span class="pill"><code><?= h($rid) ?></code></span></div>
        <div class="muted" style="margin-top:6px;">Log: <code><?= h($logPath) ?></code></div>
    </div>

    <div style="height: 14px"></div>

    <div class="card">
        <h2 style="margin:0 0 8px 0; font-size: 16px;">Matching log lines (<?= (int) count($matches) ?>)</h2>
        <?php if (count($matches) === 0): ?>
            <p class="muted">No matches found in the scanned log tail. If this was a 500, confirm the app is logging and that RequestId middleware is deployed.</p>
        <?php else: ?>
            <pre><?php foreach ($matches as $m) { echo h($m) . "\n"; } ?></pre>
        <?php endif; ?>

        <p class="muted" style="margin-top:10px;">
            Tip: For locale debugging, reproduce with <code>?__locale_debug=1</code> and then re-check logs by the same Request ID.
        </p>
    </div>
</div>
</body>
</html>
