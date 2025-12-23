<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('company_name')->nullable()->after('last_name');
            $table->string('company_reg_number')->nullable()->after('company_name');
            $table->string('vat_number')->nullable()->after('company_reg_number');
            $table->string('billing_address')->nullable()->after('vat_number');
            $table->string('billing_city')->nullable()->after('billing_address');
            $table->string('billing_state')->nullable()->after('billing_city');
            $table->string('billing_postal_code')->nullable()->after('billing_state');
            $table->string('billing_country')->default('Romania')->after('billing_postal_code');
            $table->string('phone')->nullable()->after('billing_country');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'company_name', 'company_reg_number',
                'vat_number', 'billing_address', 'billing_city', 'billing_state',
                'billing_postal_code', 'billing_country', 'phone'
            ]);
        });
    }
};
