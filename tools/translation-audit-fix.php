<?php
/**
 * Production Translation Audit + Fixer
 *
 * Goal:
 * - Find all translation keys referenced as messages.* in code (views + app)
 * - Ensure each locale has those keys in lang/{locale}/messages.php
 * - For missing keys, copy value from en (or ro fallback) so the UI never prints raw messages.xxx
 *
 * Usage (from project root):
 *   php tools/translation-audit-fix.php
 */

declare(strict_types=1);

final class TranslationAuditFix
{
    /** @var string[] */
    private array $locales = ['en', 'ro', 'de', 'es', 'fr', 'it'];

    private string $basePath;

    /** @var array<string, bool> */
    private array $usedKeys = [];

    /** @var array<string, array<string, mixed>> */
    private array $translationsByLocale = [];

    /** @var array<string, array<string, string>> */
    private array $addedByLocale = [];

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/');
    }

    public function run(): int
    {
        $this->scanForKeys();
        $this->loadTranslations();

        $this->ensureKeysPresent();

        $this->printSummary();

        return 0;
    }

    private function scanForKeys(): void
    {
        $paths = [
            $this->basePath . '/resources/views',
            $this->basePath . '/app',
        ];

        foreach ($paths as $path) {
            if (!is_dir($path)) {
                continue;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
            );

            /** @var SplFileInfo $file */
            foreach ($iterator as $file) {
                if (!$file->isFile()) {
                    continue;
                }

                $ext = strtolower($file->getExtension());
                if (!in_array($ext, ['php', 'blade.php'], true)) {
                    // Blade templates are still .php; keep php.
                    // Ignore others.
                }

                $contents = @file_get_contents($file->getPathname());
                if ($contents === false) {
                    continue;
                }

                $this->extractKeysFromContents($contents);
            }
        }
    }

    private function extractKeysFromContents(string $contents): void
    {
        $patterns = [
            // __('messages.key') or __("messages.key")
            "/__\(\s*['\"]messages\.([A-Za-z0-9_\.\-]+)['\"]\s*[\),]/",
            // @lang('messages.key')
            "/@lang\(\s*['\"]messages\.([A-Za-z0-9_\.\-]+)['\"]\s*\)/",
            // trans('messages.key')
            "/trans\(\s*['\"]messages\.([A-Za-z0-9_\.\-]+)['\"]\s*[\),]/",
        ];

        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $contents, $m) && !empty($m[1])) {
                foreach ($m[1] as $key) {
                    $this->usedKeys[$key] = true;
                }
            }
        }
    }

    private function loadTranslations(): void
    {
        foreach ($this->locales as $locale) {
            $file = $this->basePath . '/lang/' . $locale . '/messages.php';
            if (!is_file($file)) {
                $this->translationsByLocale[$locale] = [];
                continue;
            }

            $translations = include $file;
            $this->translationsByLocale[$locale] = is_array($translations) ? $translations : [];
        }
    }

    private function ensureKeysPresent(): void
    {
        $usedKeys = array_keys($this->usedKeys);
        sort($usedKeys);

        $en = $this->translationsByLocale['en'] ?? [];
        $ro = $this->translationsByLocale['ro'] ?? [];

        foreach ($this->locales as $locale) {
            $file = $this->basePath . '/lang/' . $locale . '/messages.php';
            if (!is_file($file)) {
                // Skip missing locale files; do not create new ones silently.
                continue;
            }

            $existing = $this->translationsByLocale[$locale] ?? [];
            $missing = [];

            foreach ($usedKeys as $key) {
                if (array_key_exists($key, $existing)) {
                    continue;
                }

                $value = null;
                if (array_key_exists($key, $en) && is_scalar($en[$key])) {
                    $value = (string) $en[$key];
                } elseif (array_key_exists($key, $ro) && is_scalar($ro[$key])) {
                    $value = (string) $ro[$key];
                } else {
                    $value = $this->humanizeKey($key);
                }

                $missing[$key] = $value;
            }

            if (empty($missing)) {
                continue;
            }

            $this->appendMissingKeysToMessagesFile($file, $missing);
            $this->addedByLocale[$locale] = $missing;
        }
    }

    private function appendMissingKeysToMessagesFile(string $filePath, array $missing): void
    {
        $original = file_get_contents($filePath);
        if ($original === false) {
            throw new RuntimeException("Unable to read {$filePath}");
        }

        $timestamp = date('Ymd_His');
        $backupPath = $filePath . '.bak.' . $timestamp;
        file_put_contents($backupPath, $original);

        // Insert before the last closing token:
        // - Modern style: return [ ... ];
        // - Legacy/var_export style: return array ( ... );
        $pos = strrpos($original, '];');
        $closing = '];';

        $posAlt = strrpos($original, ');');
        if ($posAlt !== false && ($pos === false || $posAlt > $pos)) {
            $pos = $posAlt;
            $closing = ');';
        }

        if ($pos === false) {
            // Fallback: do not rewrite unknown format.
            throw new RuntimeException("Unexpected messages.php format (missing closing ]; or );): {$filePath}");
        }

        $indent = "  ";
        $lines = "\n\n" . $indent . "// Added automatically by tools/translation-audit-fix.php on " . date('c') . "\n";

        foreach ($missing as $key => $value) {
            $lines .= $indent . "'" . $this->escapePhpSingleQuoted((string) $key) . "' => '" . $this->escapePhpSingleQuoted((string) $value) . "',\n";
        }

        $updated = substr($original, 0, $pos) . rtrim($lines, "\n") . "\n" . substr($original, $pos);

        file_put_contents($filePath, $updated);
    }

    private function escapePhpSingleQuoted(string $s): string
    {
        // In single-quoted PHP strings, only backslash and single quote need escaping.
        return str_replace(["\\", "'"], ["\\\\", "\\'"], $s);
    }

    private function humanizeKey(string $key): string
    {
        $key = str_replace(['.', '_', '-'], ' ', $key);
        $key = preg_replace('/\s+/', ' ', $key) ?: $key;
        $key = trim($key);
        return $key === '' ? '—' : ucfirst($key);
    }

    private function printSummary(): void
    {
        $usedCount = count($this->usedKeys);
        echo "\nTranslation audit finished.\n";
        echo "Keys referenced in code: {$usedCount}\n\n";

        foreach ($this->locales as $locale) {
            $added = $this->addedByLocale[$locale] ?? [];
            echo sprintf("- %s: added %d missing key(s)\n", $locale, count($added));
        }

        echo "\nBackups: lang/{locale}/messages.php.bak.YYYYMMDD_HHMMSS\n";
        echo "\nIMPORTANT: Run 'php artisan optimize:clear' after this so views/routes are refreshed.\n\n";
    }
}

$basePath = dirname(__DIR__);
$runner = new TranslationAuditFix($basePath);
exit($runner->run());
