<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First get existing data
        $widgets = DB::table('widgets')->get();
        
        // Add temporary columns
        Schema::table('widgets', function (Blueprint $table) {
            $table->json('title_json')->nullable()->after('title');
            $table->json('content_json')->nullable()->after('content');
        });
        
        // Convert existing data to JSON format in temporary columns
        foreach ($widgets as $widget) {
            DB::table('widgets')
                ->where('id', $widget->id)
                ->update([
                    'title_json' => json_encode(['en' => $widget->title ?? '']),
                    'content_json' => $widget->content ? json_encode(['en' => $widget->content]) : json_encode(['en' => '']),
                ]);
        }
        
        // Drop old columns and rename new ones
        Schema::table('widgets', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('content');
        });
        
        Schema::table('widgets', function (Blueprint $table) {
            $table->renameColumn('title_json', 'title');
            $table->renameColumn('content_json', 'content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First get existing data
        $widgets = DB::table('widgets')->get();
        
        // Add temporary columns
        Schema::table('widgets', function (Blueprint $table) {
            $table->string('title_string', 255)->nullable()->after('title');
            $table->text('content_string')->nullable()->after('content');
        });
        
        // Convert JSON data back to string in temporary columns
        foreach ($widgets as $widget) {
            $titleData = json_decode($widget->title, true);
            $contentData = json_decode($widget->content, true);
            
            DB::table('widgets')
                ->where('id', $widget->id)
                ->update([
                    'title_string' => $titleData['en'] ?? '',
                    'content_string' => $contentData['en'] ?? '',
                ]);
        }
        
        // Drop old columns and rename new ones
        Schema::table('widgets', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('content');
        });
        
        Schema::table('widgets', function (Blueprint $table) {
            $table->renameColumn('title_string', 'title');
            $table->renameColumn('content_string', 'content');
        });
    }
};
