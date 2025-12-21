<?php
/**
 * Admin Login - Simple AJAX/Form based (No Livewire)
 */

// Ensure error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\User;

// Start session properly
if (!Session::isStarted()) {
    Session::start();
}

// Check if already logged in
if (Auth::check()) {
    header('Location: /admin');
    exit;
}

$error = '';
$debug = [];

// Handle POST login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    $csrfToken = $_POST['_token'] ?? '';
    $sessionToken = Session::token() ?? csrf_token() ?? '';
    
    $debug['email'] = $email;
    $debug['csrf_received'] = substr($csrfToken, 0, 10) . '...';
    $debug['csrf_session'] = substr($sessionToken, 0, 10) . '...';
    $debug['session_id'] = substr(Session::getId(), 0, 10) . '...';
    
    // Log attempt
    Log::info('Login attempt', ['email' => $email, 'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
    
    // Verify CSRF - more lenient check
    $csrfValid = false;
    if (!empty($sessionToken) && !empty($csrfToken)) {
        $csrfValid = hash_equals($sessionToken, $csrfToken);
    }
    
    if (!$csrfValid) {
        Log::warning('CSRF mismatch', ['email' => $email, 'debug' => $debug]);
        $errorMsg = 'Session expired. Please refresh the page.';
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $errorMsg, 'debug' => $debug]);
            exit;
        } else {
            $error = $errorMsg;
        }
    } else {
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            Log::warning('User not found', ['email' => $email]);
            $errorMsg = 'Email sau parolă incorectă.';
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => $errorMsg]);
                exit;
            } else {
                $error = $errorMsg;
            }
        } elseif (!Hash::check($password, $user->password)) {
            Log::warning('Invalid password', ['email' => $email]);
            $errorMsg = 'Email sau parolă incorectă.';
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => $errorMsg]);
                exit;
            } else {
                $error = $errorMsg;
            }
        } else {
            // Login successful
            Auth::login($user, $remember);
            Session::regenerate();
            
            Log::info('Login successful', ['email' => $email, 'user_id' => $user->id]);
            
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'redirect' => '/admin']);
                exit;
            } else {
                header('Location: /admin');
                exit;
            }
        }
    }
}

$csrfToken = Session::token() ?? csrf_token();
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars($csrfToken) ?>">
    <title>Admin Login - Carphatian</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .loading { opacity: 0.7; pointer-events: none; }
        .shake { animation: shake 0.5s; }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="bg-gray-800 p-8 rounded-xl shadow-2xl w-full max-w-md">
        <div class="text-center mb-8">
            <img src="/images/carphatian-logo-transparent.png" alt="Carphatian" class="h-16 mx-auto mb-4" onerror="this.style.display='none'">
            <h1 class="text-2xl font-bold text-white">Autentificare Admin</h1>
        </div>
        
        <?php if ($error): ?>
        <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-400 p-4 rounded-lg mb-6">
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>
        
        <div id="error-box" class="hidden bg-red-500 bg-opacity-20 border border-red-500 text-red-400 p-4 rounded-lg mb-6"></div>
        <div id="success-box" class="hidden bg-green-500 bg-opacity-20 border border-green-500 text-green-400 p-4 rounded-lg mb-6"></div>
        
        <form id="login-form" method="POST" action="/admin-login.php">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken) ?>">
            
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       required 
                       autocomplete="email"
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 outline-none transition"
                       placeholder="email@exemplu.ro"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            
            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Parolă</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required 
                       autocomplete="current-password"
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 outline-none transition"
                       placeholder="••••••••">
            </div>
            
            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-yellow-500 focus:ring-yellow-500">
                    <span class="ml-2 text-gray-300 text-sm">Ține-mă minte</span>
                </label>
            </div>
            
            <button type="submit" 
                    id="submit-btn"
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-3 px-4 rounded-lg transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                <span id="btn-text">Autentificare</span>
                <span id="btn-loading" class="hidden">Se procesează...</span>
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="/" class="text-gray-400 hover:text-yellow-500 text-sm transition">← Înapoi la site</a>
        </div>
    </div>

    <script>
    (function() {
        var form = document.getElementById('login-form');
        var errorBox = document.getElementById('error-box');
        var successBox = document.getElementById('success-box');
        var submitBtn = document.getElementById('submit-btn');
        var btnText = document.getElementById('btn-text');
        var btnLoading = document.getElementById('btn-loading');
        
        function showError(msg) {
            errorBox.textContent = msg;
            errorBox.classList.remove('hidden');
            successBox.classList.add('hidden');
            form.classList.add('shake');
            setTimeout(function() { form.classList.remove('shake'); }, 500);
        }
        
        function showSuccess(msg) {
            successBox.textContent = msg;
            successBox.classList.remove('hidden');
            errorBox.classList.add('hidden');
        }
        
        function setLoading(loading) {
            if (loading) {
                submitBtn.disabled = true;
                submitBtn.classList.add('loading');
                btnText.classList.add('hidden');
                btnLoading.classList.remove('hidden');
            } else {
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
                btnText.classList.remove('hidden');
                btnLoading.classList.add('hidden');
            }
        }
        
        // Progressive enhancement - use AJAX if available
        if (window.XMLHttpRequest) {
            form.onsubmit = function(e) {
                e.preventDefault();
                setLoading(true);
                errorBox.classList.add('hidden');
                
                var formData = new FormData(form);
                
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin-login.php', true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                
                xhr.onload = function() {
                    setLoading(false);
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            showSuccess('Autentificare reușită! Redirecționare...');
                            setTimeout(function() {
                                window.location.href = response.redirect || '/admin';
                            }, 500);
                        } else {
                            showError(response.error || 'Eroare la autentificare.');
                        }
                    } catch (err) {
                        // If AJAX fails, submit form normally
                        form.submit();
                    }
                };
                
                xhr.onerror = function() {
                    // If AJAX fails, submit form normally
                    form.submit();
                };
                
                xhr.send(formData);
            };
        }
    })();
    </script>
</body>
</html>
