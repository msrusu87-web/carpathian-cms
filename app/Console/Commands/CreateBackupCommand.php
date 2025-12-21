<?php

namespace App\Console\Commands;

use App\Models\Backup;
use Illuminate\Console\Command;

class CreateBackupCommand extends Command
{
    protected $signature = 'backup:create';
    protected $description = 'Create a database backup';

    public function handle()
    {
        $this->info('Creating database backup...');

        $backupPath = storage_path('app/backups');
        
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = $backupPath . '/' . $filename;

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $command = sprintf(
            'mysqldump -h%s -u%s -p%s %s > %s 2>&1',
            escapeshellarg($host),
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database),
            escapeshellarg($filepath)
        );

        exec($command, $output, $returnCode);

        if ($returnCode === 0 && file_exists($filepath)) {
            $size = filesize($filepath);
            
            Backup::create([
                'filename' => $filename,
                'path' => $filepath,
                'size' => $size,
                'type' => 'database',
                'status' => 'completed',
                'created_by' => 1,
            ]);

            $sizeMB = round($size / 1024 / 1024, 2);
            $this->info("Backup created successfully: {$filename} ({$sizeMB} MB)");
            return 0;
        } else {
            $this->error('Backup failed: ' . implode("\n", $output));
            return 1;
        }
    }
}
