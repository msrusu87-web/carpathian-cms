<?php

namespace Plugins\Marketing\Console\Commands;

use Illuminate\Console\Command;
use Plugins\Marketing\Models\MarketingCampaign;
use Plugins\Marketing\Services\BrevoApiService;

class SyncBrevoStatsCommand extends Command
{
    protected $signature = 'brevo:sync-stats {campaign_id? : Specific campaign ID to sync}';
    protected $description = 'Sync campaign statistics from Brevo';

    public function handle()
    {
        if (!config('services.brevo.use_api')) {
            $this->error('Brevo API is not enabled. Set BREVO_USE_API=true in .env');
            return 1;
        }

        $campaignId = $this->argument('campaign_id');
        
        if ($campaignId) {
            return $this->syncCampaign($campaignId);
        }

        // Sync all recent campaigns
        $campaigns = MarketingCampaign::whereIn('status', ['sending', 'sent'])
            ->where('created_at', '>=', now()->subDays(30))
            ->get();

        $this->info("Syncing {$campaigns->count()} campaigns...");
        
        foreach ($campaigns as $campaign) {
            $this->syncCampaign($campaign->id);
        }

        $this->info('Sync completed!');
        return 0;
    }

    protected function syncCampaign(int $campaignId): int
    {
        $campaign = MarketingCampaign::find($campaignId);
        
        if (!$campaign) {
            $this->error("Campaign {$campaignId} not found");
            return 1;
        }

        $this->info("Syncing campaign: {$campaign->name}");

        try {
            $brevoService = app(BrevoApiService::class);
            
            // Note: This would require storing Brevo campaign ID in your database
            // For now, we'll just log that manual sync is needed
            $this->warn("Manual sync from Brevo dashboard required");
            $this->info("Campaign stats in CMS:");
            $this->line("  Sent: {$campaign->emails_sent}");
            $this->line("  Opened: {$campaign->emails_opened}");
            $this->line("  Clicked: {$campaign->emails_clicked}");
            $this->line("  Bounced: {$campaign->emails_bounced}");
            
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to sync campaign: " . $e->getMessage());
            return 1;
        }
    }
}
