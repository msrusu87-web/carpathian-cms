<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('backup_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('frequency', ['hourly', 'daily', 'weekly', 'monthly']);
            $table->json('includes'); // database, files, media, etc.
            $table->string('storage_path');
            $table->integer('retention_days')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();
        });

        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->nullable()->constrained('backup_schedules')->onDelete('set null');
            $table->string('name');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size');
            $table->enum('status', ['creating', 'completed', 'failed'])->default('creating');
            $table->text('error_message')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backups');
        Schema::dropIfExists('backup_schedules');
    }
};
