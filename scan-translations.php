<?php
/**
 * Translation Scanner Script for Carpathian CMS
 * Scans all Blade templates for missing translation keys
 * Run: php scan-translations.php
 */

class TranslationScanner
{
    private $basePath;
    private $viewsPath;
    private $langPath;
    private $missingKeys = [];
    private $existingKeys = [];
    private $languages = ['en', 'ro', 'de', 'es', 'fr', 'it'];
    private $stats = [
        'files_scanned' => 0,
        'total_keys_found' => 0,
        'missing_keys' => 0,
        'existing_keys' => 0,
    ];

    public function __construct()
    {
        $this->basePath = __DIR__;
        $this->viewsPath = $this->basePath . '/resources/views';
        $this->langPath = $this->basePath . '/lang';
    }

    /**
     * Main execution method
     */
    public function scan()
    {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "Carpathian CMS - Translation Scanner\n";
        echo str_repeat("=", 80) . "\n\n";

        // Load existing translations
        $this->loadExistingTranslations();

        // Scan all blade files
        $this->scanBladeFiles($this->viewsPath);

        // Display results
        $this->displayResults();

        // Generate missing translations report
        $this->generateReport();
        
        return $this->missingKeys;
    }

    /**
     * Load existing translations from lang files
     */
    private function loadExistingTranslations()
    {
        echo "Loading existing translations...\n";
        
        foreach ($this->languages as $lang) {
            $messagesFile = $this->langPath . '/' . $lang . '/messages.php';
            
            if (file_exists($messagesFile)) {
                $translations = include $messagesFile;
                if (is_array($translations)) {
                    foreach (array_keys($translations) as $key) {
                        $this->existingKeys[$lang][$key] = true;
                    }
                    echo "  ✓ Loaded {$lang}: " . count($translations) . " keys\n";
                }
            } else {
                echo "  ✗ Missing: {$messagesFile}\n";
            }
        }
        
        echo "\n";
    }

    /**
     * Recursively scan blade files
     */
    private function scanBladeFiles($directory)
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $this->scanFile($file->getPathname());
            }
        }
    }

    /**
     * Scan a single file for translation keys
     */
    private function scanFile($filePath)
    {
        $content = file_get_contents($filePath);
        $relativePath = str_replace($this->basePath . '/', '', $filePath);
        
        $this->stats['files_scanned']++;

        // Pattern to match __('messages.xxx') or {{ __('messages.xxx') }}
        preg_match_all("/__\(['\"]messages\.([a-z_]+)['\"]\)/i", $content, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key) {
                $this->stats['total_keys_found']++;
                $this->checkTranslationKey($key, $relativePath);
            }
        }

        // Also check for plain messages.xxx patterns that aren't being translated
        preg_match_all("/messages\.([a-z_]+)(?!['\"])/i", $content, $plainMatches);
        
        if (!empty($plainMatches[1])) {
            foreach ($plainMatches[1] as $key) {
                // Check if this is NOT wrapped in __() - these are errors
                $context = $this->getContext($content, 'messages.' . $key);
                $pattern = "/__\\(['\"]\]messages\\." . preg_quote($key) . "['\"]\)/";
                if (!preg_match($pattern, $context)) {
                    $this->missingKeys['UNTRANSLATED'][$key][] = $relativePath;
                }
            }
        }
    }

    /**
     * Get context around a string match
     */
    private function getContext($content, $search, $contextLength = 100)
    {
        $pos = strpos($content, $search);
        if ($pos === false) {
            return '';
        }
        
        $start = max(0, $pos - $contextLength);
        $length = $contextLength * 2 + strlen($search);
        
        return substr($content, $start, $length);
    }

    /**
     * Check if translation key exists in all languages
     */
    private function checkTranslationKey($key, $file)
    {
        $keyExists = true;
        
        foreach ($this->languages as $lang) {
            if (!isset($this->existingKeys[$lang][$key])) {
                $this->missingKeys[$lang][$key][] = $file;
                $keyExists = false;
                $this->stats['missing_keys']++;
            }
        }
        
        if ($keyExists) {
            $this->stats['existing_keys']++;
        }
    }

    /**
     * Display scan results
     */
    private function displayResults()
    {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "SCAN RESULTS\n";
        echo str_repeat("=", 80) . "\n\n";

        echo "Files scanned: {$this->stats['files_scanned']}\n";
        echo "Total translation keys found: {$this->stats['total_keys_found']}\n";
        echo "Existing keys: {$this->stats['existing_keys']}\n";
        echo "Missing keys: {$this->stats['missing_keys']}\n\n";

        // Show untranslated messages (messages.xxx without __())
        if (isset($this->missingKeys['UNTRANSLATED']) && count($this->missingKeys['UNTRANSLATED']) > 0) {
            echo "\n" . str_repeat("-", 80) . "\n";
            echo "WARNING: Found " . count($this->missingKeys['UNTRANSLATED']) . " UNTRANSLATED messages (not wrapped in __())\n";
            echo str_repeat("-", 80) . "\n";
            
            foreach ($this->missingKeys['UNTRANSLATED'] as $key => $files) {
                echo "\n  messages.{$key}\n";
                foreach ($files as $file) {
                    echo "    - {$file}\n";
                }
            }
        }

        // Show missing keys per language
        foreach ($this->languages as $lang) {
            if (isset($this->missingKeys[$lang]) && count($this->missingKeys[$lang]) > 0) {
                echo "\n" . str_repeat("-", 80) . "\n";
                echo "Missing translations in [{$lang}]: " . count($this->missingKeys[$lang]) . " keys\n";
                echo str_repeat("-", 80) . "\n";
                
                $displayCount = 0;
                foreach ($this->missingKeys[$lang] as $key => $files) {
                    if ($displayCount < 20) {  // Show first 20
                        echo "\n  {$key}\n";
                        foreach (array_unique($files) as $file) {
                            echo "    - {$file}\n";
                        }
                        $displayCount++;
                    }
                }
                
                if (count($this->missingKeys[$lang]) > 20) {
                    echo "\n  ... and " . (count($this->missingKeys[$lang]) - 20) . " more keys\n";
                }
            }
        }

        echo "\n";
    }

    /**
     * Generate detailed JSON report
     */
    private function generateReport()
    {
        $reportFile = $this->basePath . '/translation-scan-report.json';
        
        $report = [
            'scan_date' => date('Y-m-d H:i:s'),
            'statistics' => $this->stats,
            'missing_translations' => $this->missingKeys,
        ];

        file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        echo "Report saved: translation-scan-report.json\n\n";
    }
}

// Execute scanner
$scanner = new TranslationScanner();
$missingKeys = $scanner->scan();

// Return exit code based on results
exit(empty($missingKeys) ? 0 : 1);
