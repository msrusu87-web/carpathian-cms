#!/usr/bin/env php
<?php

/**
 * Simple Admin String Extractor
 * Extracts all translatable strings from Filament admin files
 */

echo "🔍 EXTRACTING ADMIN STRINGS\n";
echo "============================\n\n";

$baseDir = '/var/www/carphatian.ro/html';
$strings = [];

// Simple patterns to match - one at a time to avoid regex issues
$searchPatterns = [
    "->label('" => "')",
    '->label("' => '")',
    "->placeholder('" => "')",
    '->placeholder("' => '")',
    "->heading('" => "')",
    '->heading("' => '")',
    "->helperText('" => "')",
    '->helperText("' => '")',
    "TextColumn::make('" => "')",
    'TextColumn::make("' => '")',
    "TextInput::make('" => "')",
    'TextInput::make("' => '")',
    "Textarea::make('" => "')",
    'Textarea::make("' => '")',
    "Select::make('" => "')",
    'Select::make("' => '")',
    "Toggle::make('" => "')",
    'Toggle::make("' => '")',
];

function extractBetween($string, $start, $end) {
    $pos = strpos($string, $start);
    if ($pos === false) return [];
    
    $results = [];
    $offset = 0;
    
    while (($pos = strpos($string, $start, $offset)) !== false) {
        $pos += strlen($start);
        $endPos = strpos($string, $end, $pos);
        if ($endPos === false) break;
        
        $extracted = substr($string, $pos, $endPos - $pos);
        // Skip if contains __ or trans (already translated)
        if (strpos($extracted, '__') === false && strpos($extracted, 'trans') === false) {
            if (strlen($extracted) > 1 && !is_numeric($extracted)) {
                $results[] = $extracted;
            }
        }
        
        $offset = $endPos + 1;
    }
    
    return $results;
}

$dirs = [
    'app/Filament/Resources',
    'app/Filament/Pages',
    'app/Filament/Widgets',
];

foreach ($dirs as $dir) {
    $fullPath = "$baseDir/$dir";
    echo "📂 Scanning: $dir\n";
    
    if (!is_dir($fullPath)) {
        echo "   ⚠️  Not found\n";
        continue;
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($fullPath)
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            
            foreach ($searchPatterns as $start => $end) {
                $found = extractBetween($content, $start, $end);
                foreach ($found as $str) {
                    if (!isset($strings[$str])) {
                        $strings[$str] = 0;
                    }
                    $strings[$str]++;
                }
            }
        }
    }
}

arsort($strings);

echo "\n📊 FOUND " . count($strings) . " UNIQUE STRINGS\n";
echo "==========================================\n\n";

// Show top strings
$count = 0;
foreach ($strings as $str => $occurrences) {
    if ($count++ < 50) {
        echo sprintf("%3d. '%s' (%d times)\n", $count, $str, $occurrences);
    }
}

if (count($strings) > 50) {
    echo "\n... and " . (count($strings) - 50) . " more\n";
}

// Save all strings
$outputFile = '/home/ubuntu/carpathian-cms/extracted-strings.json';
file_put_contents($outputFile, json_encode(array_keys($strings), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "\n💾 All strings saved to: $outputFile\n";

// Check what needs translation
$roFile = "$baseDir/lang/ro/messages.php";
if (file_exists($roFile)) {
    $existing = include $roFile;
    $existingKeys = array_keys($existing);
    
    $needsTranslation = [];
    foreach (array_keys($strings) as $str) {
        $key = strtolower(preg_replace('/[^a-z0-9]+/i', '_', $str));
        $key = trim($key, '_');
        
        if (!isset($existing[$key]) && !isset($existing[$str])) {
            $needsTranslation[$key] = $str;
        }
    }
    
    echo "\n📋 Translation Status:\n";
    echo "   Existing translations: " . count($existing) . "\n";
    echo "   New strings needing translation: " . count($needsTranslation) . "\n\n";
    
    if (count($needsTranslation) > 0) {
        $needsFile = '/home/ubuntu/carpathian-cms/needs-translation.json';
        file_put_contents($needsFile, json_encode($needsTranslation, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo "💾 Strings needing translation saved to: $needsFile\n";
    }
}

echo "\n✅ EXTRACTION COMPLETE\n";
