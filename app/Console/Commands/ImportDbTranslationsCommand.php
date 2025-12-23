<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportDbTranslationsCommand extends Command
{
    protected $signature = 'translations:import-db
        {--locale=en : Locale to import into}
        {--file= : JSON file path relative to storage/app (required)}
        {--dry-run : Validate and report changes without writing}
        {--limit=0 : Limit number of items applied (0 = no limit)}';

    protected $description = 'Import DB translations from a JSON file created by translations:export-missing-db';

    public function handle(): int
    {
        $locale = (string) $this->option('locale');
        $file = (string) ($this->option('file') ?? '');
        $dryRun = (bool) $this->option('dry-run');
        $limit = (int) $this->option('limit');

        if ($file === '') {
            $this->error('--file is required (path relative to storage/app)');
            return 1;
        }

        if (!Storage::disk('local')->exists($file)) {
            $this->error("File not found in storage/app: {$file}");
            return 1;
        }

        $json = Storage::disk('local')->get($file);
        $data = json_decode($json, true);
        if (!is_array($data) || !isset($data['items']) || !is_array($data['items'])) {
            $this->error('Invalid JSON format.');
            return 1;
        }

        $applied = 0;
        $skipped = 0;

        foreach ($data['items'] as $item) {
            if ($limit > 0 && $applied >= $limit) {
                break;
            }

            $class = $item['class'] ?? null;
            $id = $item['id'] ?? null;
            $field = $item['field'] ?? null;
            $to = $item['to'] ?? '';

            if (!is_string($class) || !class_exists($class) || $id === null || !is_string($field)) {
                $skipped++;
                continue;
            }

            $to = is_string($to) ? trim($to) : '';
            if ($to === '') {
                $skipped++;
                continue;
            }

            $model = $class::find($id);
            if (!$model) {
                $skipped++;
                continue;
            }

            // Ensure field is translatable.
            if (!method_exists($model, 'getTranslatableAttributes') || !in_array($field, $model->getTranslatableAttributes(), true)) {
                $skipped++;
                continue;
            }

            if (!$dryRun) {
                $model->setTranslation($field, $locale, $to);
                $model->save();
            }

            $applied++;
        }

        $this->info(($dryRun ? '✅ Dry-run OK.' : '✅ Imported.') . " Applied: {$applied}. Skipped: {$skipped}.");
        return 0;
    }
}
