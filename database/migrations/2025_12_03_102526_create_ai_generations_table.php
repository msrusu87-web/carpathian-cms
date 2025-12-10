<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ai_generations', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->text('prompt');
            $table->longText('response')->nullable();
            $table->json('parameters')->nullable();
            $table->string('model')->default('groq');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->text('error_message')->nullable();
            $table->unsignedInteger('tokens_used')->nullable();
            $table->unsignedInteger('generation_time')->nullable();
            $table->timestamps();
            $table->index(['type', 'user_id']);
            $table->index('status');
        });
    }
    public function down(): void { Schema::dropIfExists('ai_generations'); }
};
