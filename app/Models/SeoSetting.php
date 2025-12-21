<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    protected $fillable = [
        'meta_title', 'meta_description', 'meta_keywords', 'og_image', 'robots_txt',
        'sitemap_enabled', 'sitemap_frequencies', 'google_analytics', 'google_tag_manager',
        'facebook_pixel', 'custom_head_scripts', 'custom_body_scripts', 'schema_markup'
    ];

    protected $casts = [
        'sitemap_enabled' => 'boolean',
        'sitemap_frequencies' => 'array',
        'schema_markup' => 'array',
    ];

    public static function get()
    {
        return self::first() ?? self::create([]);
    }
}
