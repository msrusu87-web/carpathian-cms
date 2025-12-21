<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('redirects')) Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_url', 500)->unique();
            $table->string('to_url', 500);
            $table->integer('status_code')->default(301); // 301 permanent, 302 temporary
            $table->boolean('is_active')->default(true);
            $table->integer('hits')->default(0);
            $table->timestamp('last_hit_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('from_url');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redirects');
    }
};
