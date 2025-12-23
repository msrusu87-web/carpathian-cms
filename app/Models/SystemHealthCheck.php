<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemHealthCheck extends Model
{
    protected $fillable = [
        'check_name',
        'status',
        'message',
        'metrics',
        'checked_at',
    ];

    protected $casts = [
        'metrics' => 'array',
        'checked_at' => 'datetime',
    ];
}
