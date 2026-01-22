<?php
/**
 * Direct Admin Login - Bypasses Filament/Livewire entirely
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a fake request for Laravel to boot
$request = Illuminate\Http\Request::capture();
$kernel->handle($request);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

$error = '';
$success = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    // Verify CSRF (simplified)
    $csrfToken = $_POST['_token'] ?? '';
    $sessionToken = session()->token();
    
    if (!hash_equals($sessionToken, $csrfToken)) {
        $error = 'CSRF token mismatch. Please refresh and try again.';
    } else {
        $user = User::where('email', $email)->first();
        
        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $remember);
            
            // Redirect to admin
            header('Location: /admin');
            exit;
        } else {
            $error = 'Invalid credentials. Email: ' . $email;
        }
    }
}

$csrfToken = session()->token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direct Admin Login - Carphatian</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded-lg shadow-xl w-full max-w-md">
        <div class="text-center mb-8">
            <img src="/images/carphatian-logo-transparent.png" alt="Carphatian" class="h-16 mx-auto mb-4">
            <h1 class="text-2xl font-bold text-white">Admin Login</h1>
            <p class="text-gray-400 text-sm mt-2">Direct PHP Login (No Livewire)</p>
        </div>
        
        <?php if ($error): ?>
            <div class="bg-red-500/20 border border-red-500 text-red-400 p-4 rounded-lg mb-6">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="bg-green-500/20 border border-green-500 text-green-400 p-4 rounded-lg mb-6">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <input type="hidden" name="_token" value="<?= $csrfToken ?>">
            
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/50 outline-none transition">
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/50 outline-none transition">
            </div>
            
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-amber-500 focus:ring-amber-500">
                    <span class="ml-2 text-gray-300 text-sm">Remember me</span>
                </label>
            </div>
            
            <button type="submit" 
                    class="w-full bg-amber-500 hover:bg-amber-600 text-gray-900 font-semibold py-3 px-4 rounded-lg transition">
                Sign In
            </button>
        </form>
        
        <div class="mt-6 p-4 bg-gray-700/50 rounded-lg">
            <p class="text-gray-400 text-xs">
                <strong>Debug Info:</strong><br>
                PHP: <?= PHP_VERSION ?><br>
                Laravel: <?= app()->version() ?><br>
                Session: <?= config('session.driver') ?><br>
                CSRF: <?= substr($csrfToken, 0, 20) ?>...
            </p>
        </div>
    </div>
</body>
</html>
