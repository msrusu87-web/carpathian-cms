<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'thumbnail', 'version',
        'author', 'author_url', 'css', 'js', 'config', 'layouts',
        'is_active', 'is_default', 'ai_generated', 'ai_generation_id',
        'color_scheme', 'typography', 'custom_fields'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'ai_generated' => 'boolean',
        'config' => 'array',
        'layouts' => 'array',
        'color_scheme' => 'array',
        'typography' => 'array',
        'custom_fields' => 'array'
    ];

    public function blocks(): HasMany
    {
        return $this->hasMany(TemplateBlock::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function aiGeneration(): BelongsTo
    {
        return $this->belongsTo(AiGeneration::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
