<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndexIfNotExists('posts', 'status');
        $this->addIndexIfNotExists('posts', 'published_at');
        $this->addIndexIfNotExists('posts', 'featured');
        $this->addIndexIfNotExists('posts', 'status_published_at', ['status', 'published_at']);
        $this->addIndexIfNotExists('posts', 'category_status', ['category_id', 'status']);
        
        $this->addIndexIfNotExists('pages', 'status');
        $this->addIndexIfNotExists('pages', 'published_at');
        $this->addIndexIfNotExists('pages', 'is_homepage');
        $this->addIndexIfNotExists('pages', 'show_in_menu');
        $this->addIndexIfNotExists('pages', 'status_order', ['status', 'order']);
        
        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'parent_id')) {
            $this->addIndexIfNotExists('categories', 'parent_id');
        }
        
        if (Schema::hasTable('products')) {
            if (Schema::hasColumn('products', 'status')) $this->addIndexIfNotExists('products', 'status');
            if (Schema::hasColumn('products', 'is_featured')) $this->addIndexIfNotExists('products', 'is_featured');
            if (Schema::hasColumn('products', 'stock')) $this->addIndexIfNotExists('products', 'stock');
        }
        
        $this->addIndexIfNotExists('media', 'mime_type');
        $this->addIndexIfNotExists('media', 'type');
        $this->addIndexIfNotExists('media', 'user_id');
        $this->addIndexIfNotExists('users', 'created_at');
    }

    protected function addIndexIfNotExists(string $table, string $indexName, ?array $columns = null): void
    {
        if ($columns === null) {
            $columns = [$indexName];
            $indexName = "{$table}_{$indexName}_index";
        } else {
            $indexName = "{$table}_{$indexName}_index";
        }
        
        $exists = DB::select("SHOW INDEX FROM `$table` WHERE Key_name = ?", [$indexName]);
        
        if (empty($exists)) {
            $columnStr = implode('`, `', $columns);
            DB::statement("ALTER TABLE `$table` ADD INDEX `$indexName` (`$columnStr`)");
        }
    }

    public function down(): void
    {
        $indexes = [
            'posts' => ['status', 'published_at', 'featured', 'status_published_at', 'category_status'],
            'pages' => ['status', 'published_at', 'is_homepage', 'show_in_menu', 'status_order'],
            'media' => ['mime_type', 'type', 'user_id'],
            'users' => ['created_at']
        ];
        
        foreach ($indexes as $table => $indexList) {
            if (!Schema::hasTable($table)) continue;
            foreach ($indexList as $index) {
                $indexName = "{$table}_{$index}_index";
                $exists = DB::select("SHOW INDEX FROM `$table` WHERE Key_name = ?", [$indexName]);
                if (!empty($exists)) {
                    DB::statement("ALTER TABLE `$table` DROP INDEX `$indexName`");
                }
            }
        }
    }
};
