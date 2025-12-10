<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceMetric extends Model
{
    protected $fillable = [
        'metric_type',
        'metric_name',
        'value',
        'unit',
        'tags',
        'measured_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'tags' => 'array',
        'measured_at' => 'datetime',
    ];
}
