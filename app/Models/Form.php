<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'fields',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'fields' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }
}
