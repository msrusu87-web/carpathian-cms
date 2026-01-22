#!/usr/bin/env php
<?php
/**
 * CMS Validation Script
 * Checks all critical components before deployment
 */

echo "\n🔍 CMS VALIDATION SCRIPT\n";
echo str_repeat("=", 60) . "\n\n";

// Colors for output
$green = "\033[32m";
$red = "\033[31m";
$yellow = "\033[33m";
$reset = "\033[0m";

$errors = 0;
$warnings = 0;

// 1. Check Environment Variables
echo "📋 Checking Environment Configuration...\n";
$envFile = __DIR__ . '/.env';
if (!file_exists($envFile)) {
    echo "{$red}✗ .env file not found{$reset}\n";
    $errors++;
} else {
    $envContent = file_get_contents($envFile);
    
    $required = ['APP_KEY', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'GROQ_API_KEY'];
    foreach ($required as $var) {
        if (strpos($envContent, "$var=") === false) {
            echo "{$red}✗ Missing: $var{$reset}\n";
            $errors++;
        } elseif (strpos($envContent, "$var=your-") !== false || strpos($envContent, "$var=\n") !== false) {
            echo "{$yellow}⚠ Not configured: $var{$reset}\n";
            $warnings++;
        } else {
            echo "{$green}✓ $var configured{$reset}\n";
        }
    }
}

// 2. Check Database Connection
echo "\n🗄️  Checking Database Connection...\n";
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    DB::connection()->getPdo();
    echo "{$green}✓ Database connected{$reset}\n";
    
    // Check required tables
    $tables = ['users', 'templates', 'posts', 'pages', 'categories', 'tags', 'media', 'plugins', 'settings'];
    foreach ($tables as $table) {
        if (Schema::hasTable($table)) {
            $count = DB::table($table)->count();
            echo "{$green}✓ Table '$table' exists ($count records){$reset}\n";
        } else {
            echo "{$red}✗ Table '$table' missing{$reset}\n";
            $errors++;
        }
    }
} catch (Exception $e) {
    echo "{$red}✗ Database connection failed: {$e->getMessage()}{$reset}\n";
    $errors++;
}

// 3. Check Template System
echo "\n🎨 Checking Template System...\n";
try {
    $activeTemplate = App\Models\Template::active()->first();
    $defaultTemplate = App\Models\Template::default()->first();
    
    if ($activeTemplate) {
        echo "{$green}✓ Active template found: {$activeTemplate->name}{$reset}\n";
    } else {
        echo "{$yellow}⚠ No active template{$reset}\n";
        $warnings++;
    }
    
    if ($defaultTemplate) {
        echo "{$green}✓ Default template found: {$defaultTemplate->name}{$reset}\n";
        
        // Check layouts
        $layouts = $defaultTemplate->layouts ?? [];
        if (empty($layouts)) {
            echo "{$red}✗ Template has no layouts defined{$reset}\n";
            $errors++;
        } else {
            echo "{$green}✓ Template has " . count($layouts) . " layout(s){$reset}\n";
        }
    } else {
        echo "{$red}✗ No default template{$reset}\n";
        $errors++;
    }
} catch (Exception $e) {
    echo "{$red}✗ Template check failed: {$e->getMessage()}{$reset}\n";
    $errors++;
}

// 4. Check Routes
echo "\n🛣️  Checking Routes...\n";
$requiredRoutes = ['home', 'blog', 'post.show', 'page.show'];
foreach ($requiredRoutes as $routeName) {
    if (Route::has($routeName)) {
        echo "{$green}✓ Route '$routeName' registered{$reset}\n";
    } else {
        echo "{$red}✗ Route '$routeName' missing{$reset}\n";
        $errors++;
    }
}

// 5. Check Services
echo "\n⚙️  Checking Services...\n";
try {
    $groqService = app(App\Services\GroqAiService::class);
    echo "{$green}✓ GroqAiService instantiated{$reset}\n";
} catch (Exception $e) {
    echo "{$red}✗ GroqAiService failed: {$e->getMessage()}{$reset}\n";
    $errors++;
}

try {
    $rendererService = app(App\Services\TemplateRendererService::class);
    echo "{$green}✓ TemplateRendererService instantiated{$reset}\n";
} catch (Exception $e) {
    echo "{$red}✗ TemplateRendererService failed: {$e->getMessage()}{$reset}\n";
    $errors++;
}

// 6. Check Permissions
echo "\n🔐 Checking File Permissions...\n";
$writableDirs = ['storage', 'bootstrap/cache', 'public'];
foreach ($writableDirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (is_writable($path)) {
        echo "{$green}✓ $dir is writable{$reset}\n";
    } else {
        echo "{$red}✗ $dir is not writable{$reset}\n";
        $errors++;
    }
}

// 7. Check Caches
echo "\n💾 Checking Caches...\n";
$cacheFiles = [
    'bootstrap/cache/config.php' => 'Config cache',
    'bootstrap/cache/routes-v7.php' => 'Routes cache',
];
foreach ($cacheFiles as $file => $name) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "{$green}✓ $name exists{$reset}\n";
    } else {
        echo "{$yellow}⚠ $name missing (run php artisan optimize){$reset}\n";
        $warnings++;
    }
}

// Final Report
echo "\n" . str_repeat("=", 60) . "\n";
echo "📊 VALIDATION SUMMARY\n";
echo str_repeat("=", 60) . "\n";

if ($errors === 0 && $warnings === 0) {
    echo "{$green}✓ ALL CHECKS PASSED{$reset}\n";
    echo "System is ready for production!\n";
    exit(0);
} elseif ($errors === 0) {
    echo "{$yellow}⚠ {$warnings} WARNING(S){$reset}\n";
    echo "System is operational but has minor issues\n";
    exit(0);
} else {
    echo "{$red}✗ {$errors} ERROR(S), {$warnings} WARNING(S){$reset}\n";
    echo "Please fix errors before deployment\n";
    exit(1);
}
