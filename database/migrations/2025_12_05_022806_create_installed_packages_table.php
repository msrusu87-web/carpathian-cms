<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installed_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['plugin', 'template', 'theme', 'module'])->index();
            $table->string('version', 20);
            $table->text('description')->nullable();
            $table->string('author')->nullable();
            $table->string('author_url')->nullable();
            $table->string('install_path');
            $table->boolean('is_active')->default(false)->index();
            $table->json('settings')->nullable();
            $table->json('requirements')->nullable();
            $table->string('update_url')->nullable();
            $table->timestamp('last_checked')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installed_packages');
    }
};
