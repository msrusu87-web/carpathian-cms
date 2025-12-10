<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_post_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('supports');
            $table->json('taxonomies')->nullable();
            $table->boolean('is_hierarchical')->default(false);
            $table->boolean('is_public')->default(true);
            $table->string('menu_icon')->nullable();
            $table->integer('menu_position')->default(5);
            $table->timestamps();
        });

        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_type_id')->constrained('custom_post_types')->onDelete('cascade');
            $table->string('name');
            $table->string('key')->unique();
            $table->enum('type', ['text', 'textarea', 'wysiwyg', 'number', 'email', 'url', 'date', 'select', 'checkbox', 'radio', 'file', 'image', 'gallery', 'repeater']);
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_id')->constrained('custom_fields')->onDelete('cascade');
            $table->string('fieldable_type');
            $table->unsignedBigInteger('fieldable_id');
            $table->text('value')->nullable();
            $table->timestamps();
            $table->index(['fieldable_type', 'fieldable_id']);
        });

        Schema::create('custom_taxonomies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_type_id')->constrained('custom_post_types')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_hierarchical')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_taxonomies');
        Schema::dropIfExists('custom_field_values');
        Schema::dropIfExists('custom_fields');
        Schema::dropIfExists('custom_post_types');
    }
};
