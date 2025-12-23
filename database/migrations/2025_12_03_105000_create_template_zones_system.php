<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Template Zones table
        Schema::create('template_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // header, body, footer
            $table->string('identifier'); // header-zone-1
            $table->integer('order')->default(0);
            $table->json('settings')->nullable(); // logo, menu_style, etc
            $table->json('styles')->nullable(); // CSS properties
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['template_id', 'name']);
        });

        // Menu Styles table
        Schema::create('menu_styles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Horizontal, Dropdown, Mega Menu, Sidebar
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('html_template'); // HTML structure
            $table->text('css_template')->nullable(); // CSS styles
            $table->json('config')->nullable(); // Additional settings
            $table->string('preview_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_styles');
        Schema::dropIfExists('template_zones');
    }
};
