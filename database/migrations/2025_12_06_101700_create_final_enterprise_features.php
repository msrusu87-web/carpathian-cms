<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Advanced Caching
        Schema::create('cache_rules', function (Blueprint $table) {
            $table->id();
            $table->string('path_pattern');
            $table->integer('ttl')->default(3600);
            $table->json('conditions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Real-time Features
        Schema::create('websocket_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('socket_id')->unique();
            $table->json('channels')->nullable();
            $table->timestamp('connected_at');
            $table->timestamp('last_activity_at');
            $table->timestamps();
        });

        // Activity Monitoring
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action');
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('changes')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['subject_type', 'subject_id']);
            $table->index(['user_id', 'created_at']);
        });

        // Email Templates
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('subject');
            $table->text('body_html');
            $table->text('body_text')->nullable();
            $table->json('variables')->nullable();
            $table->json('attachments')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Import/Export Jobs
        Schema::create('import_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // posts, users, products, etc.
            $table->string('file_path');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->integer('total_rows')->default(0);
            $table->integer('processed_rows')->default(0);
            $table->integer('failed_rows')->default(0);
            $table->json('errors')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Content Scheduling
        Schema::create('scheduled_contents', function (Blueprint $table) {
            $table->id();
            $table->string('contentable_type');
            $table->unsignedBigInteger('contentable_id');
            $table->enum('action', ['publish', 'unpublish', 'delete']);
            $table->timestamp('scheduled_for');
            $table->enum('status', ['pending', 'executed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->timestamps();
            
            $table->index(['contentable_type', 'contentable_id']);
            $table->index(['scheduled_for', 'status']);
        });

        // User Sessions
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('session_id')->unique();
            $table->string('ip_address');
            $table->text('user_agent');
            $table->json('location')->nullable();
            $table->timestamp('last_activity_at');
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
        Schema::dropIfExists('scheduled_contents');
        Schema::dropIfExists('import_jobs');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('websocket_connections');
        Schema::dropIfExists('cache_rules');
    }
};
