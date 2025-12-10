<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('api_keys')) {
            Schema::create('api_keys', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('key')->unique();
                $table->string('secret');
                $table->json('permissions')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->enum('type', ['percentage', 'fixed'])->default('percentage');
                $table->decimal('value', 10, 2);
                $table->decimal('min_purchase', 10, 2)->nullable();
                $table->integer('max_uses')->nullable();
                $table->integer('used_count')->default(0);
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('forms')) {
            Schema::create('forms', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->json('fields');
                $table->json('settings')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('languages')) {
            Schema::create('languages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code', 10)->unique();
                $table->string('locale', 10);
                $table->enum('direction', ['ltr', 'rtl'])->default('ltr');
                $table->boolean('is_default')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('notification_templates')) {
            Schema::create('notification_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type');
                $table->string('subject');
                $table->text('content');
                $table->json('variables')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('payment_gateways')) {
            Schema::create('payment_gateways', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('credentials');
                $table->json('settings')->nullable();
                $table->boolean('is_active')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_variants')) {
            Schema::create('product_variants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->string('sku')->unique();
                $table->string('name');
                $table->decimal('price', 10, 2);
                $table->integer('stock')->default(0);
                $table->json('attributes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('workflows')) {
            Schema::create('workflows', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('trigger');
                $table->json('conditions')->nullable();
                $table->json('actions');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('workflows');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('payment_gateways');
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('forms');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('api_keys');
    }
};
