<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiGeneration extends Model
{
    protected $fillable = [
        'type', 'prompt', 'response', 'parameters', 'model',
        'user_id', 'status', 'error_message', 'tokens_used',
        'generation_time'
    ];

    protected $casts = [
        'parameters' => 'array',
        'tokens_used' => 'integer',
        'generation_time' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function templates(): HasMany
    {
        return $this->hasMany(Template::class);
    }

    public function plugins(): HasMany
    {
        return $this->hasMany(Plugin::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
