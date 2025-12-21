<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateZone extends Model
{
    protected $fillable = [
        'template_id', 'name', 'identifier', 'order',
        'settings', 'styles', 'is_active'
    ];

    protected $casts = [
        'settings' => 'array',
        'styles' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(TemplateBlock::class, 'zone_id')->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Helper to get blocks by position
    public function getBlocksByPosition(string $position)
    {
        return $this->blocks()->where('position', $position)->get();
    }

    // Settings helpers
    public function getLogo(): ?string
    {
        return $this->settings['logo'] ?? null;
    }

    public function getMenuStyle(): ?string
    {
        return $this->settings['menu_style'] ?? 'horizontal';
    }

    public function getSiteTitle(): ?string
    {
        return $this->settings['site_title'] ?? config('app.name');
    }
}
