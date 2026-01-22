<?php

namespace Plugins\Marketing\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Plugins\Marketing\Models\MarketingCampaign;
use Plugins\Marketing\Models\MarketingCampaignLog;
use Plugins\Marketing\Models\MarketingContact;

class BrevoWebhookController
{
    /**
     * Handle incoming webhooks from Brevo
     */
    public function handle(Request $request)
    {
        // Verify webhook signature if secret is configured
        if (config('services.brevo.webhook_secret')) {
            $signature = $request->header('X-Brevo-Signature');
            $payload = $request->getContent();
            $expectedSignature = hash_hmac('sha256', $payload, config('services.brevo.webhook_secret'));
            
            if (!hash_equals($expectedSignature, $signature)) {
                return response()->json(['error' => 'Invalid signature'], 401);
            }
        }

        $data = $request->all();
        $event = $data['event'] ?? null;
        $email = $data['email'] ?? null;
        
        if (!$email) {
            return response()->json(['error' => 'Email not provided'], 400);
        }

        Log::info("Brevo webhook received: {$event}", ['data' => $data]);

        switch ($event) {
            case 'delivered':
                $this->handleDelivered($data);
                break;
            case 'opened':
            case 'open':
                $this->handleOpened($data);
                break;
            case 'click':
            case 'clicked':
                $this->handleClicked($data);
                break;
            case 'hard_bounce':
            case 'soft_bounce':
            case 'bounce':
                $this->handleBounce($data);
                break;
            case 'spam':
            case 'complaint':
                $this->handleComplaint($data);
                break;
            case 'unsubscribe':
            case 'unsubscribed':
                $this->handleUnsubscribe($data);
                break;
            case 'delivered':
                $this->handleDelivered($data);
                break;
            default:
                Log::info("Brevo webhook event: {$event}", $data);
        }

        return response()->json(['success' => true, 'message' => 'Webhook processed']);
    }

    protected function handleOpened(array $data): void
    {
        $email = $data['email'] ?? null;
        $messageId = $data['message-id'] ?? null;
        
        if (!$email) return;

        Log::info('Brevo webhook: Email opened', ['email' => $email]);

        // Find the campaign log by email
        $log = MarketingCampaignLog::whereHas('contact', function ($query) use ($email) {
            $query->where('email', $email);
        })->latest()->first();

        if ($log) {
            $log->markAsOpened();
        }
    }

    protected function handleClicked(array $data): void
    {
        $email = $data['email'] ?? null;
        if (!$email) return;

        Log::info('Brevo webhook: Email clicked', ['email' => $email]);

        // Find the most recent campaign log for this email
        $log = MarketingCampaignLog::whereHas('contact', function ($query) use ($email) {
            $query->where('email', $email);
        })->latest()->first();

        if ($log) {
            $log->markAsClicked();
        }
    }

    protected function handleBounce(array $data): void
    {
        $email = $data['email'] ?? null;
        $isHardBounce = ($data['event'] ?? '') === 'hard_bounce';
        
        if (!$email) return;

        // Find contact
        $contact = MarketingContact::where('email', $email)->first();
        if (!$contact) return;

        // Update contact status
        if ($isHardBounce) {
            $contact->update(['status' => 'bounced']);
        }

        // Update campaign log
        $log = MarketingCampaignLog::whereHas('contact', function ($query) use ($email) {
            $query->where('email', $email);
        })->latest()->first();

        if ($log) {
            $log->update([
                'status' => 'bounced',
                'error_message' => $data['reason'] ?? 'Bounce',
            ]);
            
            if ($log->campaign) {
                $log->campaign->incrementStat('bounced');
            }
        }
    }

    protected function handleComplaint(array $data): void
    {
        $email = $data['email'] ?? null;
        if (!$email) return;

        Log::warning('Brevo webhook: Spam complaint', ['email' => $email]);

        $contact = MarketingContact::where('email', $email)->first();
        if ($contact) {
            $contact->markAsUnsubscribed('Marked as spam');
        }
    }

    protected function handleUnsubscribe(array $data): void
    {
        $email = $data['email'] ?? null;
        if (!$email) return;

        $contact = MarketingContact::where('email', $email)->first();
        if ($contact) {
            $contact->markAsUnsubscribed('Unsubscribed via Brevo');
        }
    }

    protected function handleDelivered(array $data): void
    {
        $email = $data['email'] ?? null;
        if (!$email) return;

        // Update delivery stats if needed
        Log::info("Email delivered to: {$email}");
    }

    protected function verifyWebhookSignature(Request $request): bool
    {
        $secret = config('services.brevo.webhook_secret');
        if (!$secret) {
            return true; // Skip verification if no secret configured
        }

        $signature = $request->header('X-Brevo-Signature');
        if (!$signature) {
            return false;
        }

        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expectedSignature, $signature);
    }
}
