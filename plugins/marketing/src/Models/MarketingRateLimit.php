<?php

namespace Plugins\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Plugins\Marketing\Plugin;

class MarketingRateLimit extends Model
{
    protected $table = 'marketing_rate_limits';

    protected $fillable = [
        'type',
        'count',
        'window_start',
    ];

    protected $casts = [
        'window_start' => 'datetime',
    ];

    public static function canSendEmail(): bool
    {
        $limits = Plugin::getRateLimits();
        
        // Check hourly limit
        $hourlyCount = static::getCount('email_hourly', now()->startOfHour());
        if ($hourlyCount >= $limits['emails_per_hour']) {
            return false;
        }
        
        // Check daily limit
        $dailyCount = static::getCount('email_daily', now()->startOfDay());
        if ($dailyCount >= $limits['emails_per_day']) {
            return false;
        }
        
        return true;
    }

    public static function canScrape(): bool
    {
        $limits = Plugin::getRateLimits();
        
        $minuteCount = static::getCount('scrape', now()->startOfMinute());
        return $minuteCount < $limits['scrape_per_minute'];
    }

    public static function incrementEmail(): void
    {
        static::incrementCount('email_hourly', now()->startOfHour());
        static::incrementCount('email_daily', now()->startOfDay());
    }

    public static function incrementScrape(): void
    {
        static::incrementCount('scrape', now()->startOfMinute());
    }

    protected static function getCount(string $type, $windowStart): int
    {
        $record = static::where('type', $type)
            ->where('window_start', $windowStart)
            ->first();
            
        return $record?->count ?? 0;
    }

    protected static function incrementCount(string $type, $windowStart): void
    {
        static::updateOrCreate(
            ['type' => $type, 'window_start' => $windowStart],
            ['count' => \DB::raw('count + 1')]
        );
    }

    public static function getRemainingEmails(): array
    {
        $limits = Plugin::getRateLimits();
        
        $hourlyCount = static::getCount('email_hourly', now()->startOfHour());
        $dailyCount = static::getCount('email_daily', now()->startOfDay());
        
        return [
            'hourly' => max(0, $limits['emails_per_hour'] - $hourlyCount),
            'daily' => max(0, $limits['emails_per_day'] - $dailyCount),
        ];
    }

    public static function cleanup(): void
    {
        // Remove old rate limit records (older than 2 days)
        static::where('window_start', '<', now()->subDays(2))->delete();
    }
}
