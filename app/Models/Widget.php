<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
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
        'order' => 'integer'
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
        return $query->where('status', 'active')->orderBy('order');
    }
}
