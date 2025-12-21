<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use SoftDeletes, HasTranslations;

    public $translatable = ['title', 'content', 'excerpt'];

    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'featured_image',
        'status', 'user_id', 'category_id', 'template_id',
        'meta_title', 'meta_description', 'meta_keywords',
        'views', 'featured', 'allow_comments', 'custom_fields',
        'published_at'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'allow_comments' => 'boolean',
        'custom_fields' => 'array',
        'published_at' => 'datetime',
        'views' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
}
