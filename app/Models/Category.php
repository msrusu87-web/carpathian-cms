<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use SoftDeletes, HasTranslations;

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'name', 'slug', 'description', 'parent_id', 'icon',
        'color', 'order', 'is_active', 'meta'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'meta' => 'array'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
