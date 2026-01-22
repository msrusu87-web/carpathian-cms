<?php

namespace Plugins\Marketing\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingTemplate extends Model
{
    protected $table = 'marketing_templates';

    protected $fillable = [
        'name',
        'subject',
        'body_html',
        'body_text',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public static function getAvailableVariables(): array
    {
        return [
            '{{company_name}}' => 'Company name',
            '{{contact_name}}' => 'Contact person name',
            '{{email}}' => 'Email address',
            '{{phone}}' => 'Phone number',
            '{{website}}' => 'Website URL',
            '{{city}}' => 'City',
            '{{country}}' => 'Country',
            '{{unsubscribe_url}}' => 'Unsubscribe link (required)',
            '{{current_date}}' => 'Current date',
            '{{current_year}}' => 'Current year',
        ];
    }

    public function parseContent(MarketingContact $contact, string $unsubscribeUrl = ''): array
    {
        $replacements = [
            '{{company_name}}' => $contact->company_name ?? '',
            '{{contact_name}}' => $contact->contact_name ?? '',
            '{{email}}' => $contact->email ?? '',
            '{{phone}}' => $contact->phone ?? '',
            '{{website}}' => $contact->website ?? '',
            '{{city}}' => $contact->city ?? '',
            '{{country}}' => $contact->country ?? '',
            '{{unsubscribe_url}}' => $unsubscribeUrl,
            '{{current_date}}' => now()->format('d.m.Y'),
            '{{current_year}}' => now()->year,
        ];

        $subject = str_replace(array_keys($replacements), array_values($replacements), $this->subject);
        $bodyHtml = str_replace(array_keys($replacements), array_values($replacements), $this->body_html);
        $bodyText = str_replace(array_keys($replacements), array_values($replacements), $this->body_text ?? strip_tags($this->body_html));

        return [
            'subject' => $subject,
            'body_html' => $bodyHtml,
            'body_text' => $bodyText,
        ];
    }
}
