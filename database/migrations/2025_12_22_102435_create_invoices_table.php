<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            
            // Client billing info (snapshot at time of invoice)
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_company')->nullable();
            $table->string('client_company_reg')->nullable();
            $table->string('client_vat_number')->nullable();
            $table->text('client_address');
            $table->string('client_city');
            $table->string('client_postal_code');
            $table->string('client_country');
            $table->string('client_phone')->nullable();
            
            // Invoice details
            $table->date('invoice_date');
            $table->date('due_date');
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])->default('unpaid');
            
            // Amounts
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(19.00); // TVA 19%
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            
            // Payment info
            $table->date('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
            $table->text('footer_text')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
