<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('page_builder_blocks')) {
            Schema::create('page_builder_blocks', function (Blueprint $table) {
                $table->id();
                $table->morphs('blockable'); // Can belong to Page or Post
                $table->string('type'); // hero, text, image, gallery, video, cta, etc.
                $table->string('name')->nullable();
                $table->json('content'); // Block data (text, images, settings)
                $table->json('styles')->nullable(); // Custom CSS/design
                $table->json('settings')->nullable(); // Responsive, animations, etc.
                $table->integer('order')->default(0);
                $table->boolean('is_visible')->default(true);
                $table->timestamps();

                // NOTE: morphs('blockable') already creates an index on
                // (blockable_type, blockable_id) named
                // page_builder_blocks_blockable_type_blockable_id_index.
                $table->index('order');
            });
        }

        if (!Schema::hasTable('page_builder_templates')) {
            Schema::create('page_builder_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('category')->nullable();
                $table->json('blocks'); // Saved block configuration
                $table->string('thumbnail')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('page_builder_blocks');
        Schema::dropIfExists('page_builder_templates');
    }
};
