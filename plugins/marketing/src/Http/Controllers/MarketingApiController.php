<?php

namespace Plugins\Marketing\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Plugins\Marketing\Services\EmailCampaignService;

class MarketingApiController extends Controller
{
    protected EmailCampaignService $emailService;

    public function __construct()
    {
        $this->emailService = new EmailCampaignService();
    }

    /**
     * Handle unsubscribe request
     */
    public function unsubscribe(string $token)
    {
        // Handle test tokens gracefully
        if ($token === 'test-token' || str_starts_with($token, 'test')) {
            return response()->view('marketing::unsubscribe-success', [
                'message' => 'This is a test unsubscribe link. In a real campaign, you would be unsubscribed from our mailing list.',
                'is_test' => true
            ]);
        }

        $result = $this->emailService->handleUnsubscribe($token);

        if ($result['success']) {
            return response()->view('marketing::unsubscribe-success', [
                'message' => $result['message']
            ]);
        }

        return response()->view('marketing::unsubscribe-error', [
            'message' => $result['message']
        ], 400);
    }

    /**
     * Track email open (invisible pixel)
     */
    public function trackOpen(string $trackingId)
    {
        $this->emailService->trackOpen($trackingId);

        // Return 1x1 transparent GIF
        $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        
        return response($pixel)
            ->header('Content-Type', 'image/gif')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Track link click and redirect
     */
    public function trackClick(string $trackingId, Request $request)
    {
        $this->emailService->trackClick($trackingId);

        $url = $request->get('url', '/');
        
        return redirect()->away($url);
    }

    /**
     * API endpoint to get campaign stats
     */
    public function campaignStats(int $campaignId)
    {
        $campaign = \Plugins\Marketing\Models\MarketingCampaign::findOrFail($campaignId);

        return response()->json([
            'id' => $campaign->id,
            'name' => $campaign->name,
            'status' => $campaign->status,
            'total_recipients' => $campaign->total_recipients,
            'emails_sent' => $campaign->emails_sent,
            'emails_opened' => $campaign->emails_opened,
            'emails_clicked' => $campaign->emails_clicked,
            'emails_bounced' => $campaign->emails_bounced,
            'emails_unsubscribed' => $campaign->emails_unsubscribed,
            'open_rate' => $campaign->open_rate,
            'click_rate' => $campaign->click_rate,
            'bounce_rate' => $campaign->bounce_rate,
        ]);
    }

    /**
     * API endpoint to get rate limits
     */
    public function rateLimits()
    {
        $remaining = \Plugins\Marketing\Models\MarketingRateLimit::getRemainingEmails();
        $limits = \Plugins\Marketing\Plugin::getRateLimits();

        return response()->json([
            'limits' => $limits,
            'remaining' => $remaining,
            'can_send' => \Plugins\Marketing\Models\MarketingRateLimit::canSendEmail(),
        ]);
    }
}
