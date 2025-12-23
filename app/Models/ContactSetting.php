<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    protected $fillable = [
        'company_name', 'email', 'phone', 'address',
        'city', 'state', 'zip_code', 'country',
        'map_embed', 'facebook_url', 'twitter_url',
        'linkedin_url', 'instagram_url', 'working_hours',
        'receive_emails'
    ];

    protected $casts = [
        'receive_emails' => 'boolean',
    ];

    public static function get()
    {
        return self::first() ?? new self();
    }
}
