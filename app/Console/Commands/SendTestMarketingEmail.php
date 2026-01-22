<?php

namespace App\Console\Commands;

use App\Mail\MarketingCarphatian;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestMarketingEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marketing:test-email 
                            {email : The email address to send the test to}
                            {--name= : Optional contact name}
                            {--company= : Optional company name}
                            {--subject= : Optional email subject}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test marketing email to verify the template design';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $name = $this->option('name') ?: 'Test Contact';
        $company = $this->option('company') ?: 'Test Company';
        $subject = $this->option('subject') ?: '[TEST] Soluții Web Profesionale pentru Afacerea Ta 🚀';

        $this->info("📧 Sending test marketing email to: {$email}");
        $this->info("   Contact Name: {$name}");
        $this->info("   Company: {$company}");
        $this->newLine();

        try {
            $unsubscribeUrl = url('/api/marketing/unsubscribe/test-token');

            Mail::to($email)->send(new MarketingCarphatian(
                contactName: $name,
                companyName: $company,
                unsubscribeUrl: $unsubscribeUrl,
                emailSubject: $subject
            ));

            $this->info('✅ Test email sent successfully!');
            $this->newLine();
            $this->info('📬 Check your inbox (and spam folder) at: ' . $email);
            $this->info('   Note: This is a TEST email with [TEST] prefix in subject');
            
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Failed to send test email!');
            $this->error('   Error: ' . $e->getMessage());
            
            // Show more debug info
            $this->newLine();
            $this->warn('Debug Information:');
            $this->line('   Mail Driver: ' . config('mail.default'));
            $this->line('   Mail Host: ' . config('mail.mailers.smtp.host'));
            $this->line('   Mail Port: ' . config('mail.mailers.smtp.port'));
            $this->line('   From Address: ' . config('mail.from.address'));
            $this->line('   From Name: ' . config('mail.from.name'));
            
            return Command::FAILURE;
        }
    }
}
