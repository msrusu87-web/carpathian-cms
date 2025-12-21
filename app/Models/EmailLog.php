<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'to_email',
        'to_name',
        'subject',
        'template_slug',
        'status',
        'error_message',
    ];

    public static function log(string $toEmail, string $subject, string $status = 'sent', ?string $templateSlug = null, ?string $error = null): self
    {
        return static::create([
            'to_email' => $toEmail,
            'subject' => $subject,
            'template_slug' => $templateSlug,
            'status' => $status,
            'error_message' => $error,
        ]);
    }
}
