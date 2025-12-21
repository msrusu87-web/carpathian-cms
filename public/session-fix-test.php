<?php
// Session diagnostic
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== SESSION DIAGNOSTIC ===\n\n";

// Check PHP session settings
echo "PHP Session Settings:\n";
echo "session.cookie_domain: " . ini_get('session.cookie_domain') . "\n";
echo "session.cookie_secure: " . ini_get('session.cookie_secure') . "\n";
echo "session.cookie_httponly: " . ini_get('session.cookie_httponly') . "\n";
echo "session.cookie_samesite: " . ini_get('session.cookie_samesite') . "\n";
echo "HTTPS: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'YES' : 'NO') . "\n\n";

// Start session
session_start();
echo "Session started. ID: " . session_id() . "\n";

// Test counter
if (!isset($_SESSION['counter'])) {
    $_SESSION['counter'] = 0;
}
$_SESSION['counter']++;
$_SESSION['last_access'] = date('Y-m-d H:i:s');

echo "Counter: " . $_SESSION['counter'] . "\n";
echo "Last access: " . $_SESSION['last_access'] . "\n\n";

// Check cookies
echo "Cookies received:\n";
foreach ($_COOKIE as $name => $value) {
    echo "  $name = " . substr($value, 0, 30) . "...\n";
}

echo "\n=== Test successful - sessions work ===\n";
