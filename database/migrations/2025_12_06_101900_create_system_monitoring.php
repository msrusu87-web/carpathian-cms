<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_health_checks', function (Blueprint $table) {
            $table->id();
            $table->string('check_name');
            $table->enum('status', ['healthy', 'warning', 'critical'])->default('healthy');
            $table->json('metrics')->nullable();
            $table->text('message')->nullable();
            $table->timestamp('checked_at');
            $table->timestamps();
        });

        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->string('level');
            $table->string('channel')->nullable();
            $table->text('message');
            $table->json('context')->nullable();
            $table->timestamps();
            
            $table->index(['level', 'created_at']);
        });

        Schema::create('performance_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('metric_type');
            $table->string('metric_name');
            $table->decimal('value', 10, 2);
            $table->string('unit')->nullable();
            $table->json('tags')->nullable();
            $table->timestamp('measured_at');
            $table->timestamps();
            
            $table->index(['metric_type', 'measured_at']);
        });

        Schema::create('database_query_logs', function (Blueprint $table) {
            $table->id();
            $table->text('query');
            $table->decimal('execution_time', 10, 4);
            $table->json('bindings')->nullable();
            $table->string('connection')->default('mysql');
            $table->timestamps();
            
            $table->index('execution_time');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('database_query_logs');
        Schema::dropIfExists('performance_metrics');
        Schema::dropIfExists('system_logs');
        Schema::dropIfExists('system_health_checks');
    }
};
