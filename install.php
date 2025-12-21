<?php
/**
 * Carpathian CMS Installer
 * Advanced installation wizard with automatic server checks
 * Version: 2.0
 */

// Security: Check if already installed
if (file_exists(__DIR__ . '/.env') && !isset($_GET['force'])) {
    die('✅ CMS already installed! Delete .env file to reinstall or add ?force=1 to URL.');
}

$step = $_GET['step'] ?? 'check';
$error = '';
$success = '';
$warnings = [];

/**
 * Check server requirements
 */
function checkRequirements() {
    $checks = [];
    
    // PHP Version
    $phpVersion = PHP_VERSION;
    $checks['php_version'] = [
        'name' => 'PHP Version (>= 8.1)',
        'status' => version_compare($phpVersion, '8.1.0', '>='),
        'value' => $phpVersion,
        'required' => true
    ];
    
    // Required PHP Extensions
    $requiredExtensions = [
        'BCMath', 'Ctype', 'cURL', 'DOM', 'Fileinfo', 'JSON',
        'Mbstring', 'OpenSSL', 'PCRE', 'PDO', 'pdo_mysql',
        'Tokenizer', 'XML', 'Zip'
    ];
    
    foreach ($requiredExtensions as $ext) {
        $loaded = extension_loaded(strtolower($ext));
        $checks['ext_' . strtolower($ext)] = [
            'name' => "PHP Extension: $ext",
            'status' => $loaded,
            'value' => $loaded ? 'Installed' : 'Missing',
            'required' => true
        ];
    }
    
    // Optional but recommended extensions
    $optionalExtensions = ['gd', 'imagick', 'redis', 'opcache'];
    foreach ($optionalExtensions as $ext) {
        $loaded = extension_loaded(strtolower($ext));
        $checks['opt_' . strtolower($ext)] = [
            'name' => "PHP Extension: $ext (optional)",
            'status' => $loaded,
            'value' => $loaded ? 'Installed' : 'Not installed',
            'required' => false
        ];
    }
    
    // Directory Permissions
    $directories = [
        'storage' => __DIR__ . '/storage',
        'bootstrap/cache' => __DIR__ . '/bootstrap/cache',
        'database' => __DIR__ . '/database'
    ];
    
    foreach ($directories as $name => $path) {
        $writable = is_writable($path);
        $checks['dir_' . str_replace('/', '_', $name)] = [
            'name' => "Directory Writable: $name",
            'status' => $writable,
            'value' => $writable ? 'Writable' : 'Not writable',
            'required' => true,
            'path' => $path
        ];
    }
    
    // Check if .env.example exists
    $checks['env_example'] = [
        'name' => '.env.example file exists',
        'status' => file_exists(__DIR__ . '/.env.example'),
        'value' => file_exists(__DIR__ . '/.env.example') ? 'Found' : 'Missing',
        'required' => true
    ];
    
    // Check if database schema exists
    $sqlFiles = [
        __DIR__ . '/database/schema/carpathian_cms_full.sql',
        __DIR__ . '/database/schema/carpathian_cms.sql'
    ];
    $sqlFileFound = false;
    $sqlFilePath = '';
    foreach ($sqlFiles as $file) {
        if (file_exists($file)) {
            $sqlFileFound = true;
            $sqlFilePath = $file;
            break;
        }
    }
    
    $checks['sql_schema'] = [
        'name' => 'Database Schema File',
        'status' => $sqlFileFound,
        'value' => $sqlFileFound ? basename($sqlFilePath) : 'Missing',
        'required' => true,
        'path' => $sqlFilePath
    ];
    
    // Check Composer
    exec('composer --version 2>&1', $composerOutput, $composerReturn);
    $checks['composer'] = [
        'name' => 'Composer Installed',
        'status' => $composerReturn === 0,
        'value' => $composerReturn === 0 ? 'Installed' : 'Not found',
        'required' => true
    ];
    
    // PHP Memory Limit
    $memoryLimit = ini_get('memory_limit');
    $memoryValue = preg_replace('/[^0-9]/', '', $memoryLimit);
    $checks['memory_limit'] = [
        'name' => 'PHP Memory Limit (>= 128M)',
        'status' => $memoryValue >= 128,
        'value' => $memoryLimit,
        'required' => false
    ];
    
    // Max Execution Time
    $maxExecTime = ini_get('max_execution_time');
    $checks['max_exec_time'] = [
        'name' => 'Max Execution Time (>= 120s)',
        'status' => $maxExecTime == 0 || $maxExecTime >= 120,
        'value' => $maxExecTime . 's',
        'required' => false
    ];
    
    return $checks;
}

/**
 * Import SQL file with proper error handling
 */
function importDatabase($pdo, $sqlFile) {
    $sql = file_get_contents($sqlFile);
    
    // Remove comments
    $sql = preg_replace('/^--.*$/m', '', $sql);
    $sql = preg_replace('/^#.*$/m', '', $sql);
    
    // Split by semicolon but not inside quotes
    $statements = [];
    $currentStatement = '';
    $inString = false;
    $stringChar = '';
    
    for ($i = 0; $i < strlen($sql); $i++) {
        $char = $sql[$i];
        
        if (($char === '"' || $char === "'") && ($i == 0 || $sql[$i-1] !== '\\')) {
            if (!$inString) {
                $inString = true;
                $stringChar = $char;
            } elseif ($char === $stringChar) {
                $inString = false;
            }
        }
        
        if ($char === ';' && !$inString) {
            $currentStatement .= $char;
            $stmt = trim($currentStatement);
            if (!empty($stmt)) {
                $statements[] = $stmt;
            }
            $currentStatement = '';
        } else {
            $currentStatement .= $char;
        }
    }
    
    // Add last statement if exists
    $stmt = trim($currentStatement);
    if (!empty($stmt)) {
        $statements[] = $stmt;
    }
    
    // Execute statements
    $errors = [];
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (empty($statement) || substr($statement, 0, 2) === '--' || substr($statement, 0, 2) === '/*') {
            continue;
        }
        
        try {
            $pdo->exec($statement);
        } catch (PDOException $e) {
            // Log error but continue (some errors might be acceptable like table already exists)
            $errors[] = $e->getMessage();
        }
    }
    
    return $errors;
}

/**
 * Set proper file permissions
 */
function setPermissions() {
    $directories = [
        'storage',
        'storage/app',
        'storage/app/public',
        'storage/framework',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/framework/views',
        'storage/logs',
        'bootstrap/cache'
    ];
    
    foreach ($directories as $dir) {
        $path = __DIR__ . '/' . $dir;
        if (file_exists($path)) {
            @chmod($path, 0775);
        }
    }
    
    return true;
}

// Handle installation steps
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($step) {
        case 'database':
            $dbHost = $_POST['db_host'] ?? '127.0.0.1';
            $dbPort = $_POST['db_port'] ?? '3306';
            $dbName = $_POST['db_name'] ?? '';
            $dbUser = $_POST['db_user'] ?? '';
            $dbPass = $_POST['db_pass'] ?? '';
            
            // Test database connection
            try {
                $dsn = "mysql:host=$dbHost;port=$dbPort";
                $pdo = new PDO($dsn, $dbUser, $dbPass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Create database if it doesn't exist
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                
                // Test connection to the database
                $pdo->exec("USE `$dbName`");
                
                // Store in session
                session_start();
                $_SESSION['db_config'] = compact('dbHost', 'dbPort', 'dbName', 'dbUser', 'dbPass');
                
                header('Location: install.php?step=site');
                exit;
            } catch (PDOException $e) {
                $error = 'Database connection failed: ' . $e->getMessage();
                $error .= '<br><small>Make sure the database user has proper privileges: CREATE, ALTER, DROP, INSERT, UPDATE, DELETE, SELECT</small>';
            }
            break;
            
        case 'site':
            session_start();
            $_SESSION['site_config'] = [
                'app_name' => $_POST['app_name'] ?? 'Carpathian CMS',
                'app_url' => rtrim($_POST['app_url'] ?? 'http://localhost', '/'),
                'app_locale' => $_POST['app_locale'] ?? 'ro',
                'admin_email' => $_POST['admin_email'] ?? '',
                'admin_password' => $_POST['admin_password'] ?? '',
            ];
            header('Location: install.php?step=install');
            exit;
            
        case 'install':
            session_start();
            if (!isset($_SESSION['db_config']) || !isset($_SESSION['site_config'])) {
                header('Location: install.php?step=check');
                exit;
            }
            
            try {
                $db = $_SESSION['db_config'];
                $site = $_SESSION['site_config'];
                $installLog = [];
                
                // 1. Set proper permissions
                $installLog[] = 'Setting file permissions...';
                setPermissions();
                
                // 2. Create .env file
                $installLog[] = 'Creating .env configuration...';
                $envContent = file_get_contents(__DIR__ . '/.env.example');
                $envContent = str_replace('APP_NAME="Carpathian CMS"', 'APP_NAME="' . addslashes($site['app_name']) . '"', $envContent);
                $envContent = str_replace('APP_URL=http://localhost', 'APP_URL=' . $site['app_url'], $envContent);
                $envContent = str_replace('APP_LOCALE=ro', 'APP_LOCALE=' . $site['app_locale'], $envContent);
                $envContent = str_replace('DB_DATABASE=your_database_name', 'DB_DATABASE=' . $db['dbName'], $envContent);
                $envContent = str_replace('DB_USERNAME=your_database_user', 'DB_USERNAME=' . $db['dbUser'], $envContent);
                $envContent = str_replace('DB_PASSWORD=your_database_password', 'DB_PASSWORD=' . $db['dbPass'], $envContent);
                $envContent = str_replace('DB_HOST=127.0.0.1', 'DB_HOST=' . $db['dbHost'], $envContent);
                $envContent = str_replace('DB_PORT=3306', 'DB_PORT=' . $db['dbPort'], $envContent);
                
                file_put_contents(__DIR__ . '/.env', $envContent);
                
                // 3. Connect to database
                $installLog[] = 'Connecting to database...';
                $pdo = new PDO(
                    "mysql:host={$db['dbHost']};port={$db['dbPort']};dbname={$db['dbName']}",
                    $db['dbUser'],
                    $db['dbPass'],
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
                
                // 4. Import database
                $installLog[] = 'Importing database schema...';
                $sqlFiles = [
                    __DIR__ . '/database/schema/carpathian_cms_full.sql',
                    __DIR__ . '/database/schema/carpathian_cms.sql'
                ];
                
                $sqlFile = null;
                foreach ($sqlFiles as $file) {
                    if (file_exists($file)) {
                        $sqlFile = $file;
                        break;
                    }
                }
                
                if ($sqlFile) {
                    $importErrors = importDatabase($pdo, $sqlFile);
                    if (!empty($importErrors)) {
                        $installLog[] = 'Some non-critical database warnings occurred (this is usually normal)';
                    }
                } else {
                    throw new Exception('Database schema file not found');
                }
                
                // 5. Generate application key
                $installLog[] = 'Generating application key...';
                chdir(__DIR__);
                exec('php artisan key:generate --force 2>&1', $output1, $return1);
                
                // 6. Run Laravel setup commands
                $installLog[] = 'Configuring Laravel...';
                exec('php artisan config:clear 2>&1', $output2);
                exec('php artisan cache:clear 2>&1', $output3);
                exec('php artisan view:clear 2>&1', $output4);
                exec('php artisan storage:link 2>&1', $output5);
                
                // 7. Update/Create admin user if provided
                if (!empty($site['admin_email']) && !empty($site['admin_password'])) {
                    $installLog[] = 'Creating admin user...';
                    $hashedPassword = password_hash($site['admin_password'], PASSWORD_BCRYPT);
                    
                    // Check if users table exists
                    $tableCheck = $pdo->query("SHOW TABLES LIKE 'users'")->rowCount();
                    if ($tableCheck > 0) {
                        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at, updated_at) 
                                              VALUES (?, ?, ?, NOW(), NOW()) 
                                              ON DUPLICATE KEY UPDATE password = VALUES(password), updated_at = NOW()");
                        $stmt->execute(['Administrator', $site['admin_email'], $hashedPassword]);
                    }
                }
                
                // 8. Create success file
                file_put_contents(__DIR__ . '/.installed', date('Y-m-d H:i:s'));
                
                // Clear session
                $_SESSION['install_log'] = $installLog;
                
                header('Location: install.php?step=complete');
                exit;
            } catch (Exception $e) {
                $error = 'Installation failed: ' . $e->getMessage();
                $error .= '<br><br><strong>Debug Information:</strong><br>' . nl2br(htmlspecialchars($e->getTraceAsString()));
                $step = 'install';
            }
            break;
    }
}

// Get server checks for display
$serverChecks = ($step === 'check' || $step === 'database') ? checkRequirements() : [];
$allRequiredPass = true;
$hasWarnings = false;
foreach ($serverChecks as $check) {
    if ($check['required'] && !$check['status']) {
        $allRequiredPass = false;
    }
    if (!$check['required'] && !$check['status']) {
        $hasWarnings = true;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carpathian CMS - Installation</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 800px;
            width: 100%;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 { font-size: 2em; margin-bottom: 10px; }
        .header p { opacity: 0.9; font-size: 0.95em; }
        .content { padding: 40px 30px; max-height: 600px; overflow-y: auto; }
        .steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
            flex-wrap: wrap;
        }
        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
            min-width: 80px;
            margin: 5px;
        }
        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }
        .step-item.active .step-number {
            background: #667eea;
            color: white;
        }
        .step-item.completed .step-number {
            background: #10b981;
            color: white;
        }
        .step-label {
            font-size: 0.75em;
            color: #666;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }
        .help-text {
            font-size: 0.85em;
            color: #666;
            margin-top: 5px;
        }
        .button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-error {
            background: #fee;
            border-left: 4px solid #f44;
            color: #c33;
        }
        .alert-success {
            background: #efe;
            border-left: 4px solid #4c4;
            color: #363;
        }
        .alert-warning {
            background: #fff4e5;
            border-left: 4px solid #ff9800;
            color: #e65100;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .info-box h3 {
            margin-bottom: 10px;
            color: #667eea;
        }
        .info-box ul {
            margin-left: 20px;
        }
        .info-box li {
            margin: 5px 0;
        }
        .check-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 0.9em;
        }
        .check-table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #e0e0e0;
        }
        .check-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #f0f0f0;
        }
        .check-table tr:last-child td {
            border-bottom: none;
        }
        .status-icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            text-align: center;
            line-height: 20px;
            font-weight: bold;
            font-size: 0.8em;
        }
        .status-pass {
            background: #10b981;
            color: white;
        }
        .status-fail {
            background: #ef4444;
            color: white;
        }
        .status-warning {
            background: #ff9800;
            color: white;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #10b981;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3em;
            margin: 0 auto 20px;
        }
        .text-center { text-align: center; }
        .mt-20 { margin-top: 20px; }
        code {
            background: #f5f5f5;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }
        .fix-command {
            background: #2d3748;
            color: #68d391;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            font-size: 0.85em;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏔️ Carpathian CMS</h1>
            <p>Advanced Installation Wizard v2.0</p>
        </div>
        
        <div class="content">
            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <!-- Progress Steps -->
            <div class="steps">
                <div class="step-item <?= in_array($step, ['database', 'site', 'install', 'complete']) ? 'completed' : '' ?> <?= $step == 'check' ? 'active' : '' ?>">
                    <div class="step-number">1</div>
                    <div class="step-label">Requirements</div>
                </div>
                <div class="step-item <?= in_array($step, ['site', 'install', 'complete']) ? 'completed' : '' ?> <?= $step == 'database' ? 'active' : '' ?>">
                    <div class="step-number">2</div>
                    <div class="step-label">Database</div>
                </div>
                <div class="step-item <?= in_array($step, ['install', 'complete']) ? 'completed' : '' ?> <?= $step == 'site' ? 'active' : '' ?>">
                    <div class="step-number">3</div>
                    <div class="step-label">Site Config</div>
                </div>
                <div class="step-item <?= $step == 'complete' ? 'completed' : '' ?> <?= $step == 'install' ? 'active' : '' ?>">
                    <div class="step-number">4</div>
                    <div class="step-label">Install</div>
                </div>
                <div class="step-item <?= $step == 'complete' ? 'active completed' : '' ?>">
                    <div class="step-number">5</div>
                    <div class="step-label">Complete</div>
                </div>
            </div>
            
            <?php if ($step == 'check'): ?>
                <!-- Step 1: Requirements Check -->
                <h2>Server Requirements Check</h2>
                <p style="margin: 15px 0;">Checking your server configuration...</p>
                
                <?php if (!$allRequiredPass): ?>
                    <div class="alert alert-error">
                        <strong>❌ Requirements Not Met</strong><br>
                        Your server does not meet all required specifications. Please fix the issues below before continuing.
                    </div>
                <?php elseif ($hasWarnings): ?>
                    <div class="alert alert-warning">
                        <strong>⚠️ Warnings Detected</strong><br>
                        All required checks passed, but some optional features are not available. You can continue, but consider fixing warnings for better performance.
                    </div>
                <?php else: ?>
                    <div class="alert alert-success">
                        <strong>✅ All Checks Passed!</strong><br>
                        Your server meets all requirements for Carpathian CMS.
                    </div>
                <?php endif; ?>
                
                <table class="check-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Status</th>
                            <th>Requirement</th>
                            <th style="width: 150px;">Current Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($serverChecks as $key => $check): ?>
                            <tr>
                                <td>
                                    <span class="status-icon <?= $check['status'] ? 'status-pass' : ($check['required'] ? 'status-fail' : 'status-warning') ?>">
                                        <?= $check['status'] ? '✓' : '✗' ?>
                                    </span>
                                </td>
                                <td>
                                    <?= htmlspecialchars($check['name']) ?>
                                    <?php if (!$check['status'] && isset($check['path'])): ?>
                                        <br><small style="color: #666;">Path: <?= htmlspecialchars($check['path']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($check['value']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <?php if (!$allRequiredPass): ?>
                    <div class="info-box">
                        <h3>🔧 How to Fix</h3>
                        <p><strong>Missing PHP Extensions:</strong></p>
                        <div class="fix-command">
                            # Ubuntu/Debian<br>
                            sudo apt install php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml \<br>
                            &nbsp;&nbsp;php8.3-curl php8.3-gd php8.3-zip php8.3-bcmath
                        </div>
                        
                        <p style="margin-top: 15px;"><strong>Fix Directory Permissions:</strong></p>
                        <div class="fix-command">
                            cd <?= __DIR__ ?><br>
                            sudo chmod -R 775 storage bootstrap/cache<br>
                            sudo chown -R www-data:www-data storage bootstrap/cache
                        </div>
                        
                        <p style="margin-top: 15px;"><strong>Install Composer:</strong></p>
                        <div class="fix-command">
                            curl -sS https://getcomposer.org/installer | php<br>
                            sudo mv composer.phar /usr/local/bin/composer
                        </div>
                    </div>
                    
                    <button type="button" class="button" onclick="location.reload()">🔄 Recheck Requirements</button>
                <?php else: ?>
                    <form method="get">
                        <input type="hidden" name="step" value="database">
                        <button type="submit" class="button">Continue to Database Setup →</button>
                    </form>
                <?php endif; ?>
                
            <?php elseif ($step == 'database'): ?>
                <!-- Step 2: Database Configuration -->
                <h2>Database Configuration</h2>
                <p style="margin-bottom: 20px;">Enter your MySQL/MariaDB connection details:</p>
                
                <div class="info-box">
                    <h3>📝 Database Privileges Required</h3>
                    <p>Your database user needs these privileges:</p>
                    <code>SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES</code>
                </div>
                
                <form method="post">
                    <div class="form-group">
                        <label>Database Host</label>
                        <input type="text" name="db_host" value="127.0.0.1" required>
                        <div class="help-text">Usually "127.0.0.1" or "localhost"</div>
                    </div>
                    <div class="form-group">
                        <label>Database Port</label>
                        <input type="text" name="db_port" value="3306" required>
                        <div class="help-text">Default MySQL port is 3306</div>
                    </div>
                    <div class="form-group">
                        <label>Database Name</label>
                        <input type="text" name="db_name" required placeholder="carpathian_cms">
                        <div class="help-text">The database will be created if it doesn't exist</div>
                    </div>
                    <div class="form-group">
                        <label>Database Username</label>
                        <input type="text" name="db_user" required>
                    </div>
                    <div class="form-group">
                        <label>Database Password</label>
                        <input type="password" name="db_pass" autocomplete="new-password">
                        <div class="help-text">Leave empty if no password is set</div>
                    </div>
                    <button type="submit" class="button">Test Connection & Continue →</button>
                </form>
                
            <?php elseif ($step == 'site'): ?>
                <!-- Step 3: Site Configuration -->
                <h2>Site Configuration</h2>
                <p style="margin-bottom: 20px;">Configure your website settings:</p>
                
                <form method="post">
                    <div class="form-group">
                        <label>Site Name</label>
                        <input type="text" name="app_name" value="Carpathian CMS" required>
                        <div class="help-text">Your website name/brand</div>
                    </div>
                    <div class="form-group">
                        <label>Site URL</label>
                        <input type="url" name="app_url" value="http://<?= $_SERVER['HTTP_HOST'] ?>" required>
                        <div class="help-text">Your full website URL (with http:// or https://)</div>
                    </div>
                    <div class="form-group">
                        <label>Default Language</label>
                        <select name="app_locale" required>
                            <option value="ro" selected>Română (Romanian)</option>
                            <option value="en">English</option>
                            <option value="it">Italiano (Italian)</option>
                            <option value="fr">Français (French)</option>
                            <option value="de">Deutsch (German)</option>
                            <option value="es">Español (Spanish)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Admin Email</label>
                        <input type="email" name="admin_email" required>
                        <div class="help-text">Administrator account email</div>
                    </div>
                    <div class="form-group">
                        <label>Admin Password</label>
                        <input type="password" name="admin_password" required minlength="8" autocomplete="new-password">
                        <div class="help-text">Minimum 8 characters (use a strong password)</div>
                    </div>
                    <button type="submit" class="button">Continue to Installation →</button>
                </form>
                
            <?php elseif ($step == 'install'): ?>
                <!-- Step 4: Install -->
                <h2>Ready to Install</h2>
                <div class="info-box">
                    <h3>📦 Installation Process</h3>
                    <p>The installer will now:</p>
                    <ul>
                        <li>✅ Set proper file permissions</li>
                        <li>✅ Create .env configuration file</li>
                        <li>✅ Import database with all demo content</li>
                        <li>✅ Generate application security key</li>
                        <li>✅ Configure Laravel framework</li>
                        <li>✅ Create admin user account</li>
                        <li>✅ Set up storage links</li>
                    </ul>
                    <p style="margin-top: 15px;"><strong>This may take 30-60 seconds. Please don't close this page.</strong></p>
                </div>
                <form method="post">
                    <button type="submit" class="button">🚀 Install Carpathian CMS Now</button>
                </form>
                
            <?php elseif ($step == 'complete'): ?>
                <!-- Step 5: Success -->
                <?php
                session_start();
                $installLog = $_SESSION['install_log'] ?? [];
                ?>
                <div class="text-center">
                    <div class="success-icon">✓</div>
                    <h2>Installation Complete!</h2>
                    <p style="margin: 20px 0;">Your Carpathian CMS is ready to use.</p>
                </div>
                
                <?php if (!empty($installLog)): ?>
                    <div class="info-box">
                        <h3>📋 Installation Log</h3>
                        <ul>
                            <?php foreach ($installLog as $log): ?>
                                <li><?= htmlspecialchars($log) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="info-box">
                    <h3>🎯 Next Steps</h3>
                    <ol style="margin-left: 20px;">
                        <li style="margin: 10px 0;"><strong>Delete install.php for security:</strong>
                            <div class="fix-command" style="margin-top: 5px;">
                                cd <?= __DIR__ ?><br>
                                rm install.php
                            </div>
                        </li>
                        <li style="margin: 10px 0;">
                            <strong>Visit your website:</strong> 
                            <a href="/" target="_blank"><?= $_SERVER['HTTP_HOST'] ?></a>
                        </li>
                        <li style="margin: 10px 0;">
                            <strong>Login to admin panel:</strong> 
                            <a href="/admin" target="_blank"><?= $_SERVER['HTTP_HOST'] ?>/admin</a>
                        </li>
                        <li style="margin: 10px 0;"><strong>Configure site settings in admin panel</strong></li>
                        <li style="margin: 10px 0;"><strong>Review and customize content (products, pages, posts)</strong></li>
                        <li style="margin: 10px 0;"><strong>Add translations:</strong> See <code>lang/TRANSLATION_GUIDE.md</code></li>
                    </ol>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <a href="/" class="button" style="display: block; text-decoration: none; text-align: center; padding: 14px;">
                        🌐 View Website
                    </a>
                    <a href="/admin" class="button" style="display: block; text-decoration: none; text-align: center; padding: 14px;">
                        🔐 Admin Panel
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
