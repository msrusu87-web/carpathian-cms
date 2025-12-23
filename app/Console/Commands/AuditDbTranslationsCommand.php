<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Widget;
use Illuminate\Console\Command;

class AuditDbTranslationsCommand extends Command
{
    protected $signature = 'translations:audit-db
        {--locales=ro,en,de,es,fr,it : Comma-separated locales to audit}
        {--fill-from= : Locale to copy from when filling missing translations (e.g. ro)}
        {--write : Actually write changes to DB (otherwise dry-run)}
        {--limit=0 : Limit rows per model (0 = no limit)}';

    protected $description = 'Audit (and optionally fill) missing Spatie-translatable DB fields across locales';

    public function handle(): int
    {
        $locales = array_values(array_filter(array_map('trim', explode(',', (string) $this->option('locales')))));
        $fillFrom = $this->option('fill-from');
        $write = (bool) $this->option('write');
        $limit = (int) $this->option('limit');

        if (empty($locales)) {
            $this->error('No locales provided.');
            return 1;
        }

        if ($fillFrom && !in_array($fillFrom, $locales, true)) {
            $this->warn("fill-from locale '{$fillFrom}' is not in locales list; adding it.");
            $locales[] = $fillFrom;
        }

        $models = [
            'pages' => Page::class,
            'posts' => Post::class,
            'products' => Product::class,
            'product_categories' => ProductCategory::class,
            'categories' => Category::class,
            'widgets' => Widget::class,
        ];

        $this->info('🔎 Auditing DB translations');
        $this->line('Locales: ' . implode(', ', $locales));
        if ($fillFrom) {
            $this->line('Fill missing from: ' . $fillFrom . ' (' . ($write ? 'WRITE' : 'DRY-RUN') . ')');
        }

        $summaryRows = [];
        $totalFixes = 0;

        foreach ($models as $label => $class) {
            /** @var object $probe */
            $probe = new $class();
            $fields = method_exists($probe, 'getTranslatableAttributes')
                ? $probe->getTranslatableAttributes()
                : (property_exists($probe, 'translatable') ? (array) $probe->translatable : []);

            if (empty($fields)) {
                $summaryRows[] = [$label, '0', '0', '0'];
                continue;
            }

            $q = $class::query();
            if ($limit > 0) {
                $q->limit($limit);
            }

            $items = $q->get();
            $missingCount = 0;
            $fixedCount = 0;

            foreach ($items as $item) {
                $changed = false;

                foreach ($fields as $field) {
                    foreach ($locales as $loc) {
                        $val = $item->getTranslation($field, $loc, false);
                        if ($val === null || $val === '') {
                            $missingCount++;

                            if ($fillFrom) {
                                $fallback = $item->getTranslation($field, $fillFrom, false);
                                if ($fallback !== null && $fallback !== '') {
                                    if ($write) {
                                        $item->setTranslation($field, $loc, $fallback);
                                        $changed = true;
                                    }
                                    $fixedCount++;
                                }
                            }
                        }
                    }
                }

                if ($changed) {
                    $item->save();
                }
            }

            $totalFixes += $fixedCount;
            $summaryRows[] = [$label, (string) $items->count(), (string) $missingCount, (string) $fixedCount];
        }

        $this->newLine();
        $this->table(['Model', 'Rows', 'Missing translations', 'Filled'], $summaryRows);

        if ($fillFrom && !$write) {
            $this->warn('Dry-run only. Re-run with --write to apply changes.');
        }

        if ($totalFixes > 0 && $write) {
            $this->info("✅ Wrote {$totalFixes} filled translations");
        }

        return 0;
    }
}
