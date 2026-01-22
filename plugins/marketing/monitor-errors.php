#!/usr/bin/env php
<?php
/**
 * Marketing Plugin Error Monitor
 * 
 * Scans Laravel logs and marketing plugin logs for errors.
 * Run: php plugins/marketing/monitor-errors.php
 */

$basePath = dirname(__DIR__, 2);
require $basePath . '/vendor/autoload.php';

$app = require_once $basePath . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

echo "=== Marketing Plugin Error Monitor ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

$errors = [];
$warnings = [];

// 1. Check Laravel log for 500 errors in the last 24 hours
echo "Checking Laravel logs...\n";
$logPath = storage_path('logs/laravel.log');
if (file_exists($logPath)) {
    $logContent = file_get_contents($logPath);
    $lines = explode("\n", $logContent);
    
    $yesterday = strtotime('-24 hours');
    $errorCount = 0;
    $warningCount = 0;
    
    foreach ($lines as $line) {
        // Check for recent errors
        if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\].*?(ERROR|error|Exception)/i', $line, $matches)) {
            $logDate = strtotime($matches[1]);
            if ($logDate >= $yesterday) {
                $errorCount++;
                if (stripos($line, 'marketing') !== false || stripos($line, 'Marketing') !== false) {
                    $errors[] = substr($line, 0, 200);
                }
            }
        }
        
        if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\].*?WARNING/i', $line, $matches)) {
            $logDate = strtotime($matches[1]);
            if ($logDate >= $yesterday) {
                $warningCount++;
            }
        }
    }
    
    echo "  - Total errors (24h): {$errorCount}\n";
    echo "  - Total warnings (24h): {$warningCount}\n";
    echo "  - Marketing-related errors: " . count($errors) . "\n";
} else {
    echo "  - Log file not found\n";
}

// 2. Check database connection
echo "\nChecking database connection...\n";
try {
    DB::connection()->getPdo();
    echo "  ✅ Database connection OK\n";
} catch (\Exception $e) {
    echo "  ❌ Database connection FAILED: " . $e->getMessage() . "\n";
    $errors[] = "Database connection failed";
}

// 3. Check marketing tables exist
echo "\nChecking marketing tables...\n";
$tables = [
    'marketing_contacts',
    'marketing_lists',
    'marketing_campaigns',
    'marketing_templates',
    'marketing_scrape_jobs',
    'marketing_campaign_logs',
];

foreach ($tables as $table) {
    try {
        $count = DB::table($table)->count();
        echo "  ✅ {$table}: {$count} records\n";
    } catch (\Exception $e) {
        echo "  ❌ {$table}: NOT FOUND\n";
        $warnings[] = "Table {$table} does not exist";
    }
}

// 4. Check scrape job errors
echo "\nChecking scrape job status...\n";
try {
    $failedJobs = DB::table('marketing_scrape_jobs')
        ->where('status', 'failed')
        ->where('created_at', '>=', now()->subDays(7))
        ->count();
    
    $runningJobs = DB::table('marketing_scrape_jobs')
        ->where('status', 'running')
        ->where('started_at', '<', now()->subHours(1))
        ->count();
    
    echo "  - Failed jobs (7 days): {$failedJobs}\n";
    echo "  - Stuck running jobs: {$runningJobs}\n";
    
    if ($runningJobs > 0) {
        $warnings[] = "Found {$runningJobs} scrape jobs stuck in 'running' state";
    }
} catch (\Exception $e) {
    echo "  - Could not check: " . $e->getMessage() . "\n";
}

// 5. Check campaign email errors
echo "\nChecking campaign status...\n";
try {
    $failedEmails = DB::table('marketing_campaign_logs')
        ->where('status', 'failed')
        ->where('created_at', '>=', now()->subDays(7))
        ->count();
    
    $bouncedEmails = DB::table('marketing_campaign_logs')
        ->where('status', 'bounced')
        ->where('created_at', '>=', now()->subDays(7))
        ->count();
    
    echo "  - Failed emails (7 days): {$failedEmails}\n";
    echo "  - Bounced emails (7 days): {$bouncedEmails}\n";
    
    if ($bouncedEmails > 10) {
        $warnings[] = "High bounce rate: {$bouncedEmails} bounced emails in 7 days";
    }
} catch (\Exception $e) {
    echo "  - Could not check: " . $e->getMessage() . "\n";
}

// 6. Check rate limits
echo "\nChecking rate limits...\n";
try {
    $remaining = \Plugins\Marketing\Models\MarketingRateLimit::getRemainingEmails();
    echo "  - Emails remaining (hourly): {$remaining['hourly']}\n";
    echo "  - Emails remaining (daily): {$remaining['daily']}\n";
} catch (\Exception $e) {
    echo "  - Rate limit check failed\n";
}

// 7. Check mail configuration
echo "\nChecking mail configuration...\n";
$mailConfig = config('mail');
if ($mailConfig['default'] === 'log') {
    $warnings[] = "Mail is configured to 'log' - emails won't be sent in production";
    echo "  ⚠️  Mail is set to 'log' mode (not sending real emails)\n";
} else {
    echo "  ✅ Mail configured: " . $mailConfig['default'] . "\n";
}

// 8. Check disk space
echo "\nChecking disk space...\n";
$freeSpace = disk_free_space('/');
$totalSpace = disk_total_space('/');
$usedPercent = round((($totalSpace - $freeSpace) / $totalSpace) * 100, 2);
$freeGB = round($freeSpace / 1024 / 1024 / 1024, 2);

echo "  - Free space: {$freeGB} GB ({$usedPercent}% used)\n";
if ($usedPercent > 90) {
    $errors[] = "Disk space critical: {$usedPercent}% used";
} elseif ($usedPercent > 80) {
    $warnings[] = "Disk space warning: {$usedPercent}% used";
}

// Summary
echo "\n=== Summary ===\n";
echo "Errors: " . count($errors) . "\n";
echo "Warnings: " . count($warnings) . "\n";

if (!empty($errors)) {
    echo "\n❌ ERRORS:\n";
    foreach ($errors as $error) {
        echo "  - {$error}\n";
    }
}

if (!empty($warnings)) {
    echo "\n⚠️  WARNINGS:\n";
    foreach ($warnings as $warning) {
        echo "  - {$warning}\n";
    }
}

if (empty($errors) && empty($warnings)) {
    echo "\n✅ All checks passed!\n";
}

echo "\n=== Monitor Complete ===\n";
