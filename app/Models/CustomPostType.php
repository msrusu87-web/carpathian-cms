<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomPostType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'fields_config',
        'is_active',
    ];

    protected $casts = [
        'fields_config' => 'array',
        'is_active' => 'boolean',
    ];
}
