<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{
    protected $fillable = [
        'site_name', 'site_domain', 'site_logo', 'site_favicon', 'admin_email',
        'site_description', 'timezone', 'date_format', 'time_format',
        'maintenance_mode', 'maintenance_message', 'social_links', 'custom_scripts'
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
        'social_links' => 'array',
        'custom_scripts' => 'array',
    ];

    public static function get()
    {
        return self::first() ?? self::create([]);
    }
}
