<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_settings', function (Blueprint $table) {
            $table->id();
            $table->string('currency')->default('USD');
            $table->string('currency_symbol')->default('$');
            $table->string('currency_position')->default('before'); // before, after
            $table->boolean('tax_enabled')->default(false);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->boolean('shipping_enabled')->default(false);
            $table->json('payment_gateways')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->text('privacy_policy')->nullable();
            $table->text('return_policy')->nullable();
            $table->string('order_prefix')->default('ORD-');
            $table->boolean('inventory_management')->default(true);
            $table->boolean('low_stock_alert')->default(true);
            $table->integer('low_stock_threshold')->default(5);
            $table->timestamps();
        });
        
        // Insert default shop settings
        DB::table('shop_settings')->insert([
            'currency' => 'USD',
            'currency_symbol' => '$',
            'currency_position' => 'before',
            'tax_enabled' => false,
            'payment_gateways' => json_encode([
                'paypal_classic' => ['enabled' => false, 'email' => ''],
                'paypal_api' => ['enabled' => false, 'client_id' => '', 'secret' => ''],
                'stripe' => ['enabled' => false, 'public_key' => '', 'secret_key' => ''],
                'bank_transfer' => ['enabled' => false, 'bank_name' => '', 'account_name' => '', 'iban' => '', 'bic_swift' => '', 'instructions' => '']
            ]),
            'order_prefix' => 'ORD-',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_settings');
    }
};
