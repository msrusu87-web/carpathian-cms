<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Email Settings (SMTP) - if not exists
        if (!Schema::hasTable('email_settings')) {
            Schema::create('email_settings', function (Blueprint $table) {
                $table->id();
                $table->string('mail_driver')->default('smtp');
                $table->string('mail_host')->nullable();
                $table->integer('mail_port')->default(587);
                $table->string('mail_username')->nullable();
                $table->string('mail_password')->nullable();
                $table->string('mail_encryption')->default('tls');
                $table->string('mail_from_address')->nullable();
                $table->string('mail_from_name')->nullable();
                $table->string('admin_notification_email')->nullable();
                $table->json('notification_preferences')->nullable();
                $table->timestamps();
            });
        }

        // User Groups / Roles Management
        if (!Schema::hasTable('user_groups')) {
            Schema::create('user_groups', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->json('permissions')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Pivot table for user groups
        if (!Schema::hasTable('user_user_group')) {
            Schema::create('user_user_group', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_group_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                
                $table->unique(['user_id', 'user_group_id']);
            });
        }

        // Email Logs
        if (!Schema::hasTable('email_logs')) {
            Schema::create('email_logs', function (Blueprint $table) {
                $table->id();
                $table->string('to_email');
                $table->string('to_name')->nullable();
                $table->string('subject');
                $table->string('template_slug')->nullable();
                $table->string('status')->default('sent');
                $table->text('error_message')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
        Schema::dropIfExists('user_user_group');
        Schema::dropIfExists('user_groups');
        Schema::dropIfExists('email_settings');
    }
};
