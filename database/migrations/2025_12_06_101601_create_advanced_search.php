<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_indexes', function (Blueprint $table) {
            $table->id();
            $table->string('indexable_type');
            $table->unsignedBigInteger('indexable_id');
            $table->text('title');
            $table->text('content');
            $table->text('excerpt')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['indexable_type', 'indexable_id']);
            $table->fullText(['title', 'content']);
        });

        Schema::create('search_synonyms', function (Blueprint $table) {
            $table->id();
            $table->string('term');
            $table->json('synonyms');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('search_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('query');
            $table->string('redirect_url');
            $table->integer('hits')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_redirects');
        Schema::dropIfExists('search_synonyms');
        Schema::dropIfExists('search_indexes');
    }
};
