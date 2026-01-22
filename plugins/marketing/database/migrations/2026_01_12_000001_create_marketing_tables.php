<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Marketing Contacts - scraped or imported contacts
        Schema::create('marketing_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Romania');
            $table->string('source')->default('manual'); // manual, scraper, import
            $table->string('source_url')->nullable();
            $table->json('tags')->nullable();
            $table->json('custom_fields')->nullable();
            $table->enum('status', ['active', 'unsubscribed', 'bounced', 'invalid'])->default('active');
            $table->boolean('email_verified')->default(false);
            $table->boolean('has_consent')->default(false);
            $table->timestamp('consent_date')->nullable();
            $table->timestamp('last_contacted')->nullable();
            $table->integer('emails_sent')->default(0);
            $table->integer('emails_opened')->default(0);
            $table->integer('emails_clicked')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['email'], 'unique_email');
        });

        // Marketing Lists - group contacts for campaigns
        Schema::create('marketing_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('contact_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Pivot table for contacts <-> lists
        Schema::create('marketing_contact_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained('marketing_contacts')->onDelete('cascade');
            $table->foreignId('list_id')->constrained('marketing_lists')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['contact_id', 'list_id']);
        });

        // Email Templates
        Schema::create('marketing_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->longText('body_html');
            $table->text('body_text')->nullable();
            $table->json('variables')->nullable(); // {{company_name}}, {{contact_name}}, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Marketing Campaigns
        Schema::create('marketing_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->longText('body_html');
            $table->text('body_text')->nullable();
            $table->foreignId('list_id')->nullable()->constrained('marketing_lists')->nullOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('marketing_templates')->nullOnDelete();
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'paused', 'cancelled'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('total_recipients')->default(0);
            $table->integer('emails_sent')->default(0);
            $table->integer('emails_opened')->default(0);
            $table->integer('emails_clicked')->default(0);
            $table->integer('emails_bounced')->default(0);
            $table->integer('emails_unsubscribed')->default(0);
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->string('reply_to')->nullable();
            $table->timestamps();
        });

        // Campaign send log - tracks individual email sends
        Schema::create('marketing_campaign_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('marketing_campaigns')->onDelete('cascade');
            $table->foreignId('contact_id')->constrained('marketing_contacts')->onDelete('cascade');
            $table->enum('status', ['pending', 'sent', 'opened', 'clicked', 'bounced', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->string('tracking_id')->unique();
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            $table->index(['campaign_id', 'status']);
        });

        // Scrape Jobs - track scraping operations
        Schema::create('marketing_scrape_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('urls')->nullable(); // JSON array of URLs to scrape
            $table->string('search_query')->nullable(); // Alternative: search query
            $table->string('search_location')->nullable();
            $table->enum('status', ['pending', 'running', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->integer('total_urls')->default(0);
            $table->integer('processed_urls')->default(0);
            $table->integer('contacts_found')->default(0);
            $table->json('settings')->nullable(); // scraper settings
            $table->text('error_log')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Scraped data - raw data before processing
        Schema::create('marketing_scraped_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('marketing_scrape_jobs')->onDelete('cascade');
            $table->string('url');
            $table->string('domain')->nullable();
            $table->json('extracted_data')->nullable();
            $table->enum('status', ['pending', 'processed', 'failed', 'duplicate'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });

        // Unsubscribe tokens
        Schema::create('marketing_unsubscribes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained('marketing_contacts')->onDelete('cascade');
            $table->foreignId('campaign_id')->nullable()->constrained('marketing_campaigns')->nullOnDelete();
            $table->string('token')->unique();
            $table->string('reason')->nullable();
            $table->timestamp('unsubscribed_at');
            $table->timestamps();
        });

        // Rate limiting tracker
        Schema::create('marketing_rate_limits', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'email', 'scrape'
            $table->integer('count')->default(0);
            $table->timestamp('window_start');
            $table->timestamps();
            
            $table->index(['type', 'window_start']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_rate_limits');
        Schema::dropIfExists('marketing_unsubscribes');
        Schema::dropIfExists('marketing_scraped_data');
        Schema::dropIfExists('marketing_scrape_jobs');
        Schema::dropIfExists('marketing_campaign_logs');
        Schema::dropIfExists('marketing_campaigns');
        Schema::dropIfExists('marketing_templates');
        Schema::dropIfExists('marketing_contact_list');
        Schema::dropIfExists('marketing_lists');
        Schema::dropIfExists('marketing_contacts');
    }
};
