<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;

class BackupController extends Controller
{
    /**
     * Run a new backup
     */
    public function run(Request $request): JsonResponse
    {
        $this->authorize('manage-backups');
        
        try {
            // Run backup command
            Artisan::call('backup:run', [
                '--only-db' => $request->boolean('only_db', false),
                '--disable-notifications' => true,
            ]);
            
            $output = Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => 'Backup completed successfully',
                'output' => $output,
                'timestamp' => now()->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * List available backups
     */
    public function index(): JsonResponse
    {
        $this->authorize('manage-backups');
        
        try {
            $backupDestination = config('backup.backup.destination.disks')[0] ?? 'local';
            $backupPath = config('backup.backup.name', 'laravel-backup');
            
            $disk = Storage::disk($backupDestination);
            $files = $disk->files($backupPath);
            
            $backups = collect($files)
                ->filter(fn($file) => str_ends_with($file, '.zip'))
                ->map(fn($file) => [
                    'filename' => basename($file),
                    'path' => $file,
                    'size' => $disk->size($file),
                    'size_human' => $this->formatBytes($disk->size($file)),
                    'created_at' => date('Y-m-d H:i:s', $disk->lastModified($file)),
                ])
                ->sortByDesc('created_at')
                ->values();
            
            return response()->json([
                'success' => true,
                'backups' => $backups,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to list backups: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restore from a backup
     */
    public function restore(Request $request): JsonResponse
    {
        $this->authorize('manage-backups');
        
        $request->validate([
            'filename' => 'required|string',
        ]);
        
        try {
            $backupDestination = config('backup.backup.destination.disks')[0] ?? 'local';
            $backupPath = config('backup.backup.name', 'laravel-backup');
            $disk = Storage::disk($backupDestination);
            
            $filePath = $backupPath . '/' . $request->filename;
            
            if (!$disk->exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup file not found',
                ], 404);
            }
            
            // Extract and restore
            $tempPath = storage_path('app/temp-restore');
            if (!is_dir($tempPath)) {
                mkdir($tempPath, 0755, true);
            }
            
            $zipPath = $disk->path($filePath);
            $zip = new \ZipArchive();
            
            if ($zip->open($zipPath) === true) {
                $zip->extractTo($tempPath);
                $zip->close();
                
                // Find and restore the SQL dump
                $sqlFiles = glob($tempPath . '/db-dumps/*.sql');
                if (!empty($sqlFiles)) {
                    $sqlFile = $sqlFiles[0];
                    
                    // Get database config
                    $dbHost = config('database.connections.mysql.host');
                    $dbName = config('database.connections.mysql.database');
                    $dbUser = config('database.connections.mysql.username');
                    $dbPass = config('database.connections.mysql.password');
                    
                    // Restore using mysql command
                    $command = sprintf(
                        'mysql -h%s -u%s -p%s %s < %s 2>&1',
                        escapeshellarg($dbHost),
                        escapeshellarg($dbUser),
                        escapeshellarg($dbPass),
                        escapeshellarg($dbName),
                        escapeshellarg($sqlFile)
                    );
                    
                    exec($command, $output, $returnCode);
                    
                    // Cleanup temp directory
                    $this->deleteDirectory($tempPath);
                    
                    if ($returnCode !== 0) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Database restore failed',
                            'output' => implode("\n", $output),
                        ], 500);
                    }
                    
                    // Clear caches after restore
                    Artisan::call('cache:clear');
                    Artisan::call('config:clear');
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Backup restored successfully',
                        'restored_from' => $request->filename,
                        'timestamp' => now()->toIso8601String(),
                    ]);
                }
                
                $this->deleteDirectory($tempPath);
                return response()->json([
                    'success' => false,
                    'message' => 'No SQL dump found in backup',
                ], 400);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to open backup archive',
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Restore failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restore the latest backup
     */
    public function restoreLatest(): JsonResponse
    {
        $this->authorize('manage-backups');
        
        try {
            $backupDestination = config('backup.backup.destination.disks')[0] ?? 'local';
            $backupPath = config('backup.backup.name', 'laravel-backup');
            $disk = Storage::disk($backupDestination);
            
            $files = $disk->files($backupPath);
            $latestBackup = collect($files)
                ->filter(fn($file) => str_ends_with($file, '.zip'))
                ->sortByDesc(fn($file) => $disk->lastModified($file))
                ->first();
            
            if (!$latestBackup) {
                return response()->json([
                    'success' => false,
                    'message' => 'No backup found to restore',
                ], 404);
            }
            
            // Delegate to restore method
            $request = new Request(['filename' => basename($latestBackup)]);
            return $this->restore($request);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore latest backup: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a backup
     */
    public function destroy(Request $request): JsonResponse
    {
        $this->authorize('manage-backups');
        
        $request->validate([
            'filename' => 'required|string',
        ]);
        
        try {
            $backupDestination = config('backup.backup.destination.disks')[0] ?? 'local';
            $backupPath = config('backup.backup.name', 'laravel-backup');
            $disk = Storage::disk($backupDestination);
            
            $filePath = $backupPath . '/' . $request->filename;
            
            if (!$disk->exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup file not found',
                ], 404);
            }
            
            $disk->delete($filePath);
            
            return response()->json([
                'success' => true,
                'message' => 'Backup deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete backup: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $factor = floor((strlen((string) $bytes) - 1) / 3);
        return sprintf("%.2f %s", $bytes / pow(1024, $factor), $units[$factor] ?? 'TB');
    }

    /**
     * Recursively delete a directory
     */
    private function deleteDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        
        return rmdir($dir);
    }
}
