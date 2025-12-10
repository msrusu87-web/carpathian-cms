<?php

namespace Plugins\Freelancer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FreelancerGig extends Model
{
    protected $fillable = [
        'freelancer_profile_id', 'title', 'description', 'category', 'tags',
        'price', 'delivery_days', 'images', 'revisions', 'rating', 'total_orders', 'is_active'
    ];

    protected $casts = [
        'tags' => 'array',
        'images' => 'array',
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(FreelancerProfile::class, 'freelancer_profile_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(FreelancerOrder::class, 'gig_id');
    }
}
