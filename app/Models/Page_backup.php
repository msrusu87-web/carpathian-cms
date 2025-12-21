<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'featured_image',
        'status', 'user_id', 'template_id', 'meta_title',
        'meta_description', 'meta_keywords', 'order',
        'is_homepage', 'show_in_menu', 'custom_fields', 'published_at', 'menu_locations'
    ];

    protected $casts = [
        'is_homepage' => 'boolean',
        'show_in_menu' => 'boolean',
        'custom_fields' => 'array',
        'published_at' => 'datetime',
        'menu_locations' => 'array',
        'order' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at', 'menu_locations')
                     ->where('published_at', 'menu_locations', '<=', now());
    }

    public function scopeHomepage($query)
    {
        return $query->where('is_homepage', true);
    }

    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true)->orderBy('order');
    }
}
