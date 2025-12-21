<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';

    protected $description = 'Create a database backup';

    public function handle()
    {
        if (!Setting::get('backup_enabled', true)) {
            $this->info('Backups are disabled');
            return 0;
        }

        $filename = 'backup-' . date('Y-m-d-His') . '.sql';
        $path = storage_path("app/backups/{$filename}");

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $cmd = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s 2>&1',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.database'),
            $path
        );
        
        exec($cmd, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error('Backup failed');
            return 1;
        }

        // Clean old backups (keep last 30)
        $backups = glob(storage_path('app/backups/*.sql'));
        if (count($backups) > 30) {
            usort($backups, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            $toDelete = array_slice($backups, 0, count($backups) - 30);
            foreach ($toDelete as $file) {
                unlink($file);
            }
        }

        $this->info("Backup created: {$filename}");
        return 0;
    }
}
