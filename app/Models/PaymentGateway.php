<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'credentials',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'credentials' => 'array',  // FIXED: Changed from 'encrypted:array' - credentials are stored as plain JSON
        'settings' => 'array',
        'is_active' => 'boolean',
    ];
}
