<?php
/**
 * Script to help clean bounced contacts from your email list
 * 
 * Usage:
 * php cleanup-bounced-contacts.php --mode=report
 * php cleanup-bounced-contacts.php --mode=clean --emails="email1@example.com,email2@example.com"
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Plugins\Marketing\Models\MarketingContact;
use Plugins\Marketing\Models\MarketingCampaignLog;

// Parse command line arguments
$options = getopt('', ['mode:', 'emails:']);
$mode = $options['mode'] ?? 'report';

echo "\n📧 Bounced Contact Cleanup Tool\n";
echo "================================\n\n";

switch ($mode) {
    case 'report':
        generateReport();
        break;
        
    case 'clean':
        if (!isset($options['emails'])) {
            echo "❌ Error: --emails parameter required for clean mode\n";
            echo "Usage: php cleanup-bounced-contacts.php --mode=clean --emails=\"email1@example.com,email2@example.com\"\n";
            exit(1);
        }
        cleanContacts($options['emails']);
        break;
        
    case 'export':
        exportFailedContacts();
        break;
        
    default:
        echo "❌ Invalid mode. Use: report, clean, or export\n";
        exit(1);
}

function generateReport()
{
    echo "📊 Campaign Analysis Report\n";
    echo "----------------------------\n\n";
    
    // Get campaign stats
    $campaign = \Plugins\Marketing\Models\MarketingCampaign::find(1);
    if (!$campaign) {
        echo "❌ Campaign not found\n";
        return;
    }
    
    echo "Campaign: {$campaign->name}\n";
    echo "Status: {$campaign->status}\n";
    echo "Total Recipients: {$campaign->total_recipients}\n";
    echo "Emails Sent: {$campaign->emails_sent}\n";
    echo "Bounced: {$campaign->emails_bounced}\n";
    echo "Open Rate: {$campaign->open_rate}%\n";
    echo "Click Rate: {$campaign->click_rate}%\n\n";
    
    // Get failed logs
    echo "❌ Failed Email Logs:\n";
    echo "--------------------\n";
    $failed = MarketingCampaignLog::where('campaign_id', 1)
        ->where('status', 'failed')
        ->with('contact')
        ->get();
    
    echo "Total failed: {$failed->count()}\n\n";
    
    $errors = [];
    foreach ($failed as $log) {
        $errorMsg = $log->error_message ?? 'Unknown error';
        if (!isset($errors[$errorMsg])) {
            $errors[$errorMsg] = [];
        }
        $errors[$errorMsg][] = $log->contact->email ?? 'Unknown';
    }
    
    foreach ($errors as $error => $emails) {
        echo "\n📌 Error: {$error}\n";
        echo "   Count: " . count($emails) . " emails\n";
        echo "   Emails: " . implode(', ', array_slice($emails, 0, 5));
        if (count($emails) > 5) {
            echo " ... and " . (count($emails) - 5) . " more";
        }
        echo "\n";
    }
    
    // Contact status summary
    echo "\n\n📋 Contact Status Summary:\n";
    echo "-------------------------\n";
    $statuses = MarketingContact::selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->get();
    
    foreach ($statuses as $status) {
        echo "{$status->status}: {$status->count} contacts\n";
    }
    
    echo "\n💡 Recommendations:\n";
    echo "------------------\n";
    echo "1. Export bounced emails from Brevo dashboard\n";
    echo "2. Use --mode=clean to mark them as bounced\n";
    echo "3. Use --mode=export to export failed contacts for review\n\n";
}

function cleanContacts($emailsString)
{
    $emails = array_map('trim', explode(',', $emailsString));
    
    echo "🧹 Cleaning Contacts\n";
    echo "-------------------\n";
    echo "Emails to mark as bounced: " . count($emails) . "\n\n";
    
    $updated = 0;
    $notFound = 0;
    
    foreach ($emails as $email) {
        $contact = MarketingContact::where('email', $email)->first();
        
        if ($contact) {
            $oldStatus = $contact->status;
            $contact->update(['status' => 'bounced']);
            echo "✅ {$email} - Status changed from '{$oldStatus}' to 'bounced'\n";
            $updated++;
        } else {
            echo "❌ {$email} - Not found in database\n";
            $notFound++;
        }
    }
    
    echo "\n📊 Summary:\n";
    echo "Updated: {$updated}\n";
    echo "Not found: {$notFound}\n\n";
    
    if ($updated > 0) {
        echo "✅ Successfully updated {$updated} contact(s)\n";
        echo "💡 These contacts will be excluded from future campaigns\n\n";
    }
}

function exportFailedContacts()
{
    echo "📤 Exporting Failed Contacts\n";
    echo "---------------------------\n\n";
    
    $failed = MarketingCampaignLog::where('campaign_id', 1)
        ->where('status', 'failed')
        ->with('contact')
        ->get();
    
    if ($failed->isEmpty()) {
        echo "No failed contacts found.\n";
        return;
    }
    
    $filename = 'failed-contacts-' . date('Y-m-d-His') . '.csv';
    $filepath = __DIR__ . '/' . $filename;
    
    $fp = fopen($filepath, 'w');
    fputcsv($fp, ['Email', 'Company Name', 'Contact Name', 'Error Message', 'Failed At']);
    
    foreach ($failed as $log) {
        fputcsv($fp, [
            $log->contact->email ?? '',
            $log->contact->company_name ?? '',
            $log->contact->contact_name ?? '',
            $log->error_message ?? '',
            $log->sent_at ?? $log->created_at,
        ]);
    }
    
    fclose($fp);
    
    echo "✅ Exported {$failed->count()} failed contacts to: {$filename}\n";
    echo "💡 Review this file and cross-reference with Brevo's bounce report\n\n";
}

echo "\n✅ Done!\n\n";
