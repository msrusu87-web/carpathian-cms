<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('global_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('My CMS Website');
            $table->string('site_domain')->nullable();
            $table->string('site_logo')->nullable();
            $table->string('site_favicon')->nullable();
            $table->string('admin_email')->nullable();
            $table->text('site_description')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('date_format')->default('Y-m-d');
            $table->string('time_format')->default('H:i:s');
            $table->boolean('maintenance_mode')->default(false);
            $table->text('maintenance_message')->nullable();
            $table->json('social_links')->nullable();
            $table->json('custom_scripts')->nullable();
            $table->timestamps();
        });
        
        // Insert default settings
        DB::table('global_settings')->insert([
            'site_name' => 'Web Agency CMS',
            'site_domain' => 'cms.carphatian.ro',
            'admin_email' => 'admin@example.com',
            'site_description' => 'Professional web development and digital solutions',
            'timezone' => 'UTC',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('global_settings');
    }
};
