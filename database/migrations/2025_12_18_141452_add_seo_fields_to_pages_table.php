<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('canonical_url')->nullable()->after('meta_keywords');
            $table->string('robots_meta')->default('index,follow')->after('canonical_url');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['canonical_url', 'robots_meta']);
        });
    }
};
