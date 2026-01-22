<?php

namespace Plugins\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingScrapedData extends Model
{
    protected $table = 'marketing_scraped_data';

    protected $fillable = [
        'job_id',
        'url',
        'domain',
        'extracted_data',
        'status',
        'error_message',
    ];

    protected $casts = [
        'extracted_data' => 'array',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(MarketingScrapeJob::class, 'job_id');
    }

    public function markAsProcessed(): void
    {
        $this->update(['status' => 'processed']);
    }

    public function markAsFailed(string $error): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $error,
        ]);
    }

    public function markAsDuplicate(): void
    {
        $this->update(['status' => 'duplicate']);
    }

    public function toContact(): ?MarketingContact
    {
        $data = $this->extracted_data;
        
        if (empty($data)) return null;
        
        // Check for duplicate
        if (!empty($data['email'])) {
            $existing = MarketingContact::findByEmail($data['email']);
            if ($existing) {
                $this->markAsDuplicate();
                return null;
            }
        }

        $contact = MarketingContact::create([
            'company_name' => $data['company_name'] ?? null,
            'contact_name' => $data['contact_name'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'website' => $data['website'] ?? $this->url,
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? null,
            'country' => $data['country'] ?? 'Romania',
            'source' => 'scraper',
            'source_url' => $this->url,
        ]);

        $this->markAsProcessed();
        $this->job->incrementContactsFound();

        return $contact;
    }
}
