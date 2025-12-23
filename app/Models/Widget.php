<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Widget extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'content'];

    protected $fillable = [
        'title',
        'type',
        'content',
        'settings',
        'order',
        'status'
    ];

    protected $casts = [
        'settings' => 'array',
        'order' => 'integer',
        // Historically this has existed as either an enum ('active'/'inactive')
        // or a boolean/integer in some deployments. Keep it as a string to avoid
        // silently coercing values.
        'status' => 'string',
    ];

    public static function getTypes(): array
    {
        return [
            'hero' => 'Hero Section',
            'features' => 'Features Section',
            'products' => 'Products Section',
            'blog' => 'Blog Section',
            'footer' => 'Footer',
            'copyright' => 'Copyright',
            'custom' => 'Custom HTML'
        ];
    }

    public function scopeActive($query)
    {
        return $query
            ->where(function ($q) {
                // Support enum-based status
                $q->where('status', 'active')
                  // Support boolean/integer-based status
                  ->orWhere('status', 1)
                  ->orWhere('status', '1')
                  ->orWhere('status', true);
            })
            ->orderBy('order');
    }
}
