<?php

namespace App\Console\Commands;

use App\Services\SystemMonitoringService;
use Illuminate\Console\Command;

class SystemHealthCommand extends Command
{
    protected $signature = 'system:health-check';
    protected $description = 'Run system health checks and display status';

    public function handle(SystemMonitoringService $service)
    {
        $this->info('Running system health checks...');
        $this->newLine();

        $checks = $service->runHealthChecks();

        foreach ($checks as $check => $result) {
            $status = match($result['status']) {
                'healthy' => '<fg=green>✓</>',
                'warning' => '<fg=yellow>⚠</>',
                'critical' => '<fg=red>✗</>',
                default => '?'
            };

            $this->line(sprintf(
                '%s %s: %s',
                $status,
                ucfirst($check),
                $result['message']
            ));
        }

        $this->newLine();
        $allHealthy = collect($checks)->every(fn($c) => $c['status'] === 'healthy');
        
        if ($allHealthy) {
            $this->info('All systems operational ✓');
            return 0;
        } else {
            $this->warn('Some systems need attention');
            return 1;
        }
    }
}
