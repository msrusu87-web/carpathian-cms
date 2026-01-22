<?php

namespace Plugins\Marketing\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Plugins\Marketing\Models\MarketingCampaign;
use Plugins\Marketing\Models\MarketingCampaignLog;
use Plugins\Marketing\Models\MarketingContact;
use Plugins\Marketing\Models\MarketingRateLimit;
use Plugins\Marketing\Models\MarketingUnsubscribe;
use Plugins\Marketing\Plugin;

class EmailCampaignService
{
    protected array $antiSpamConfig;

    public function __construct()
    {
        $this->antiSpamConfig = Plugin::getAntiSpamConfig();
    }

    public function sendCampaign(MarketingCampaign $campaign): array
    {
        try {
            // Validate campaign
            if (!$campaign->canSend()) {
                return ['success' => false, 'message' => 'Campaign cannot be sent in current status'];
            }

            if (!$campaign->list) {
                return ['success' => false, 'message' => 'No contact list selected'];
            }

            // Get active contacts from the list
            $contacts = $campaign->list->activeContacts()->get();
            
            if ($contacts->isEmpty()) {
                return ['success' => false, 'message' => 'No active contacts in the selected list'];
            }

            // Start campaign
            $campaign->start();

            $sent = 0;
            $failed = 0;
            $skipped = 0;

            foreach ($contacts as $contact) {
                // Check rate limit
                if (!MarketingRateLimit::canSendEmail()) {
                    Log::info("Rate limit reached, stopping campaign {$campaign->id}");
                    $campaign->pause();
                    return [
                        'success' => true, 
                        'message' => "Paused: Rate limit reached. Sent: {$sent}, Failed: {$failed}, Remaining: " . ($contacts->count() - $sent - $failed - $skipped)
                    ];
                }

                // Check if contact can receive email
                if (!$contact->canReceiveEmail()) {
                    $skipped++;
                    continue;
                }

                // Check daily limit per contact
                if ($this->hasReachedContactLimit($contact)) {
                    $skipped++;
                    continue;
                }

                // Send email
                $result = $this->sendEmailToContact($campaign, $contact);
                
                if ($result['success']) {
                    $sent++;
                    MarketingRateLimit::incrementEmail();
                } else {
                    $failed++;
                }

                // Delay between emails
                $delay = $this->antiSpamConfig['min_delay_between_emails_seconds'] ?? 30;
                sleep($delay);
            }

            // Complete campaign
            $campaign->complete();

            return [
                'success' => true,
                'message' => "Campaign completed. Sent: {$sent}, Failed: {$failed}, Skipped: {$skipped}"
            ];

        } catch (\Exception $e) {
            Log::error("Campaign {$campaign->id} failed: " . $e->getMessage());
            $campaign->pause();
            return ['success' => false, 'message' => 'Campaign failed: ' . $e->getMessage()];
        }
    }

    public function sendEmailToContact(MarketingCampaign $campaign, MarketingContact $contact): array
    {
        try {
            // Create campaign log
            $log = MarketingCampaignLog::create([
                'campaign_id' => $campaign->id,
                'contact_id' => $contact->id,
                'status' => 'pending',
            ]);

            // Generate unsubscribe URL
            $unsubscribeUrl = $this->generateUnsubscribeUrl($contact, $campaign);

            // Parse email content with variables
            $content = $this->parseEmailContent($campaign, $contact, $unsubscribeUrl);

            // Check for required unsubscribe link
            if ($this->antiSpamConfig['require_unsubscribe_link'] ?? true) {
                if (strpos($content['body_html'], $unsubscribeUrl) === false) {
                    // Append unsubscribe link if not present
                    $content['body_html'] .= $this->getUnsubscribeFooter($unsubscribeUrl);
                    $content['body_text'] .= "\n\nUnsubscribe: " . $unsubscribeUrl;
                }
            }

            // Add tracking pixel
            $trackingPixel = $this->getTrackingPixel($log->tracking_id);
            $content['body_html'] .= $trackingPixel;

            // Send the email
            Mail::html($content['body_html'], function ($message) use ($campaign, $contact, $content) {
                $message->to($contact->email, $contact->contact_name ?? $contact->company_name)
                    ->subject($content['subject'])
                    ->from(
                        $campaign->from_email ?? config('mail.from.address'),
                        $campaign->from_name ?? config('mail.from.name')
                    );

                if ($campaign->reply_to) {
                    $message->replyTo($campaign->reply_to);
                }
            });

            // Update log
            $log->markAsSent();

            return ['success' => true, 'log_id' => $log->id];

        } catch (\Exception $e) {
            Log::error("Failed to send email to {$contact->email}: " . $e->getMessage());
            
            if (isset($log)) {
                $log->markAsFailed($e->getMessage());
            }

            // Check for bounce indicators
            if (strpos($e->getMessage(), 'Mailbox not found') !== false ||
                strpos($e->getMessage(), 'User unknown') !== false ||
                strpos($e->getMessage(), '550') !== false) {
                $contact->update(['status' => 'bounced']);
            }

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function parseEmailContent(MarketingCampaign $campaign, MarketingContact $contact, string $unsubscribeUrl): array
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

        return [
            'subject' => str_replace(array_keys($replacements), array_values($replacements), $campaign->subject),
            'body_html' => str_replace(array_keys($replacements), array_values($replacements), $campaign->body_html),
            'body_text' => str_replace(array_keys($replacements), array_values($replacements), $campaign->body_text ?? strip_tags($campaign->body_html)),
        ];
    }

    protected function generateUnsubscribeUrl(MarketingContact $contact, ?MarketingCampaign $campaign = null): string
    {
        // Check if unsubscribe token exists
        $unsubscribe = MarketingUnsubscribe::where('contact_id', $contact->id)->first();
        
        if (!$unsubscribe) {
            $unsubscribe = MarketingUnsubscribe::create([
                'contact_id' => $contact->id,
                'campaign_id' => $campaign?->id,
                'unsubscribed_at' => null, // Not unsubscribed yet, just creating token
            ]);
            // Clear the unsubscribed_at since it's just a token creation
            $unsubscribe->update(['unsubscribed_at' => null]);
        }

        return url('/api/marketing/unsubscribe/' . $unsubscribe->token);
    }

    protected function getUnsubscribeFooter(string $unsubscribeUrl): string
    {
        return '<div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #666; text-align: center;">
            <p>You received this email because you are subscribed to our marketing communications.</p>
            <p><a href="' . $unsubscribeUrl . '" style="color: #666;">Click here to unsubscribe</a></p>
        </div>';
    }

    protected function getTrackingPixel(string $trackingId): string
    {
        $trackUrl = url('/api/marketing/track/' . $trackingId);
        return '<img src="' . $trackUrl . '" width="1" height="1" style="display:none;" alt="" />';
    }

    protected function hasReachedContactLimit(MarketingContact $contact): bool
    {
        $maxPerDay = $this->antiSpamConfig['max_emails_per_contact_per_day'] ?? 1;
        
        $sentToday = MarketingCampaignLog::where('contact_id', $contact->id)
            ->where('status', 'sent')
            ->where('sent_at', '>=', now()->startOfDay())
            ->count();

        return $sentToday >= $maxPerDay;
    }

    /**
     * Send a test email to verify configuration
     */
    public function sendTestEmail(string $toEmail, MarketingCampaign $campaign): array
    {
        try {
            // Create a dummy contact for testing
            $contact = new MarketingContact([
                'email' => $toEmail,
                'company_name' => 'Test Company',
                'contact_name' => 'Test Contact',
            ]);

            $unsubscribeUrl = url('/api/marketing/unsubscribe/test');
            $content = $this->parseEmailContent($campaign, $contact, $unsubscribeUrl);

            // Add unsubscribe footer
            $content['body_html'] .= $this->getUnsubscribeFooter($unsubscribeUrl);

            Mail::html($content['body_html'], function ($message) use ($campaign, $toEmail, $content) {
                $message->to($toEmail)
                    ->subject('[TEST] ' . $content['subject'])
                    ->from(
                        $campaign->from_email ?? config('mail.from.address'),
                        $campaign->from_name ?? config('mail.from.name')
                    );
            });

            return ['success' => true, 'message' => 'Test email sent to ' . $toEmail];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed: ' . $e->getMessage()];
        }
    }

    /**
     * Process unsubscribe request
     */
    public function handleUnsubscribe(string $token): array
    {
        $unsubscribe = MarketingUnsubscribe::findByToken($token);
        
        if (!$unsubscribe) {
            return ['success' => false, 'message' => 'Invalid unsubscribe token'];
        }

        if ($unsubscribe->contact->status === 'unsubscribed') {
            return ['success' => true, 'message' => 'Already unsubscribed'];
        }

        $unsubscribe->contact->markAsUnsubscribed('Clicked unsubscribe link');
        
        if ($unsubscribe->campaign) {
            $unsubscribe->campaign->incrementStat('unsubscribed');
        }

        return ['success' => true, 'message' => 'Successfully unsubscribed'];
    }

    /**
     * Track email open
     */
    public function trackOpen(string $trackingId): bool
    {
        $log = MarketingCampaignLog::findByTrackingId($trackingId);
        
        if ($log) {
            $log->markAsOpened();
            return true;
        }

        return false;
    }

    /**
     * Track link click
     */
    public function trackClick(string $trackingId): bool
    {
        $log = MarketingCampaignLog::findByTrackingId($trackingId);
        
        if ($log) {
            $log->markAsClicked();
            return true;
        }

        return false;
    }
}
