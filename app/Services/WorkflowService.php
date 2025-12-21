<?php

namespace App\Services;

use App\Models\Workflow;
use Carbon\Carbon;

class WorkflowService
{
    public function execute(Workflow $workflow): array
    {
        // Execute workflow actions
        $steps = json_decode($workflow->actions, true) ?? [];
        
        foreach ($steps as $step) {
            // Process each workflow step
            // This could trigger emails, webhooks, database updates, etc.
        }

        $workflow->update([
            'last_executed_at' => now(),
        ]);

        return ['status' => 'completed', 'steps' => count($steps)];
    }

    public function executeScheduledWorkflows(): int
    {
        $workflows = Workflow::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('last_executed_at')
                    ->orWhere('last_executed_at', '<', Carbon::now()->subHour());
            })
            ->get();

        $executed = 0;

        foreach ($workflows as $workflow) {
            $this->execute($workflow);
            $executed++;
        }

        return $executed;
    }
}
