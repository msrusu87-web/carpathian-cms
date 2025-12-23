<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('client')->nullable();
            $table->string('category')->default('web_development'); // web_development, ai_platform, blockchain, etc.
            $table->text('short_description');
            $table->longText('full_description')->nullable();
            $table->string('image')->nullable();
            $table->string('gallery')->nullable(); // JSON array of images
            $table->string('website_url')->nullable();
            $table->json('technologies')->nullable(); // ["Laravel", "Tailwind", "MySQL"]
            $table->json('services')->nullable(); // ["Web Design", "Branding", "VPS Setup"]
            $table->date('completion_date')->nullable();
            $table->string('gradient_from')->default('purple-600');
            $table->string('gradient_to')->default('pink-600');
            $table->string('badge_color')->default('purple-700');
            $table->integer('order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
