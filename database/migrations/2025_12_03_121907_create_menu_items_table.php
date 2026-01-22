<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->onDelete('cascade');
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('type')->default('custom');
            $table->foreignId('reference_id')->nullable();
            $table->string('target')->default('_self');
            $table->string('icon')->nullable();
            $table->string('css_class')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->json('menu_locations')->nullable()->after('show_in_menu');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('menu_locations');
        });
        
        Schema::dropIfExists('menu_items');
    }
};
