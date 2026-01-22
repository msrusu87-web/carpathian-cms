<?php

namespace Plugins\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingCampaignLog extends Model
{
    protected $table = 'marketing_campaign_logs';

    protected $fillable = [
        'campaign_id',
        'contact_id',
        'status',
        'sent_at',
        'opened_at',
        'clicked_at',
        'tracking_id',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($log) {
            if (empty($log->tracking_id)) {
                $log->tracking_id = bin2hex(random_bytes(16));
            }
        });
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(MarketingCampaign::class, 'campaign_id');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(MarketingContact::class, 'contact_id');
    }

    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
        
        $this->campaign->incrementStat('sent');
        $this->contact->incrementEmailStats('sent');
    }

    public function markAsOpened(): void
    {
        if ($this->status !== 'opened' && $this->status !== 'clicked') {
            $this->update([
                'status' => 'opened',
                'opened_at' => now(),
            ]);
            
            $this->campaign->incrementStat('opened');
            $this->contact->incrementEmailStats('opened');
        }
    }

    public function markAsClicked(): void
    {
        if ($this->status !== 'clicked') {
            if ($this->status !== 'opened') {
                $this->markAsOpened();
            }
            
            $this->update([
                'status' => 'clicked',
                'clicked_at' => now(),
            ]);
            
            $this->campaign->incrementStat('clicked');
            $this->contact->incrementEmailStats('clicked');
        }
    }

    public function markAsFailed(string $error): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $error,
        ]);
    }

    public function markAsBounced(): void
    {
        $this->update(['status' => 'bounced']);
        $this->campaign->incrementStat('bounced');
        $this->contact->update(['status' => 'bounced']);
    }

    public static function findByTrackingId(string $trackingId): ?self
    {
        return static::where('tracking_id', $trackingId)->first();
    }
}
