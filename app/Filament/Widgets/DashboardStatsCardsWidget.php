<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class DashboardStatsCardsWidget extends BaseWidget
{
    protected static ?int $sort = 0;
    
    protected function getStats(): array
    {
        $stats = [];
        
        // Total Visitors Today
        try {
            $visitorsToday = DB::table('sessions')
                ->whereDate('last_activity', today())
                ->count();
            
            $stats[] = Stat::make('Visitors Today', number_format($visitorsToday))
                ->description('Active sessions')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart([7, 12, 16, 10, 24, 15, $visitorsToday])
                ->color('info');
        } catch (\Exception $e) {
            $stats[] = Stat::make('Visitors Today', '0')
                ->description('No data available')
                ->color('gray');
        }
        
        // Unread Messages
        try {
            $unreadMessages = DB::table('contact_messages')
                ->where(function($q) {
                    $q->where('status', 'unread')->orWhere('status', 'new');
                })
                ->count();
                
            $stats[] = Stat::make('New Messages', number_format($unreadMessages))
                ->description('Awaiting response')
                ->descriptionIcon('heroicon-m-envelope')
                ->color($unreadMessages > 0 ? 'warning' : 'success');
        } catch (\Exception $e) {
            $stats[] = Stat::make('New Messages', '0')
                ->color('gray');
        }
        
        // Chat Messages
        try {
            $unreadChats = DB::table('chat_messages')
                ->where('is_admin', false)
                ->where('is_read', false)
                ->count();
                
            $stats[] = Stat::make('Unread Chats', number_format($unreadChats))
                ->description('Support messages')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color($unreadChats > 0 ? 'danger' : 'success');
        } catch (\Exception $e) {
            $stats[] = Stat::make('Unread Chats', '0')
                ->color('gray');
        }
        
        // Server Status
        $diskUsage = round(disk_free_space('/') / disk_total_space('/') * 100, 1);
        $stats[] = Stat::make('Server Health', $diskUsage . '% Free')
            ->description('Disk space available')
            ->descriptionIcon('heroicon-m-server')
            ->color($diskUsage > 20 ? 'success' : 'danger');
        
        return $stats;
    }
}
