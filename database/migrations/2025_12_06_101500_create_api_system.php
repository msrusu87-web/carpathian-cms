<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('key', 64)->unique();
            $table->json('permissions')->nullable();
            $table->integer('rate_limit')->default(1000);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_key_id')->nullable()->constrained()->onDelete('set null');
            $table->string('endpoint');
            $table->string('method');
            $table->integer('status_code');
            $table->string('ip_address');
            $table->integer('response_time');
            $table->timestamps();
            
            $table->index(['endpoint', 'created_at']);
        });

        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('url');
            $table->json('events');
            $table->string('secret')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('failed_attempts')->default(0);
            $table->timestamp('last_triggered_at')->nullable();
            $table->timestamps();
        });

        Schema::create('webhook_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webhook_id')->constrained()->onDelete('cascade');
            $table->string('event');
            $table->json('payload');
            $table->integer('status_code')->nullable();
            $table->text('response')->nullable();
            $table->enum('status', ['pending', 'delivered', 'failed'])->default('pending');
            $table->integer('attempts')->default(0);
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_deliveries');
        Schema::dropIfExists('webhooks');
        Schema::dropIfExists('api_logs');
        Schema::dropIfExists('api_keys');
    }
};
