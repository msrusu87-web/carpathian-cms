<?php
// Debug script to check cart functionality
session_start();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart Debug</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .debug-box { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        pre { background: #f0f0f0; padding: 10px; overflow-x: auto; }
        h2 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 5px; }
        .test-button { background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        .test-button:hover { background: #45a049; }
    </style>
</head>
<body>
    <h1>🔍 Cart Debug Information</h1>
    
    <div class="debug-box">
        <h2>Session Status</h2>
        <p><strong>Session ID:</strong> <?php echo session_id(); ?></p>
        <p><strong>Session Status:</strong> <?php echo session_status() === PHP_SESSION_ACTIVE ? '<span class="success">✅ Active</span>' : '<span class="error">❌ Inactive</span>'; ?></p>
    </div>

    <div class="debug-box">
        <h2>Cart Data</h2>
        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <p class="success">✅ Cart has <?php echo count($_SESSION['cart']); ?> item(s)</p>
            <pre><?php print_r($_SESSION['cart']); ?></pre>
        <?php else: ?>
            <p class="error">❌ Cart is empty</p>
        <?php endif; ?>
    </div>

    <div class="debug-box">
        <h2>All Session Data</h2>
        <pre><?php print_r($_SESSION); ?></pre>
    </div>

    <div class="debug-box">
        <h2>Laravel Session Cookie</h2>
        <?php
        $cookieName = 'laravel_session';
        if (isset($_COOKIE[$cookieName])) {
            echo '<p class="success">✅ Laravel session cookie found</p>';
            echo '<p><strong>Value:</strong> ' . substr($_COOKIE[$cookieName], 0, 50) . '...</p>';
        } else {
            echo '<p class="error">❌ No Laravel session cookie found</p>';
            echo '<p class="info">Available cookies:</p>';
            echo '<pre>' . print_r(array_keys($_COOKIE), true) . '</pre>';
        }
        ?>
    </div>

    <div class="debug-box">
        <h2>Quick Tests</h2>
        <p>Open your cart page and then come back here to see your session data.</p>
        <a href="/cart" class="test-button">Open Cart Page</a>
        <button onclick="location.reload()" class="test-button">Refresh This Page</button>
    </div>

    <div class="debug-box">
        <h2>Instructions for User</h2>
        <ol>
            <li>Open your cart page (with items in it): <a href="/cart">/cart</a></li>
            <li>Then open this debug page in a new tab</li>
            <li>Take a screenshot showing:
                <ul>
                    <li>The cart data from this page</li>
                    <li>Open browser Developer Tools (F12)</li>
                    <li>Go to Console tab - check for JavaScript errors</li>
                    <li>Go to Network tab - click the checkout button and see what request is made (or not made)</li>
                </ul>
            </li>
        </ol>
    </div>

    <div class="debug-box">
        <h2>Direct Link Test</h2>
        <p>Try clicking this direct link:</p>
        <a href="/checkout" class="test-button" style="background: #2196F3;">Go to Checkout Directly</a>
        <p class="info">If this link works, then the button issue is JavaScript/CSS related on the cart page.</p>
    </div>
</body>
</html>
