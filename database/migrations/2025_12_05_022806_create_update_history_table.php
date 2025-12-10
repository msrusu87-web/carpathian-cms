<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('update_history', function (Blueprint $table) {
            $table->id();
            $table->string('package_slug');
            $table->string('package_type')->default('plugin');
            $table->string('from_version', 20);
            $table->string('to_version', 20);
            $table->text('changelog')->nullable();
            $table->enum('status', ['success', 'failed', 'rolled_back'])->default('success');
            $table->text('error_message')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('backup_path')->nullable();
            $table->timestamp('updated_at');
            
            $table->index(['package_slug', 'updated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('update_history');
    }
};
