<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketing_contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('marketing_contacts', 'google_place_id')) {
                $table->string('google_place_id')->nullable()->after('source_url');
            }
            if (!Schema::hasColumn('marketing_contacts', 'google_rating')) {
                $table->decimal('google_rating', 2, 1)->nullable()->after('google_place_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('marketing_contacts', function (Blueprint $table) {
            $table->dropColumn(['google_place_id', 'google_rating']);
        });
    }
};
