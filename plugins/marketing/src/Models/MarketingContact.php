<?php

namespace Plugins\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingContact extends Model
{
    use SoftDeletes;

    protected $table = 'marketing_contacts';

    protected $fillable = [
        'company_name',
        'contact_name',
        'email',
        'phone',
        'website',
        'address',
        'city',
        'country',
        'source',
        'source_url',
        'tags',
        'custom_fields',
        'status',
        'email_verified',
        'has_consent',
        'consent_date',
        'last_contacted',
        'emails_sent',
        'emails_opened',
        'emails_clicked',
        'notes',
        'google_place_id',
        'google_rating',
    ];

    protected $casts = [
        'tags' => 'array',
        'custom_fields' => 'array',
        'email_verified' => 'boolean',
        'has_consent' => 'boolean',
        'consent_date' => 'datetime',
        'last_contacted' => 'datetime',
    ];

    public function lists(): BelongsToMany
    {
        return $this->belongsToMany(MarketingList::class, 'marketing_contact_list', 'contact_id', 'list_id');
    }

    public function campaignLogs(): HasMany
    {
        return $this->hasMany(MarketingCampaignLog::class, 'contact_id');
    }

    public function unsubscribes(): HasMany
    {
        return $this->hasMany(MarketingUnsubscribe::class, 'contact_id');
    }

    public function isUnsubscribed(): bool
    {
        return $this->status === 'unsubscribed';
    }

    public function canReceiveEmail(): bool
    {
        return $this->status === 'active' 
            && !empty($this->email) 
            && filter_var($this->email, FILTER_VALIDATE_EMAIL);
    }

    public function markAsUnsubscribed(?string $reason = null): void
    {
        $this->update(['status' => 'unsubscribed']);
        
        MarketingUnsubscribe::create([
            'contact_id' => $this->id,
            'token' => bin2hex(random_bytes(32)),
            'reason' => $reason,
            'unsubscribed_at' => now(),
        ]);
    }

    public function incrementEmailStats(string $type): void
    {
        match($type) {
            'sent' => $this->increment('emails_sent'),
            'opened' => $this->increment('emails_opened'),
            'clicked' => $this->increment('emails_clicked'),
            default => null,
        };
        
        if ($type === 'sent') {
            $this->update(['last_contacted' => now()]);
        }
    }

    public static function findByEmail(string $email): ?self
    {
        return static::where('email', strtolower(trim($email)))->first();
    }

    public function getDomainAttribute(): ?string
    {
        if (!$this->email) return null;
        $parts = explode('@', $this->email);
        return $parts[1] ?? null;
    }
}
