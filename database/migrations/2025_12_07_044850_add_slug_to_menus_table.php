<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('menus', 'slug')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
            });
            
            // Generate slugs for existing menus
            $menus = DB::table('menus')->get();
            foreach ($menus as $menu) {
                DB::table('menus')
                    ->where('id', $menu->id)
                    ->update(['slug' => Str::slug($menu->name ?: 'menu-' . $menu->id)]);
            }
            
            // Now make it unique
            Schema::table('menus', function (Blueprint $table) {
                $table->string('slug')->unique()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('menus', 'slug')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
