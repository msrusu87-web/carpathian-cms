<?php

/**
 * Extract Admin Panel Translations
 * Scans Filament resources, clusters, pages for untranslated strings
 */

$baseDir = __DIR__;
$translationStrings = [];

// Directories to scan
$scanDirs = [
    '/var/www/carphatian.ro/html/app/Filament/Resources',
    '/var/www/carphatian.ro/html/app/Filament/Clusters',
    '/var/www/carphatian.ro/html/app/Filament/Pages',
    '/var/www/carphatian.ro/html/app/Filament/Widgets',
];

echo "🔍 Scanning Filament files for translatable strings...\n\n";

foreach ($scanDirs as $dir) {
    if (!is_dir($dir)) continue;
    
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir)
    );
    
    foreach ($files as $file) {
        if ($file->isDir() || $file->getExtension() !== 'php') continue;
        
        $content = file_get_contents($file->getPathname());
        $relativePath = str_replace('/var/www/carphatian.ro/html/', '', $file->getPathname());
        
        // Extract cluster names
        if (preg_match('/public static function getNavigationLabel\(\).*?return\s+[\'"]([^\'"]+)[\'"]/', $content, $matches)) {
            $translationStrings[$matches[1]] = [
                'type' => 'cluster',
                'file' => $relativePath,
                'context' => 'Navigation cluster name'
            ];
        }
        
        // Extract ->label('Text') that are NOT using __()
        preg_match_all('/->label\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
        foreach ($matches[1] as $label) {
            if (!isset($translationStrings[$label])) {
                $translationStrings[$label] = [
                    'type' => 'label',
                    'file' => $relativePath,
                    'context' => 'Form/Table label'
                ];
            }
        }
        
        // Extract ->placeholder('Text')
        preg_match_all('/->placeholder\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
        foreach ($matches[1] as $placeholder) {
            if (!isset($translationStrings[$placeholder])) {
                $translationStrings[$placeholder] = [
                    'type' => 'placeholder',
                    'file' => $relativePath,
                    'context' => 'Input placeholder'
                ];
            }
        }
        
        // Extract ->helperText('Text')
        preg_match_all('/->helperText\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
        foreach ($matches[1] as $helper) {
            if (!isset($translationStrings[$helper])) {
                $translationStrings[$helper] = [
                    'type' => 'helper',
                    'file' => $relativePath,
                    'context' => 'Helper text'
                ];
            }
        }
        
        // Extract array keys in options()
        preg_match_all('/[\'"]([a-z_]+)[\'"]\s*=>\s*[\'"]([^\'"]+)[\'"]/', $content, $matches);
        for ($i = 0; $i < count($matches[1]); $i++) {
            $value = $matches[2][$i];
            if (!isset($translationStrings[$value]) && strlen($value) > 1 && !is_numeric($value)) {
                $translationStrings[$value] = [
                    'type' => 'option',
                    'file' => $relativePath,
                    'context' => 'Select option'
                ];
            }
        }
    }
}

// Sort alphabetically
ksort($translationStrings);

echo "✅ Found " . count($translationStrings) . " unique translatable strings\n\n";
echo "📝 Sample strings found:\n";
$count = 0;
foreach ($translationStrings as $string => $info) {
    if ($count++ >= 20) break;
    echo "  - \"$string\" ({$info['type']})\n";
}

// Export to JSON for review
$outputFile = $baseDir . '/admin-translations-to-add.json';
file_put_contents($outputFile, json_encode($translationStrings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "\n💾 Full list saved to: admin-translations-to-add.json\n";
echo "📊 Total strings: " . count($translationStrings) . "\n";

