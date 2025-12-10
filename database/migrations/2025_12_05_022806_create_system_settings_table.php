<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
        // Insert default settings
        DB::table('system_settings')->insert([
            ['key' => 'auto_updates', 'value' => 'disabled', 'type' => 'string', 'description' => 'Automatic update mode: disabled, minor, all', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'update_server', 'value' => 'https://updates.carphatian.ro', 'type' => 'url', 'description' => 'Update server URL', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'backup_retention_days', 'value' => '30', 'type' => 'integer', 'description' => 'Number of days to keep backups', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
