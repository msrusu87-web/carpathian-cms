<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Freelancer Profiles
        Schema::create('freelancer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->json('skills')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('avatar')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->decimal('total_earnings', 12, 2)->default(0);
            $table->boolean('is_available')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });

        // Freelancer Gigs (Services)
        Schema::create('freelancer_gigs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freelancer_profile_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->json('tags')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('delivery_days');
            $table->json('images')->nullable();
            $table->integer('revisions')->default(1);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Freelancer Orders
        Schema::create('freelancer_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gig_id')->constrained('freelancer_gigs')->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'in_progress', 'delivered', 'completed', 'cancelled', 'disputed'])->default('pending');
            $table->text('requirements')->nullable();
            $table->text('delivery_note')->nullable();
            $table->json('delivery_files')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->text('review')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('freelancer_orders');
        Schema::dropIfExists('freelancer_gigs');
        Schema::dropIfExists('freelancer_profiles');
    }
};
