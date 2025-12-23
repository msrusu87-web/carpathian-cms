<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Widget;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportMissingDbTranslationsCommand extends Command
{
    protected $signature = 'translations:export-missing-db
        {--locale=en : Target locale to export missing translations for}
        {--from=ro : Source locale to include as reference}
        {--out= : Output path (defaults to storage/app/missing-db-<locale>.json)}
        {--limit=0 : Limit rows per model (0 = no limit)}';

    protected $description = 'Export missing Spatie-translatable DB fields for a locale into a JSON file (for manual/AI translation)';

    public function handle(): int
    {
        $target = (string) $this->option('locale');
        $from = (string) $this->option('from');
        $limit = (int) $this->option('limit');

        $out = (string) ($this->option('out') ?: ("missing-db-{$target}.json"));

        $models = [
            'pages' => Page::class,
            'posts' => Post::class,
            'products' => Product::class,
            'product_categories' => ProductCategory::class,
            'categories' => Category::class,
            'widgets' => Widget::class,
        ];

        $payload = [
            'generated_at' => now()->toIso8601String(),
            'target_locale' => $target,
            'source_locale' => $from,
            'items' => [],
        ];

        $total = 0;

        foreach ($models as $label => $class) {
            $probe = new $class();
            $fields = method_exists($probe, 'getTranslatableAttributes')
                ? $probe->getTranslatableAttributes()
                : (property_exists($probe, 'translatable') ? (array) $probe->translatable : []);

            if (empty($fields)) {
                continue;
            }

            $q = $class::query();
            if ($limit > 0) {
                $q->limit($limit);
            }

            $items = $q->get();

            foreach ($items as $item) {
                $id = $item->getKey();

                foreach ($fields as $field) {
                    $current = $item->getTranslation($field, $target, false);
                    if ($current !== null && $current !== '') {
                        continue;
                    }

                    $source = $item->getTranslation($field, $from, false);
                    if ($source === null || $source === '') {
                        continue;
                    }

                    $payload['items'][] = [
                        'model' => $label,
                        'class' => $class,
                        'id' => $id,
                        'field' => $field,
                        'from' => $source,
                        'to' => '',
                    ];
                    $total++;
                }
            }
        }

        Storage::disk('local')->put($out, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->info("✅ Exported {$total} missing field(s) to storage/app/{$out}");
        $this->line('Fill the "to" values, then import with: php artisan translations:import-db --locale=' . $target . ' --file=' . $out);

        return 0;
    }
}
