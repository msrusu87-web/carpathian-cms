<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'subject',
        'body_html',
        'body_text',
        'type',
        'is_active',
        'variables',
        'attachments',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'variables' => 'array',
        'attachments' => 'array',
    ];

    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->where('is_active', true)->first();
    }

    public function render(array $data = []): array
    {
        $subject = $this->subject;
        $body = $this->body_html;

        foreach ($data as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $subject = str_replace($placeholder, $value, $subject);
            $body = str_replace($placeholder, $value, $body);
        }

        return [
            'subject' => $subject,
            'body' => $body,
        ];
    }
}
