#!/usr/bin/env php
<?php

/**
 * Production Translation Checker
 * Verifies that translations are working on live website
 */

echo "🔍 CHECKING PRODUCTION TRANSLATIONS\n";
echo "=====================================\n\n";

// Test keys to verify
$testKeys = [
    'dashboard',
    'security_suite',
    'ai_tools',
    'cms',
    'blog',
    'shop',
    'design',
    'communications',
    'content',
    'users_permissions',
    'settings',
    'external_links',
    'github_repository',
    'documentation',
    'live_website',
    // Common admin actions
    'edit',
    'delete',
    'cancel',
    'save',
    'create',
    'update',
    'search',
    'filter',
    'export',
    'import',
    'view',
    'actions',
];

// Check translation files exist
echo "📁 Checking Translation Files:\n";
$languages = ['ro', 'en', 'de', 'es', 'fr', 'it'];
$translationPath = '/var/www/carphatian.ro/html/lang';

foreach ($languages as $lang) {
    $filePath = "$translationPath/$lang/messages.php";
    if (file_exists($filePath)) {
        $translations = include $filePath;
        $count = count($translations);
        echo "  ✅ $lang: $count keys\n";
        
        // Check if test keys exist
        $missing = [];
        foreach ($testKeys as $key) {
            if (!isset($translations[$key])) {
                $missing[] = $key;
            }
        }
        
        if (!empty($missing)) {
            echo "     ⚠️  Missing keys: " . implode(', ', $missing) . "\n";
        }
    } else {
        echo "  ❌ $lang: File not found!\n";
    }
}

echo "\n📋 Romanian Translation Samples:\n";
$roTranslations = include "$translationPath/ro/messages.php";
foreach ($testKeys as $key) {
    if (isset($roTranslations[$key])) {
        echo "  $key => {$roTranslations[$key]}\n";
    } else {
        echo "  ❌ $key => NOT FOUND\n";
    }
}

// Check Laravel config
echo "\n⚙️  Checking Laravel Configuration:\n";
$configPath = '/var/www/carphatian.ro/html/config/app.php';
if (file_exists($configPath)) {
    $content = file_get_contents($configPath);
    
    // Extract locale
    if (preg_match("/'locale'\s*=>\s*'(\w+)'/", $content, $matches)) {
        echo "  Default Locale: {$matches[1]}\n";
    }
    
    // Extract fallback locale
    if (preg_match("/'fallback_locale'\s*=>\s*'(\w+)'/", $content, $matches)) {
        echo "  Fallback Locale: {$matches[1]}\n";
    }
    
    // Extract available locales
    if (preg_match("/'locales'\s*=>\s*\[(.*?)\]/s", $content, $matches)) {
        echo "  Available Locales: " . trim($matches[1]) . "\n";
    }
}

// Check Filament configuration
echo "\n🎨 Checking Filament Configuration:\n";
$filamentConfig = '/var/www/carphatian.ro/html/app/Providers/Filament/AdminPanelProvider.php';
if (file_exists($filamentConfig)) {
    $content = file_get_contents($filamentConfig);
    
    // Check if using translations
    $translationCount = substr_count($content, '__(');
    echo "  Translation calls (__): $translationCount\n";
    
    // Check for hardcoded strings
    $patterns = [
        '/NavigationItem::make\([\'"](?!__)([^\'"]+)[\'"]\)/' => 'NavigationItem hardcoded strings',
        '/navigationLabel\s*=\s*[\'"](?!__)([^\'"]+)[\'"]/' => 'Navigation label hardcoded strings',
        '/->label\([\'"](?!__)([^\'"]+)[\'"]\)/' => 'Label hardcoded strings',
    ];
    
    foreach ($patterns as $pattern => $description) {
        if (preg_match_all($pattern, $content, $matches)) {
            echo "  ⚠️  Found $description: " . count($matches[0]) . " instances\n";
            foreach (array_slice($matches[1], 0, 5) as $match) {
                echo "       - '$match'\n";
            }
        }
    }
}

// Scan Filament Resources for untranslated strings
echo "\n🔎 Scanning Filament Resources:\n";
$resourcePath = '/var/www/carphatian.ro/html/app/Filament/Resources';
$untranslatedStrings = [];

if (is_dir($resourcePath)) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($resourcePath)
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            
            // Find hardcoded strings in common Filament methods
            $patterns = [
                '/->label\([\'"]([^\'"]+)[\'"]\)/',
                '/->placeholder\([\'"]([^\'"]+)[\'"]\)/',
                '/->helperText\([\'"]([^\'"]+)[\'"]\)/',
                '/->heading\([\'"]([^\'"]+)[\'"]\)/',
                '/->description\([\'"]([^\'"]+)[\'"]\)/',
                '/Tables\\Actions\\[A-Z]\w+::make\([\'"]([^\'"]+)[\'"]\)/',
            ];
            
            foreach ($patterns as $pattern) {
                if (preg_match_all($pattern, $content, $matches)) {
                    foreach ($matches[1] as $string) {
                        // Skip if already using translation
                        if (strpos($string, '__') === false && !is_numeric($string)) {
                            $untranslatedStrings[$string] = ($untranslatedStrings[$string] ?? 0) + 1;
                        }
                    }
                }
            }
        }
    }
    
    if (!empty($untranslatedStrings)) {
        arsort($untranslatedStrings);
        echo "  Found " . count($untranslatedStrings) . " unique untranslated strings:\n";
        $count = 0;
        foreach ($untranslatedStrings as $string => $occurrences) {
            if ($count++ < 20) {
                echo "    - '$string' ($occurrences times)\n";
            }
        }
        if (count($untranslatedStrings) > 20) {
            echo "    ... and " . (count($untranslatedStrings) - 20) . " more\n";
        }
    } else {
        echo "  ✅ No untranslated strings found in resources!\n";
    }
}

// Check cache status
echo "\n💾 Cache Status:\n";
exec('cd /var/www/carphatian.ro/html && php artisan config:show app.locale 2>&1', $output, $return);
if ($return === 0) {
    echo "  " . implode("\n  ", $output) . "\n";
}

echo "\n✅ SCAN COMPLETE\n";
echo "=====================================\n";
echo "If translations are missing, run:\n";
echo "  1. php scan-all-admin-content.php (to extract ALL strings)\n";
echo "  2. php translate-to-romanian.php (to translate them)\n";
echo "  3. php artisan optimize:clear (to clear caches)\n";
