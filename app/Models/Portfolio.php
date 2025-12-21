<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'client',
        'category',
        'short_description',
        'full_description',
        'image',
        'gallery',
        'website_url',
        'technologies',
        'services',
        'completion_date',
        'gradient_from',
        'gradient_to',
        'badge_color',
        'order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'technologies' => 'array',
        'services' => 'array',
        'gallery' => 'array',
        'completion_date' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($portfolio) {
            if (empty($portfolio->slug)) {
                $portfolio->slug = Str::slug($portfolio->title);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
    }

    public function getCategoryLabelAttribute()
    {
        $labels = [
            'web_development' => __('messages.web_development'),
            'ai_platform' => __('messages.ai_platform'),
            'blockchain' => __('messages.blockchain'),
            'web_tools' => __('messages.web_tools'),
            'openai' => __('messages.openai'),
            'ai_powered' => __('messages.ai_powered'),
            'mobile_app' => __('messages.mobile_app'),
            'ecommerce' => __('messages.ecommerce'),
        ];

        return $labels[$this->category] ?? $this->category;
    }

    public function getGradientClassAttribute()
    {
        return "from-{$this->gradient_from} to-{$this->gradient_to}";
    }
}
