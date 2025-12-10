<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Redirect extends Model
{
    protected $fillable = [
        'from_url',
        'to_url',
        'status_code',
        'is_active',
        'hits',
        'last_hit_at',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hits' => 'integer',
        'status_code' => 'integer',
        'last_hit_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('active_redirects');
        });

        static::deleted(function () {
            Cache::forget('active_redirects');
        });
    }

    public function incrementHits(): void
    {
        $this->increment('hits');
        $this->update(['last_hit_at' => now()]);
    }

    public static function findByUrl(string $url): ?self
    {
        $redirects = Cache::remember('active_redirects', 3600, function () {
            return self::where('is_active', true)->get();
        });

        return $redirects->firstWhere('from_url', $url);
    }
}
