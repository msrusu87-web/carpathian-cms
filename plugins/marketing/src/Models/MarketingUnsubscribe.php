<?php

namespace Plugins\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingUnsubscribe extends Model
{
    protected $table = 'marketing_unsubscribes';

    protected $fillable = [
        'contact_id',
        'campaign_id',
        'token',
        'reason',
        'unsubscribed_at',
    ];

    protected $casts = [
        'unsubscribed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($unsubscribe) {
            if (empty($unsubscribe->token)) {
                $unsubscribe->token = bin2hex(random_bytes(32));
            }
            if (empty($unsubscribe->unsubscribed_at)) {
                $unsubscribe->unsubscribed_at = now();
            }
        });
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(MarketingContact::class, 'contact_id');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(MarketingCampaign::class, 'campaign_id');
    }

    public static function findByToken(string $token): ?self
    {
        return static::where('token', $token)->first();
    }
}
