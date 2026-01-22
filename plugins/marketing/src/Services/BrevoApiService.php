<?php

namespace Plugins\Marketing\Services;

use Brevo\Client\Configuration;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Api\ContactsApi;
use Brevo\Client\Api\EmailCampaignsApi;
use Brevo\Client\Model\SendSmtpEmail;
use Brevo\Client\Model\CreateSmtpEmail;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class BrevoApiService
{
    protected $apiKey;
    protected $config;
    protected $apiInstance;

    public function __construct()
    {
        $this->apiKey = config('services.brevo.api_key');
        
        if ($this->apiKey) {
            $config = \Brevo\Client\Configuration::getDefaultConfiguration()
                ->setApiKey('api-key', $this->apiKey);
            $this->apiInstance = new \Brevo\Client\Api\TransactionalEmailsApi(
                new \GuzzleHttp\Client(),
                $config = Brevo\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->apiKey)
            );
        }
    }

    /**
     * Send email via Brevo API
     */
    public function sendEmail(array $data): array
    {
        if (!$this->enabled || empty($this->apiKey)) {
            return ['success' => false, 'error' => 'Brevo API not configured'];
        }

        try {
            $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->apiKey);
            $apiInstance = new TransactionalEmailsApi(new Client(), $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->apiKey));

            $sendEmail = new SendSmtpEmail([
                'to' => [['email' => $email, 'name' => $name]],
                'sender' => [
                    'name' => $this->fromName,
                    'email' => $this->fromEmail,
                ],
                'subject' => $subject,
                'htmlContent' => $htmlContent,
                'textContent' => $textContent,
                'tags' => $tags,
                'headers' => [
                    'X-Campaign-ID' => $campaignId,
                    'X-Contact-ID' => $contactId,
                ],
            ]);

            return [
                'success' => true,
                'message_id' => $result->getMessageId(),
                'brevo_id' => $result->getMessageIds()[0] ?? null,
            ];

        } catch (\Exception $e) {
            Log::error('Brevo API send failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Sync campaign statistics from Brevo
     */
    public function syncCampaignStats(MarketingCampaign $campaign, ?string $brevoMessageId = null): array
    {
        try {
            if (!$this->isConfigured()) {
                return ['success' => false, 'message' => 'Brevo API not configured'];
            }

            // Get campaign statistics from Brevo
            $apiInstance = new \Brevo\Client\Api\EmailCampaignsApi(null, $this->config);
            
            // Note: If you have the Brevo campaign ID stored, you can fetch stats directly
            // For now, we'll update based on webhook data
            
            return ['success' => true, 'message' => 'Use webhooks for real-time stats'];
        } catch (\Exception $e) {
            Log::error('Brevo stats sync failed: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get campaign statistics from Brevo
     */
    public function getCampaignStats(int $brevoId): ?array
    {
        try {
            $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', config('services.brevo.api_key'));
            $apiInstance = new TransactionalEmailsApi(new Client(), $config);
            
            // Get campaign stats from Brevo
            $result = $apiInstance->getEmailCampaigns($brevoId);
            
            return [
                'success' => true,
                'stats' => $result
            ];
        } catch (\Exception $e) {
            Log::error("Failed to get Brevo stats: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
