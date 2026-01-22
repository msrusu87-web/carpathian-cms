<?php

namespace Plugins\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingCampaign extends Model
{
    protected $table = 'marketing_campaigns';

    protected $fillable = [
        'name',
        'subject',
        'body_html',
        'body_text',
        'list_id',
        'template_id',
        'status',
        'scheduled_at',
        'started_at',
        'completed_at',
        'total_recipients',
        'emails_sent',
        'emails_opened',
        'emails_clicked',
        'emails_bounced',
        'emails_unsubscribed',
        'from_name',
        'from_email',
        'reply_to',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function list(): BelongsTo
    {
        return $this->belongsTo(MarketingList::class, 'list_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(MarketingTemplate::class, 'template_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(MarketingCampaignLog::class, 'campaign_id');
    }

    public function getOpenRateAttribute(): float
    {
        if ($this->emails_sent === 0) return 0;
        return round(($this->emails_opened / $this->emails_sent) * 100, 2);
    }

    public function getClickRateAttribute(): float
    {
        if ($this->emails_sent === 0) return 0;
        return round(($this->emails_clicked / $this->emails_sent) * 100, 2);
    }

    public function getBounceRateAttribute(): float
    {
        if ($this->emails_sent === 0) return 0;
        return round(($this->emails_bounced / $this->emails_sent) * 100, 2);
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function canSend(): bool
    {
        return in_array($this->status, ['draft', 'scheduled', 'paused']);
    }

    public function incrementStat(string $type): void
    {
        match($type) {
            'sent' => $this->increment('emails_sent'),
            'opened' => $this->increment('emails_opened'),
            'clicked' => $this->increment('emails_clicked'),
            'bounced' => $this->increment('emails_bounced'),
            'unsubscribed' => $this->increment('emails_unsubscribed'),
            default => null,
        };
    }

    public function start(): void
    {
        $this->update([
            'status' => 'sending',
            'started_at' => now(),
            'total_recipients' => $this->list?->activeContacts()->count() ?? 0,
        ]);
    }

    public function complete(): void
    {
        $this->update([
            'status' => 'sent',
            'completed_at' => now(),
        ]);
    }

    public function pause(): void
    {
        $this->update(['status' => 'paused']);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}
