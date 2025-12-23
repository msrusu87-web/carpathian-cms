<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class VisitorAnalyticsWidget extends Widget
{
    protected static string $view = 'filament.widgets.visitor-analytics';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 3;

    public function getVisitorData(): array
    {
        $visitors = [];
        
        try {
            // Get recent sessions with user agent parsing
            $sessions = DB::table('sessions')
                ->orderBy('last_activity', 'desc')
                ->limit(10)
                ->get();
            
            foreach ($sessions as $session) {
                $userAgent = $session->user_agent ?? 'Unknown';
                
                // Parse browser
                $browser = 'Unknown';
                if (stripos($userAgent, 'Chrome') !== false) $browser = 'Chrome';
                elseif (stripos($userAgent, 'Firefox') !== false) $browser = 'Firefox';
                elseif (stripos($userAgent, 'Safari') !== false && stripos($userAgent, 'Chrome') === false) $browser = 'Safari';
                elseif (stripos($userAgent, 'Edge') !== false) $browser = 'Edge';
                elseif (stripos($userAgent, 'Opera') !== false) $browser = 'Opera';
                
                // Parse device
                $device = 'Desktop';
                if (stripos($userAgent, 'Mobile') !== false || stripos($userAgent, 'Android') !== false) $device = 'Mobile';
                elseif (stripos($userAgent, 'Tablet') !== false || stripos($userAgent, 'iPad') !== false) $device = 'Tablet';
                
                // Get country from IP (basic)
                $country = 'Unknown';
                $flag = '🌍';
                
                $visitors[] = [
                    'ip' => $session->ip_address ?? 'N/A',
                    'country' => $country,
                    'flag' => $flag,
                    'browser' => $browser,
                    'device' => $device,
                    'time' => date('M d, H:i', $session->last_activity),
                ];
            }
        } catch (\Exception $e) {
            // Silent fail
        }
        
        return $visitors;
    }
    
    public function getBrowserStats(): array
    {
        $stats = ['Chrome' => 0, 'Firefox' => 0, 'Safari' => 0, 'Edge' => 0, 'Other' => 0];
        
        try {
            $sessions = DB::table('sessions')
                ->whereDate('last_activity', '>=', now()->subDays(7))
                ->get();
            
            foreach ($sessions as $session) {
                $userAgent = $session->user_agent ?? '';
                
                if (stripos($userAgent, 'Chrome') !== false) $stats['Chrome']++;
                elseif (stripos($userAgent, 'Firefox') !== false) $stats['Firefox']++;
                elseif (stripos($userAgent, 'Safari') !== false) $stats['Safari']++;
                elseif (stripos($userAgent, 'Edge') !== false) $stats['Edge']++;
                else $stats['Other']++;
            }
        } catch (\Exception $e) {
            // Silent fail
        }
        
        return $stats;
    }
    
    public function getDeviceStats(): array
    {
        $stats = ['Desktop' => 0, 'Mobile' => 0, 'Tablet' => 0];
        
        try {
            $sessions = DB::table('sessions')
                ->whereDate('last_activity', '>=', now()->subDays(7))
                ->get();
            
            foreach ($sessions as $session) {
                $userAgent = $session->user_agent ?? '';
                
                if (stripos($userAgent, 'Mobile') !== false || stripos($userAgent, 'Android') !== false) 
                    $stats['Mobile']++;
                elseif (stripos($userAgent, 'Tablet') !== false || stripos($userAgent, 'iPad') !== false) 
                    $stats['Tablet']++;
                else 
                    $stats['Desktop']++;
            }
        } catch (\Exception $e) {
            // Silent fail
        }
        
        return $stats;
    }
}
