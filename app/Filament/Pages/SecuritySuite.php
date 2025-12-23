<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Process;

class SecuritySuite extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    
    protected static string $view = 'filament.pages.security-suite';
    
    public static function getNavigationLabel(): string { return __('messages.security_suite'); }
    
    public function getTitle(): string { return __('messages.security_suite'); }
    
    protected static ?int $navigationSort = 1;
    
    public array $securityStatus = [];
    public string $auditOutput = '';
    public string $pentestOutput = '';
    public string $blockedIpsOutput = '';
    public string $reportOutput = '';
    
    public function mount(): void
    {
        $this->loadSecurityStatus();
    }
    
    public function loadSecurityStatus(): void
    {
        $this->securityStatus = [
            'nginx' => $this->checkNginxStatus(),
            'fail2ban' => $this->checkFail2banStatus(),
            'permissions' => $this->checkFilePermissions(),
            'headers' => $this->checkSecurityHeaders(),
        ];
    }
    
    protected function checkNginxStatus(): array
    {
        try {
            $result = Process::run('sudo /usr/sbin/nginx -t 2>&1');
            $isRunning = $result->successful();
            
            return [
                'status' => $isRunning ? 'active' : 'error',
                'message' => $isRunning ? 'Running' : 'Configuration Error',
            ];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Check Failed'];
        }
    }
    
    protected function checkFail2banStatus(): array
    {
        try {
            $result = Process::run('sudo /usr/sbin/fail2ban-client status');
            $output = $result->output();
            
            preg_match('/Number of jail:\s+(\d+)/', $output, $matches);
            $jailCount = $matches[1] ?? 0;
            
            return [
                'status' => $jailCount > 0 ? 'active' : 'warning',
                'message' => "{$jailCount} Active Jails",
            ];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Check Failed'];
        }
    }
    
    protected function checkFilePermissions(): array
    {
        $basePath = base_path();
        $envFile = $basePath . '/.env';
        
        if (file_exists($envFile)) {
            $perms = substr(sprintf('%o', fileperms($envFile)), -3);
            $isSecure = $perms === '600';
            
            return [
                'status' => $isSecure ? 'active' : 'warning',
                'message' => $isSecure ? 'Secure (.env: 600)' : "Warning (.env: {$perms})",
            ];
        }
        
        return ['status' => 'warning', 'message' => '.env not found'];
    }
    
    protected function checkSecurityHeaders(): array
    {
        try {
            $url = config('app.url');
            $result = Process::run("curl -sI {$url} | grep -i 'x-frame-options\\|strict-transport\\|x-content-type'");
            $headers = $result->output();
            
            $hasXFrame = str_contains($headers, 'X-Frame-Options');
            $hasHSTS = str_contains($headers, 'Strict-Transport-Security');
            $hasContentType = str_contains($headers, 'X-Content-Type-Options');
            
            $count = ($hasXFrame ? 1 : 0) + ($hasHSTS ? 1 : 0) + ($hasContentType ? 1 : 0);
            
            return [
                'status' => $count >= 2 ? 'active' : 'warning',
                'message' => "{$count}/3 Headers Active",
            ];
        } catch (\Exception $e) {
            return ['status' => 'warning', 'message' => 'Check Failed'];
        }
    }
    
    public function runSecurityAudit(): void
    {
        try {
            $scriptPath = '/home/ubuntu/live-carphatian/security-audit.sh';
            
            if (!file_exists($scriptPath)) {
                $this->auditOutput = "Error: Security audit script not found at {$scriptPath}";
                Notification::make()
                    ->danger()
                    ->title('Audit Failed')
                    ->body('Script not found')
                    ->send();
                return;
            }
            
            $result = Process::run("bash {$scriptPath}");
            $this->auditOutput = $result->output() ?: 'No output generated';
            
            Notification::make()
                ->success()
                ->title('Security Audit Complete')
                ->body('Check the Audit tab for results')
                ->send();
                
        } catch (\Exception $e) {
            $this->auditOutput = "Error: " . $e->getMessage();
            Notification::make()
                ->danger()
                ->title('Audit Failed')
                ->body($e->getMessage())
                ->send();
        }
    }
    
    public function runPenetrationTest(): void
    {
        try {
            $scriptPath = '/home/ubuntu/live-carphatian/pentest.sh';
            
            if (!file_exists($scriptPath)) {
                $this->pentestOutput = "Error: Penetration test script not found at {$scriptPath}";
                Notification::make()
                    ->danger()
                    ->title('Pentest Failed')
                    ->body('Script not found')
                    ->send();
                return;
            }
            
            $url = config('app.url');
            $result = Process::run("bash {$scriptPath} {$url}");
            $this->pentestOutput = $result->output() ?: 'No output generated';
            
            Notification::make()
                ->success()
                ->title('Penetration Test Complete')
                ->body('Check the Pentest tab for results')
                ->send();
                
        } catch (\Exception $e) {
            $this->pentestOutput = "Error: " . $e->getMessage();
            Notification::make()
                ->danger()
                ->title('Pentest Failed')
                ->body($e->getMessage())
                ->send();
        }
    }
    
    public function loadBlockedIps(): void
    {
        try {
            $scriptPath = '/home/ubuntu/live-carphatian/list-blocked.sh';
            
            if (!file_exists($scriptPath)) {
                $this->blockedIpsOutput = "Error: List blocked IPs script not found";
                Notification::make()
                    ->warning()
                    ->title('Script Not Found')
                    ->send();
                return;
            }
            
            $result = Process::run("sudo bash {$scriptPath}");
            $this->blockedIpsOutput = $result->output() ?: 'No blocked IPs found';
            
            Notification::make()
                ->info()
                ->title('Blocked IPs Loaded')
                ->send();
                
        } catch (\Exception $e) {
            $this->blockedIpsOutput = "Error: " . $e->getMessage();
        }
    }
    
    public function generateDailyReport(): void
    {
        try {
            $scriptPath = '/home/ubuntu/live-carphatian/daily-security-report.sh';
            
            if (!file_exists($scriptPath)) {
                $this->reportOutput = "Error: Daily report script not found";
                Notification::make()
                    ->warning()
                    ->title('Script Not Found')
                    ->send();
                return;
            }
            
            $result = Process::run("bash {$scriptPath}");
            $this->reportOutput = $result->output() ?: 'No report generated';
            
            Notification::make()
                ->success()
                ->title('Daily Report Generated')
                ->send();
                
        } catch (\Exception $e) {
            $this->reportOutput = "Error: " . $e->getMessage();
        }
    }
    
    public function blockIp(string $ip, string $reason = 'Manual block'): void
    {
        try {
            $scriptPath = '/home/ubuntu/live-carphatian/block-ip.sh';
            $result = Process::run("sudo bash {$scriptPath} {$ip} '{$reason}'");
            
            Notification::make()
                ->success()
                ->title('IP Blocked')
                ->body("Successfully blocked {$ip}")
                ->send();
                
            $this->loadBlockedIps();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Block Failed')
                ->body($e->getMessage())
                ->send();
        }
    }
    
    public function unblockIp(string $ip): void
    {
        try {
            $scriptPath = '/home/ubuntu/live-carphatian/unblock-ip.sh';
            $result = Process::run("sudo bash {$scriptPath} {$ip}");
            
            Notification::make()
                ->success()
                ->title('IP Unblocked')
                ->body("Successfully unblocked {$ip}")
                ->send();
                
            $this->loadBlockedIps();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Unblock Failed')
                ->body($e->getMessage())
                ->send();
        }
    }
    
    public function refreshStatus(): void
    {
        $this->loadSecurityStatus();
        
        Notification::make()
            ->success()
            ->title('Status Refreshed')
            ->send();
    }
}
