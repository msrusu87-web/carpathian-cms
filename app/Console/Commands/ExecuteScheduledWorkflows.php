<?php

namespace App\Console\Commands;

use App\Services\WorkflowService;
use Illuminate\Console\Command;

class ExecuteScheduledWorkflows extends Command
{
    protected $signature = 'workflows:execute';
    protected $description = 'Execute all scheduled workflows';

    public function handle(WorkflowService $service)
    {
        $this->info('Executing scheduled workflows...');

        $executed = $service->executeScheduledWorkflows();

        $this->info("Executed {$executed} workflows.");
        return 0;
    }
}
