<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use App\Models\SystemHealthCheck;
use App\Models\PerformanceMetric;

class SystemMonitoringService
{
    public function runHealthChecks(): array
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'disk' => $this->checkDiskSpace(),
            'queue' => $this->checkQueue(),
        ];

        foreach ($checks as $name => $result) {
            SystemHealthCheck::create([
                'check_name' => $name,
                'status' => $result['status'],
                'message' => $result['message'],
                'metrics' => json_encode($result),
                'checked_at' => now(),
            ]);
        }

        return $checks;
    }

    protected function checkDatabase(): array
    {
        try {
            DB::select('SELECT 1');
            return [
                'status' => 'healthy',
                'message' => 'Database connection successful',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'critical',
                'message' => 'Database connection failed: ' . $e->getMessage(),
            ];
        }
    }

    protected function checkCache(): array
    {
        try {
            $testKey = 'health_check_' . time();
            Cache::put($testKey, 'test', 1);
            $value = Cache::get($testKey);
            Cache::forget($testKey);

            if ($value === 'test') {
                return [
                    'status' => 'healthy',
                    'message' => 'Cache is working',
                ];
            }

            return [
                'status' => 'warning',
                'message' => 'Cache read/write issue',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'critical',
                'message' => 'Cache failed: ' . $e->getMessage(),
            ];
        }
    }

    protected function checkDiskSpace(): array
    {
        $freeSpace = disk_free_space('/');
        $totalSpace = disk_total_space('/');
        $usedPercentage = (($totalSpace - $freeSpace) / $totalSpace) * 100;

        if ($usedPercentage < 80) {
            return [
                'status' => 'healthy',
                'message' => sprintf('Disk usage: %.1f%%', $usedPercentage),
            ];
        } elseif ($usedPercentage < 90) {
            return [
                'status' => 'warning',
                'message' => sprintf('Disk usage high: %.1f%%', $usedPercentage),
            ];
        } else {
            return [
                'status' => 'critical',
                'message' => sprintf('Disk space critical: %.1f%%', $usedPercentage),
            ];
        }
    }

    protected function checkQueue(): array
    {
        try {
            $size = Queue::size();
            
            if ($size < 100) {
                return [
                    'status' => 'healthy',
                    'message' => sprintf('Queue size: %d jobs', $size),
                ];
            } elseif ($size < 1000) {
                return [
                    'status' => 'warning',
                    'message' => sprintf('Queue size high: %d jobs', $size),
                ];
            } else {
                return [
                    'status' => 'critical',
                    'message' => sprintf('Queue backlog: %d jobs', $size),
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'warning',
                'message' => 'Queue check failed: ' . $e->getMessage(),
            ];
        }
    }

    public function recordMetric(string $type, string $name, float $value, string $unit = null, array $tags = []): void
    {
        PerformanceMetric::create([
            'metric_type' => $type,
            'metric_name' => $name,
            'value' => $value,
            'unit' => $unit,
            'tags' => json_encode($tags),
            'measured_at' => now(),
        ]);
    }
}
