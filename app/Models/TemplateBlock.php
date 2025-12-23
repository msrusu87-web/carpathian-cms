<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateBlock extends Model
{
    protected $fillable = [
        'template_id', 'name', 'type', 'content', 'html',
        'css', 'js', 'config', 'order', 'is_active', 'ai_generated'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ai_generated' => 'boolean',
        'config' => 'array',
        'order' => 'integer'
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
