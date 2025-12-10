<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Gigs (Services offered by freelancers)
        Schema::create('gigs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->json('images')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->integer('delivery_days')->default(7);
            $table->integer('revisions')->default(1);
            $table->enum('status', ['draft', 'active', 'paused', 'archived'])->default('draft');
            $table->integer('views')->default(0);
            $table->integer('orders_in_queue')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'user_id']);
            $table->index('category_id');
        });

        // Gig Packages (Basic, Standard, Premium tiers)
        Schema::create('gig_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gig_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['basic', 'standard', 'premium']);
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('delivery_days');
            $table->integer('revisions');
            $table->json('features')->nullable();
            $table->timestamps();
            
            $table->unique(['gig_id', 'type']);
        });

        // Gig Extras (Additional services)
        Schema::create('gig_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gig_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('additional_days')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Orders
        Schema::create('freelancer_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('gig_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->enum('package_type', ['basic', 'standard', 'premium'])->nullable();
            $table->json('extras')->nullable();
            $table->text('requirements')->nullable();
            $table->decimal('amount', 10, 2);
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('seller_amount', 10, 2);
            $table->enum('status', ['pending', 'in_progress', 'in_review', 'completed', 'cancelled', 'disputed'])->default('pending');
            $table->timestamp('due_date')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['buyer_id', 'status']);
            $table->index(['seller_id', 'status']);
            $table->index('status');
        });

        // Order Deliveries
        Schema::create('order_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('freelancer_orders')->onDelete('cascade');
            $table->text('message')->nullable();
            $table->json('files')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });

        // Order Revisions
        Schema::create('order_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('freelancer_orders')->onDelete('cascade');
            $table->text('message');
            $table->json('files')->nullable();
            $table->timestamps();
        });

        // Reviews
        Schema::create('gig_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gig_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained('freelancer_orders')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->unsigned();
            $table->text('comment')->nullable();
            $table->json('ratings_breakdown')->nullable(); // communication, service_quality, etc.
            $table->text('seller_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            
            $table->index(['gig_id', 'rating']);
            $table->unique(['order_id', 'user_id']);
        });

        // Messages
        Schema::create('freelancer_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('freelancer_orders')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['sender_id', 'receiver_id']);
            $table->index(['order_id', 'is_read']);
        });

        // Freelancer Profiles
        Schema::create('freelancer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->string('tagline')->nullable();
            $table->json('skills')->nullable();
            $table->json('languages')->nullable();
            $table->json('certifications')->nullable();
            $table->string('availability')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->integer('response_time')->nullable(); // in hours
            $table->integer('completed_orders')->default(0);
            $table->decimal('total_earned', 12, 2)->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_pro')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->unique('user_id');
        });

        // Freelancer Earnings
        Schema::create('freelancer_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained('freelancer_orders')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->decimal('platform_fee', 10, 2);
            $table->decimal('net_amount', 10, 2);
            $table->enum('status', ['pending', 'available', 'withdrawn'])->default('pending');
            $table->timestamp('available_at')->nullable();
            $table->timestamp('withdrawn_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
        });

        // Withdrawal Requests
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->json('payment_details')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
        Schema::dropIfExists('freelancer_earnings');
        Schema::dropIfExists('freelancer_profiles');
        Schema::dropIfExists('freelancer_messages');
        Schema::dropIfExists('gig_reviews');
        Schema::dropIfExists('order_revisions');
        Schema::dropIfExists('order_deliveries');
        Schema::dropIfExists('freelancer_orders');
        Schema::dropIfExists('gig_extras');
        Schema::dropIfExists('gig_packages');
        Schema::dropIfExists('gigs');
    }
};
