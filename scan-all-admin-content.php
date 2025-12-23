#!/usr/bin/env php
<?php

/**
 * Complete Admin Content Scanner
 * Extracts ALL translatable strings from Filament admin panel
 */

echo "🔍 SCANNING ALL ADMIN CONTENT FOR TRANSLATION\n";
echo "==============================================\n\n";

$baseDir = '/var/www/carphatian.ro/html';
$extractedStrings = [];

// Directories to scan
$dirsToScan = [
    'app/Filament/Resources',
    'app/Filament/Pages',
    'app/Filament/Widgets',
    'app/Filament/Clusters',
    'app/Providers/Filament',
];

// Patterns to extract translatable strings
$patterns = [
    // Labels
    '/->label\([\'"]([^\'"]+)[\'"]\)/' => 'label',
    '/->placeholder\([\'"]([^\'"]+)[\'"]\)/' => 'placeholder',
    '/->helperText\([\'"]([^\'"]+)[\'"]\)/' => 'helper_text',
    '/->hint\([\'"]([^\'"]+)[\'"]\)/' => 'hint',
    '/->hintIcon\([\'"]([^\'"]+)[\'"]\)/' => 'hint_icon',
    
    // Headings and descriptions
    '/->heading\([\'"]([^\'"]+)[\'"]\)/' => 'heading',
    '/->description\([\'"]([^\'"]+)[\'"]\)/' => 'description',
    '/->subheading\([\'"]([^\'"]+)[\'"]\)/' => 'subheading',
    
    // Navigation
    '/->navigationLabel\([\'"]([^\'"]+)[\'"]\)/' => 'navigation_label',
    '/->navigationGroup\([\'"]([^\'"]+)[\'"]\)/' => 'navigation_group',
    '/navigationLabel\s*=\s*[\'"]([^\'"]+)[\'"]/' => 'navigation_label_property',
    '/navigationGroup\s*=\s*[\'"]([^\'"]+)[\'"]/' => 'navigation_group_property',
    
    // Actions
    '/Action::make\([\'"]([^\'"]+)[\'"]\)/' => 'action',
    '/BulkAction::make\([\'"]([^\'"]+)[\'"]\)/' => 'bulk_action',
    '/CreateAction::make\([\'"]([^\'"]+)[\'"]\)/' => 'create_action',
    '/EditAction::make\([\'"]([^\'"]+)[\'"]\)/' => 'edit_action',
    '/DeleteAction::make\([\'"]([^\'"]+)[\'"]\)/' => 'delete_action',
    '/ViewAction::make\([\'"]([^\'"]+)[\'"]\)/' => 'view_action',
    
    // Table columns
    '/TextColumn::make\([\'"]([^\'"]+)[\'"]\)/' => 'text_column',
    '/ImageColumn::make\([\'"]([^\'"]+)[\'"]\)/' => 'image_column',
    '/BadgeColumn::make\([\'"]([^\'"]+)[\'"]\)/' => 'badge_column',
    '/BooleanColumn::make\([\'"]([^\'"]+)[\'"]\)/' => 'boolean_column',
    
    // Form fields
    '/TextInput::make\([\'"]([^\'"]+)[\'"]\)/' => 'text_input',
    '/Textarea::make\([\'"]([^\'"]+)[\'"]\)/' => 'textarea',
    '/Select::make\([\'"]([^\'"]+)[\'"]\)/' => 'select',
    '/Toggle::make\([\'"]([^\'"]+)[\'"]\)/' => 'toggle',
    '/Checkbox::make\([\'"]([^\'"]+)[\'"]\)/' => 'checkbox',
    '/DatePicker::make\([\'"]([^\'"]+)[\'"]\)/' => 'date_picker',
    '/FileUpload::make\([\'"]([^\'"]+)[\'"]\)/' => 'file_upload',
    '/RichEditor::make\([\'"]([^\'"]+)[\'"]\)/' => 'rich_editor',
    
    // Tabs
    '/Tab::make\([\'"]([^\'"]+)[\'"]\)/' => 'tab',
    '/Tabs\\Tab::make\([\'"]([^\'"]+)[\'"]\)/' => 'tabs_tab',
    
    // Notifications
    '/Notification::make\(\)->title\([\'"]([^\'"]+)[\'"]\)/' => 'notification_title',
    '/->successNotificationTitle\([\'"]([^\'"]+)[\'"]\)/' => 'success_notification',
    
    // Misc
    '/->modalHeading\([\'"]([^\'"]+)[\'"]\)/' => 'modal_heading',
    '/->modalButton\([\'"]([^\'"]+)[\'"]\)/' => 'modal_button',
    '/->tooltip\([\'"]([^\'"]+)[\'"]\)/' => 'tooltip',
];

foreach ($dirsToScan as $dir) {
    $fullPath = "$baseDir/$dir";
    echo "📂 Scanning: $dir\n";
    
    if (!is_dir($fullPath)) {
        echo "   ⚠️  Directory not found\n";
        continue;
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($fullPath)
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            $relativePath = str_replace($baseDir . '/', '', $file->getPathname());
            
            foreach ($patterns as $pattern => $type) {
                if (preg_match_all($pattern, $content, $matches)) {
                    foreach ($matches[1] as $string) {
                        // Skip if already using translation helper
                        if (strpos($string, '__') !== false || strpos($string, 'trans') !== false) {
                            continue;
                        }
                        
                        // Skip numeric strings and single characters
                        if (is_numeric($string) || strlen($string) <= 1) {
                            continue;
                        }
                        
                        // Skip variable references
                        if (strpos($string, '$') !== false || strpos($string, '{') !== false) {
                            continue;
                        }
                        
                        // Store the string
                        if (!isset($extractedStrings[$string])) {
                            $extractedStrings[$string] = [
                                'type' => $type,
                                'files' => [],
                                'count' => 0,
                            ];
                        }
                        
                        $extractedStrings[$string]['count']++;
                        if (!in_array($relativePath, $extractedStrings[$string]['files'])) {
                            $extractedStrings[$string]['files'][] = $relativePath;
                        }
                    }
                }
            }
        }
    }
}

echo "\n📊 EXTRACTION COMPLETE\n";
echo "======================\n\n";
echo "Found " . count($extractedStrings) . " unique translatable strings\n\n";

// Sort by frequency
uasort($extractedStrings, function($a, $b) {
    return $b['count'] - $a['count'];
});

// Save to JSON file
$outputFile = __DIR__ . '/extracted-admin-strings.json';
file_put_contents($outputFile, json_encode($extractedStrings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "💾 Saved to: $outputFile\n\n";

// Display top 50 strings
echo "🔝 Top 50 Most Common Strings:\n";
echo "================================\n";
$count = 0;
foreach ($extractedStrings as $string => $data) {
    if ($count++ >= 50) break;
    echo sprintf("  %3d. '%s' (%d times, type: %s)\n", 
        $count, 
        $string, 
        $data['count'], 
        $data['type']
    );
}

// Generate translation keys
echo "\n🔑 Generating Translation Keys:\n";
echo "=================================\n";

$translationKeys = [];
foreach ($extractedStrings as $string => $data) {
    // Convert to snake_case key
    $key = strtolower($string);
    $key = preg_replace('/[^a-z0-9]+/', '_', $key);
    $key = trim($key, '_');
    
    $translationKeys[$key] = $string;
}

// Save translation keys
$keysFile = __DIR__ . '/translation-keys-to-add.json';
file_put_contents($keysFile, json_encode($translationKeys, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "💾 Translation keys saved to: $keysFile\n";
echo "   Total keys: " . count($translationKeys) . "\n\n";

// Check what's already in Romanian translation file
$roFile = "$baseDir/lang/ro/messages.php";
if (file_exists($roFile)) {
    $existingTranslations = include $roFile;
    $existingKeys = array_keys($existingTranslations);
    
    $missingKeys = array_diff(array_keys($translationKeys), $existingKeys);
    
    echo "📋 Translation Status:\n";
    echo "   Existing keys: " . count($existingKeys) . "\n";
    echo "   New keys needed: " . count($missingKeys) . "\n\n";
    
    if (count($missingKeys) > 0) {
        echo "⚠️  Missing Keys (first 30):\n";
        foreach (array_slice($missingKeys, 0, 30) as $key) {
            echo "   - $key\n";
        }
        if (count($missingKeys) > 30) {
            echo "   ... and " . (count($missingKeys) - 30) . " more\n";
        }
    }
}

echo "\n✅ NEXT STEPS:\n";
echo "==============\n";
echo "1. Review extracted-admin-strings.json\n";
echo "2. Run: php translate-extracted-strings.php\n";
echo "3. Deploy translations to production\n";
echo "4. Clear caches: php artisan optimize:clear\n";
