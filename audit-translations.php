<?php
/**
 * Translation QA & Audit Script for Carpathian CMS
 * Verifies all translations are working correctly
 * Run: php audit-translations.php
 */

class TranslationAuditor
{
    private $basePath;
    private $langPath;
    private $languages = ['en', 'ro', 'de', 'es', 'fr', 'it'];
    private $issues = [];
    private $stats = [
        'total_keys' => 0,
        'languages_checked' => 0,
        'issues_found' => 0,
    ];

    public function __construct()
    {
        $this->basePath = __DIR__;
        $this->langPath = $this->basePath . '/lang';
    }

    public function audit()
    {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "Carpathian CMS - Translation QA & Audit\n";
        echo str_repeat("=", 80) . "\n\n";

        echo "Checking translation files...\n\n";

        // Check each language file
        $enTranslations = $this->loadTranslations('en');
        
        if (empty($enTranslations)) {
            echo "ERROR: Could not load English translations!\n";
            return false;
        }

        $this->stats['total_keys'] = count($enTranslations);
        echo "Base language (EN) has {$this->stats['total_keys']} keys\n\n";

        foreach ($this->languages as $lang) {
            $this->checkLanguage($lang, $enTranslations);
        }

        $this->displayResults();
        $this->generateAuditReport();

        return empty($this->issues);
    }

    private function loadTranslations($lang)
    {
        $file = $this->langPath . '/' . $lang . '/messages.php';
        
        if (!file_exists($file)) {
            return [];
        }

        $translations = include $file;
        return is_array($translations) ? $translations : [];
    }

    private function checkLanguage($lang, $baseTranslations)
    {
        $this->stats['languages_checked']++;
        echo "Checking [{$lang}]...\n";

        $translations = $this->loadTranslations($lang);

        if (empty($translations)) {
            $this->issues[] = [
                'language' => $lang,
                'type' => 'CRITICAL',
                'message' => 'Translation file missing or empty'
            ];
            echo "  ERROR: Translation file missing!\n\n";
            return;
        }

        $missing = [];
        $empty = [];
        $count = count($translations);

        // Check for missing keys
        foreach ($baseTranslations as $key => $value) {
            if (!isset($translations[$key])) {
                $missing[] = $key;
            } elseif (empty($translations[$key]) || trim($translations[$key]) === '') {
                $empty[] = $key;
            }
        }

        // Report results
        echo "  Keys: $count\n";
        
        if (!empty($missing)) {
            echo "  WARNING: " . count($missing) . " missing keys\n";
            $this->issues[] = [
                'language' => $lang,
                'type' => 'MISSING_KEYS',
                'count' => count($missing),
                'keys' => array_slice($missing, 0, 10)  // Store first 10 for report
            ];
            $this->stats['issues_found'] += count($missing);
        }

        if (!empty($empty)) {
            echo "  WARNING: " . count($empty) . " empty translations\n";
            $this->issues[] = [
                'language' => $lang,
                'type' => 'EMPTY_VALUES',
                'count' => count($empty),
                'keys' => array_slice($empty, 0, 10)
            ];
            $this->stats['issues_found'] += count($empty);
        }

        if (empty($missing) && empty($empty)) {
            echo "  STATUS: OK\n";
        }

        echo "\n";
    }

    private function displayResults()
    {
        echo str_repeat("=", 80) . "\n";
        echo "AUDIT RESULTS\n";
        echo str_repeat("=", 80) . "\n\n";

        echo "Languages Checked: {$this->stats['languages_checked']}\n";
        echo "Total Translation Keys: {$this->stats['total_keys']}\n";
        echo "Issues Found: {$this->stats['issues_found']}\n\n";

        if (empty($this->issues)) {
            echo "RESULT: ALL CLEAR - No issues found!\n";
        } else {
            echo "RESULT: " . count($this->issues) . " issues need attention\n\n";

            foreach ($this->issues as $issue) {
                echo str_repeat("-", 80) . "\n";
                echo "[{$issue['language']}] {$issue['type']}\n";
                
                if (isset($issue['message'])) {
                    echo "  {$issue['message']}\n";
                } elseif (isset($issue['count'])) {
                    echo "  Found: {$issue['count']} problems\n";
                    if (!empty($issue['keys'])) {
                        echo "  Sample keys: " . implode(', ', array_slice($issue['keys'], 0, 5)) . "\n";
                    }
                }
                echo "\n";
            }
        }

        echo "\n";
    }

    private function generateAuditReport()
    {
        $reportFile = $this->basePath . '/translation-audit-report.json';
        
        $report = [
            'audit_date' => date('Y-m-d H:i:s'),
            'statistics' => $this->stats,
            'issues' => $this->issues,
            'status' => empty($this->issues) ? 'PASSED' : 'NEEDS_ATTENTION'
        ];

        file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        echo "Audit report saved: translation-audit-report.json\n\n";
    }
}

// Test actual Laravel translation loading
function testLaravelTranslations() {
    echo "\n" . str_repeat("=", 80) . "\n";
    echo "Testing Laravel Translation Loading\n";
    echo str_repeat("=", 80) . "\n\n";

    $testKeys = [
        'messages.home',
        'messages.shop',
        'messages.blog',
        'messages.contact',
        'messages.portfolio',
        'messages.add_to_cart',
        'messages.prices_include_vat',
        'messages.quantity',
    ];

    foreach (['en', 'ro'] as $locale) {
        echo "Testing locale: {$locale}\n";
        
        foreach ($testKeys as $key) {
            $translation = trans($key, [], $locale);
            $status = ($translation !== $key && !str_contains($translation, 'messages.')) ? 'OK' : 'FAIL';
            echo "  {$key} => {$translation} [{$status}]\n";
        }
        echo "\n";
    }
}

// Execute auditor
$auditor = new TranslationAuditor();
$success = $auditor->audit();

// Test Laravel if available
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "\nNote: Run this inside Laravel app for full translation testing\n";
    echo "Command: php artisan tinker\n";
    echo "Then: __('messages.home')\n\n";
}

exit($success ? 0 : 1);
