<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Products table - ensure JSON columns
        Schema::table('products', function (Blueprint $table) {
            DB::statement('ALTER TABLE products MODIFY name JSON');
            DB::statement('ALTER TABLE products MODIFY description JSON');
            DB::statement('ALTER TABLE products MODIFY content JSON');
        });

        // Pages table - ensure JSON columns
        Schema::table('pages', function (Blueprint $table) {
            DB::statement('ALTER TABLE pages MODIFY title JSON');
            DB::statement('ALTER TABLE pages MODIFY content JSON');
            DB::statement('ALTER TABLE pages MODIFY excerpt JSON');
        });

        // Posts table - ensure JSON columns
        Schema::table('posts', function (Blueprint $table) {
            DB::statement('ALTER TABLE posts MODIFY title JSON');
            DB::statement('ALTER TABLE posts MODIFY content JSON');
            DB::statement('ALTER TABLE posts MODIFY excerpt JSON');
        });
    }

    public function down(): void
    {
        // Revert to TEXT
        Schema::table('products', function (Blueprint $table) {
            DB::statement('ALTER TABLE products MODIFY name TEXT');
            DB::statement('ALTER TABLE products MODIFY description TEXT');
            DB::statement('ALTER TABLE products MODIFY content TEXT');
        });

        Schema::table('pages', function (Blueprint $table) {
            DB::statement('ALTER TABLE pages MODIFY title TEXT');
            DB::statement('ALTER TABLE pages MODIFY content TEXT');
            DB::statement('ALTER TABLE pages MODIFY excerpt TEXT');
        });

        Schema::table('posts', function (Blueprint $table) {
            DB::statement('ALTER TABLE posts MODIFY title TEXT');
            DB::statement('ALTER TABLE posts MODIFY content TEXT');
            DB::statement('ALTER TABLE posts MODIFY excerpt TEXT');
        });
    }
};
