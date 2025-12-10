<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'location',
        'description',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->whereNull('parent_id')->orderBy('order');
    }

    public function allItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }

    public static function getLocations(): array
    {
        return [
            'header' => 'Header Menu',
            'top' => 'Top Menu',
            'top_left' => 'Top Left Menu',
            'top_right' => 'Top Right Menu',
            'footer' => 'Footer Menu',
            'sidebar_left' => 'Sidebar Left',
            'sidebar_right' => 'Sidebar Right',
        ];
    }
}
