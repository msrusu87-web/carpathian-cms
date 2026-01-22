<?php

namespace Plugins\Marketing\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Plugins\Marketing\Models\MarketingRateLimit;
use Plugins\Marketing\Models\MarketingContact;
use Plugins\Marketing\Models\MarketingCampaign;
use Plugins\Marketing\Plugin;

class RateLimitWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $remaining = MarketingRateLimit::getRemainingEmails();
        $limits = Plugin::getRateLimits();

        return [
            Stat::make('Emails Remaining (Hourly)', $remaining['hourly'] . ' / ' . $limits['emails_per_hour'])
                ->description('Resets every hour')
                ->color($remaining['hourly'] > 10 ? 'success' : ($remaining['hourly'] > 0 ? 'warning' : 'danger'))
                ->icon('heroicon-o-clock'),
            
            Stat::make('Emails Remaining (Daily)', $remaining['daily'] . ' / ' . $limits['emails_per_day'])
                ->description('Resets at midnight')
                ->color($remaining['daily'] > 50 ? 'success' : ($remaining['daily'] > 0 ? 'warning' : 'danger'))
                ->icon('heroicon-o-calendar'),

            Stat::make('Total Contacts', MarketingContact::where('status', 'active')->count())
                ->description('Active contacts')
                ->color('primary')
                ->icon('heroicon-o-users'),

            Stat::make('Campaigns Sent', MarketingCampaign::where('status', 'sent')->count())
                ->description('Completed campaigns')
                ->color('success')
                ->icon('heroicon-o-paper-airplane'),
        ];
    }
}
