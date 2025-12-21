<?php

namespace App\Console\Commands;

use App\Services\SearchService;
use Illuminate\Console\Command;

class ReindexSearchCommand extends Command
{
    protected $signature = 'search:reindex {--type= : Specific content type to reindex}';
    protected $description = 'Reindex all searchable content';

    public function handle(SearchService $service)
    {
        $type = $this->option('type');
        
        $this->info($type ? "Reindexing {$type}..." : 'Reindexing all content...');

        $count = $service->reindexAll($type);

        $this->info("Successfully reindexed {$count} items.");
        return 0;
    }
}
