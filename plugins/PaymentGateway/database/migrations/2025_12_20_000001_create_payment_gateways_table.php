<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check if table exists from core system
        if (!Schema::hasTable('payment_gateways')) {
            Schema::create('payment_gateways', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('provider');
                $table->json('credentials')->nullable();
                $table->json('config')->nullable();
                $table->decimal('fee_percentage', 5, 2)->default(0);
                $table->decimal('fee_fixed', 10, 2)->default(0);
                $table->boolean('supports_quick_links')->default(false);
                $table->boolean('supports_product_checkout')->default(true);
                $table->boolean('is_active')->default(false);
                $table->boolean('test_mode')->default(true);
                $table->string('webhook_url')->nullable();
                $table->string('callback_url')->nullable();
                $table->timestamps();
            });
        } else {
            // Add new columns if they don't exist
            Schema::table('payment_gateways', function (Blueprint $table) {
                // Add slug if missing
                if (!Schema::hasColumn('payment_gateways', 'slug')) {
                    $table->string('slug')->nullable()->after('name');
                }
                
                // Rename settings to config if needed
                if (Schema::hasColumn('payment_gateways', 'settings') && !Schema::hasColumn('payment_gateways', 'config')) {
                    $table->renameColumn('settings', 'config');
                }
                
                // Add fee columns if missing
                if (!Schema::hasColumn('payment_gateways', 'fee_percentage')) {
                    $table->decimal('fee_percentage', 5, 2)->default(0)->after('config');
                }
                if (!Schema::hasColumn('payment_gateways', 'fee_fixed')) {
                    $table->decimal('fee_fixed', 10, 2)->default(0)->after('fee_percentage');
                }
                
                // Add quick links support
                if (!Schema::hasColumn('payment_gateways', 'supports_quick_links')) {
                    $table->boolean('supports_quick_links')->default(false)->after('fee_fixed');
                }
                
                // Add product checkout support
                if (!Schema::hasColumn('payment_gateways', 'supports_product_checkout')) {
                    $table->boolean('supports_product_checkout')->default(true)->after('supports_quick_links');
                }
                
                // Rename is_test_mode to test_mode if needed
                if (Schema::hasColumn('payment_gateways', 'is_test_mode') && !Schema::hasColumn('payment_gateways', 'test_mode')) {
                    $table->renameColumn('is_test_mode', 'test_mode');
                }
                
                // Add webhooks
                if (!Schema::hasColumn('payment_gateways', 'webhook_url')) {
                    $table->string('webhook_url')->nullable()->after('is_active');
                }
                if (!Schema::hasColumn('payment_gateways', 'callback_url')) {
                    $table->string('callback_url')->nullable()->after('webhook_url');
                }
                
                // Drop sort_order and description if they exist (not needed)
                if (Schema::hasColumn('payment_gateways', 'sort_order')) {
                    $table->dropColumn('sort_order');
                }
                if (Schema::hasColumn('payment_gateways', 'description')) {
                    $table->dropColumn('description');
                }
            });
            
            // Make slug unique after adding data
            Schema::table('payment_gateways', function (Blueprint $table) {
                if (Schema::hasColumn('payment_gateways', 'slug')) {
                    // First, update null slugs
                    \DB::statement("UPDATE payment_gateways SET slug = LOWER(REPLACE(provider, ' ', '-')) WHERE slug IS NULL");
                    
                    // Then make it unique
                    $table->string('slug')->unique()->change();
                }
            });
        }
    }

    public function down(): void
    {
        // Don't drop the table as it might be used by core
        // Just remove the columns we added
        if (Schema::hasTable('payment_gateways')) {
            Schema::table('payment_gateways', function (Blueprint $table) {
                $columnsToRemove = [
                    'supports_quick_links',
                    'supports_product_checkout',
                    'webhook_url',
                    'callback_url',
                    'fee_percentage',
                    'fee_fixed',
                ];
                
                foreach ($columnsToRemove as $column) {
                    if (Schema::hasColumn('payment_gateways', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
