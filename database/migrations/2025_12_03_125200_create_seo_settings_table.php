<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_image')->nullable();
            $table->text('robots_txt')->nullable();
            $table->boolean('sitemap_enabled')->default(true);
            $table->json('sitemap_frequencies')->nullable();
            $table->text('google_analytics')->nullable();
            $table->text('google_tag_manager')->nullable();
            $table->text('facebook_pixel')->nullable();
            $table->text('custom_head_scripts')->nullable();
            $table->text('custom_body_scripts')->nullable();
            $table->json('schema_markup')->nullable();
            $table->timestamps();
        });
        
        DB::table('seo_settings')->insert([
            'meta_title' => 'Web Agency - Professional Web Development',
            'meta_description' => 'Professional web development and digital solutions for modern businesses',
            'sitemap_enabled' => true,
            'robots_txt' => "User-agent: *\nAllow: /\nSitemap: http://cms.carphatian.ro/sitemap.xml",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
