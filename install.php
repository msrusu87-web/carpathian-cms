<?php
/**
 * Carpathian CMS Installer
 * WordPress-style guided installation
 */

// Check if already installed
if (file_exists(__DIR__ . '/.env') && !isset($_GET['force'])) {
    die('✅ CMS already installed! Delete .env file to reinstall or add ?force=1 to URL.');
}

$step = $_GET['step'] ?? '1';
$error = '';
$success = '';

// Handle installation steps
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($step) {
        case '2': // Database configuration
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
                $pdo->exec("CREATE DATABASE IF NOT EXISTS \`$dbName\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                
                // Store in session
                session_start();
                $_SESSION['db_config'] = compact('dbHost', 'dbPort', 'dbName', 'dbUser', 'dbPass');
                
                header('Location: install.php?step=3');
                exit;
            } catch (PDOException $e) {
                $error = 'Database connection failed: ' . $e->getMessage();
            }
            break;
            
        case '3': // Site configuration
            session_start();
            $_SESSION['site_config'] = [
                'app_name' => $_POST['app_name'] ?? 'Carpathian CMS',
                'app_url' => $_POST['app_url'] ?? 'http://localhost',
                'app_locale' => $_POST['app_locale'] ?? 'ro',
                'admin_email' => $_POST['admin_email'] ?? '',
                'admin_password' => $_POST['admin_password'] ?? '',
            ];
            header('Location: install.php?step=4');
            exit;
            
        case '4': // Final installation
            session_start();
            if (!isset($_SESSION['db_config']) || !isset($_SESSION['site_config'])) {
                header('Location: install.php?step=1');
                exit;
            }
            
            try {
                $db = $_SESSION['db_config'];
                $site = $_SESSION['site_config'];
                
                // 1. Create .env file
                $envContent = file_get_contents(__DIR__ . '/.env.example');
                $envContent = str_replace('APP_NAME="Carpathian CMS"', 'APP_NAME="' . $site['app_name'] . '"', $envContent);
                $envContent = str_replace('APP_URL=http://localhost', 'APP_URL=' . $site['app_url'], $envContent);
                $envContent = str_replace('APP_LOCALE=ro', 'APP_LOCALE=' . $site['app_locale'], $envContent);
                $envContent = str_replace('DB_DATABASE=your_database_name', 'DB_DATABASE=' . $db['dbName'], $envContent);
                $envContent = str_replace('DB_USERNAME=your_database_user', 'DB_USERNAME=' . $db['dbUser'], $envContent);
                $envContent = str_replace('DB_PASSWORD=your_database_password', 'DB_PASSWORD=' . $db['dbPass'], $envContent);
                $envContent = str_replace('DB_HOST=127.0.0.1', 'DB_HOST=' . $db['dbHost'], $envContent);
                $envContent = str_replace('DB_PORT=3306', 'DB_PORT=' . $db['dbPort'], $envContent);
                
                file_put_contents(__DIR__ . '/.env', $envContent);
                
                // 2. Import database
                $sqlFile = __DIR__ . '/database/schema/carpathian_cms.sql';
                if (file_exists($sqlFile)) {
                    $pdo = new PDO(
                        "mysql:host={$db['dbHost']};port={$db['dbPort']};dbname={$db['dbName']}",
                        $db['dbUser'],
                        $db['dbPass']
                    );
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    $sql = file_get_contents($sqlFile);
                    $pdo->exec($sql);
                }
                
                // 3. Run Composer and generate key
                chdir(__DIR__);
                exec('php artisan key:generate 2>&1', $output1, $return1);
                exec('php artisan config:cache 2>&1', $output2, $return2);
                exec('php artisan view:cache 2>&1', $output3, $return3);
                exec('php artisan storage:link 2>&1', $output4, $return4);
                
                // 4. Create admin user if provided
                if (!empty($site['admin_email']) && !empty($site['admin_password'])) {
                    $hashedPassword = password_hash($site['admin_password'], PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW()) ON DUPLICATE KEY UPDATE password = VALUES(password)");
                    $stmt->execute(['Administrator', $site['admin_email'], $hashedPassword]);
                }
                
                // Clear session
                session_destroy();
                
                header('Location: install.php?step=5');
                exit;
            } catch (Exception $e) {
                $error = 'Installation failed: ' . $e->getMessage();
                $step = '4';
            }
            break;
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
            max-width: 600px;
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
        .content { padding: 40px 30px; }
        .steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }
        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 15px;
            left: 60%;
            width: 80%;
            height: 2px;
            background: #e0e0e0;
            z-index: 0;
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
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏔️ Carpathian CMS</h1>
            <p>WordPress-style Installation Wizard</p>
        </div>
        
        <div class="content">
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <!-- Progress Steps -->
            <div class="steps">
                <div class="step-item <?= $step >= 1 ? 'completed' : '' ?> <?= $step == 1 ? 'active' : '' ?>">
                    <div class="step-number">1</div>
                    <div class="step-label">Welcome</div>
                </div>
                <div class="step-item <?= $step >= 3 ? 'completed' : '' ?> <?= $step == 2 ? 'active' : '' ?>">
                    <div class="step-number">2</div>
                    <div class="step-label">Database</div>
                </div>
                <div class="step-item <?= $step >= 4 ? 'completed' : '' ?> <?= $step == 3 ? 'active' : '' ?>">
                    <div class="step-number">3</div>
                    <div class="step-label">Site</div>
                </div>
                <div class="step-item <?= $step >= 5 ? 'completed' : '' ?> <?= $step == 4 ? 'active' : '' ?>">
                    <div class="step-number">4</div>
                    <div class="step-label">Install</div>
                </div>
                <div class="step-item <?= $step == 5 ? 'active completed' : '' ?>">
                    <div class="step-number">5</div>
                    <div class="step-label">Done</div>
                </div>
            </div>
            
            <?php if ($step == '1'): ?>
                <!-- Step 1: Welcome -->
                <h2>Welcome to Carpathian CMS!</h2>
                <p style="margin: 20px 0;">Before getting started, you will need:</p>
                <div class="info-box">
                    <h3>📋 Requirements</h3>
                    <ul>
                        <li>PHP 8.1 or higher</li>
                        <li>MySQL 5.7+ or MariaDB 10.3+</li>
                        <li>Composer installed</li>
                        <li>Database name, username, and password</li>
                    </ul>
                </div>
                <form method="get">
                    <input type="hidden" name="step" value="2">
                    <button type="submit" class="button">Let's Get Started →</button>
                </form>
                
            <?php elseif ($step == '2'): ?>
                <!-- Step 2: Database Configuration -->
                <h2>Database Configuration</h2>
                <p style="margin-bottom: 20px;">Enter your database connection details:</p>
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
                        <input type="text" name="db_name" required>
                        <div class="help-text">The database will be created if it does not exist</div>
                    </div>
                    <div class="form-group">
                        <label>Database Username</label>
                        <input type="text" name="db_user" required>
                    </div>
                    <div class="form-group">
                        <label>Database Password</label>
                        <input type="password" name="db_pass">
                    </div>
                    <button type="submit" class="button">Test & Continue →</button>
                </form>
                
            <?php elseif ($step == '3'): ?>
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
                        <input type="password" name="admin_password" required minlength="8">
                        <div class="help-text">Minimum 8 characters</div>
                    </div>
                    <button type="submit" class="button">Continue to Installation →</button>
                </form>
                
            <?php elseif ($step == '4'): ?>
                <!-- Step 4: Installing -->
                <h2>Installing...</h2>
                <div class="info-box">
                    <p>Click the button below to complete the installation. This will:</p>
                    <ul>
                        <li>Create configuration files</li>
                        <li>Import database schema</li>
                        <li>Generate application key</li>
                        <li>Create admin account</li>
                        <li>Set up storage links</li>
                    </ul>
                </div>
                <form method="post">
                    <button type="submit" class="button">🚀 Install Carpathian CMS</button>
                </form>
                
            <?php elseif ($step == '5'): ?>
                <!-- Step 5: Success -->
                <div class="text-center">
                    <div class="success-icon">✓</div>
                    <h2>Installation Complete!</h2>
                    <p style="margin: 20px 0;">Your Carpathian CMS is ready to use.</p>
                    <div class="info-box" style="text-align: left;">
                        <h3>🎯 Next Steps</h3>
                        <ul>
                            <li><strong>Delete install.php</strong> for security</li>
                            <li>Visit your website: <a href="/"><?= $_SERVER['HTTP_HOST'] ?></a></li>
                            <li>Login to admin panel: <a href="/admin"><?= $_SERVER['HTTP_HOST'] ?>/admin</a></li>
                            <li>Configure your site settings</li>
                            <li>Add new languages using <code>lang/TRANSLATION_GUIDE.md</code></li>
                        </ul>
                    </div>
                    <a href="/" class="button" style="display: inline-block; text-decoration: none; margin-top: 20px;">
                        Visit Your Website →
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
