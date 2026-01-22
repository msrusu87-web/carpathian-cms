<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_driver',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'admin_notification_email',
        'notification_preferences',
    ];

    protected $casts = [
        'notification_preferences' => 'array',
    ];

    protected $hidden = [
        'mail_password',
    ];

    public static function getSettings(): ?self
    {
        return static::first();
    }

    public static function getSetting(string $key, $default = null)
    {
        $settings = static::getSettings();
        return $settings ? ($settings->{$key} ?? $default) : $default;
    }

    public function shouldNotify(string $type): bool
    {
        $prefs = $this->notification_preferences ?? [];
        return $prefs[$type] ?? true;
    }
}
