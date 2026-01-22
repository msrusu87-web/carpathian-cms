<?php

namespace Plugins\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingScrapeJob extends Model
{
    protected $table = 'marketing_scrape_jobs';

    protected $fillable = [
        'name',
        'urls',
        'search_query',
        'search_location',
        'status',
        'total_urls',
        'processed_urls',
        'contacts_found',
        'settings',
        'error_log',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'urls' => 'array',
        'settings' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function scrapedData(): HasMany
    {
        return $this->hasMany(MarketingScrapedData::class, 'job_id');
    }

    public function start(): void
    {
        $this->update([
            'status' => 'running',
            'started_at' => now(),
        ]);
    }

    public function complete(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function fail(string $error): void
    {
        $this->update([
            'status' => 'failed',
            'error_log' => $error,
            'completed_at' => now(),
        ]);
    }

    public function incrementProcessed(): void
    {
        $this->increment('processed_urls');
    }

    public function incrementContactsFound(int $count = 1): void
    {
        $this->increment('contacts_found', $count);
    }

    public function getProgressAttribute(): float
    {
        if ($this->total_urls === 0) return 0;
        return round(($this->processed_urls / $this->total_urls) * 100, 2);
    }
}
