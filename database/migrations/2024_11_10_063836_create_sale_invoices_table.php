<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->string('invoice_number')->nullable();
            $table->date('invoice_date');
            $table->string('ntn')->nullable();
            $table->string('financial_instrument_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('shipping')->nullable();
            $table->string('port_of_loading')->nullable();
            $table->string('port_of_discharge')->nullable();
            $table->string('term')->nullable();
            $table->string('hs_code')->nullable();
            $table->string('po_no')->nullable();
            $table->decimal('frieght_charges', 10, 2)->nullable();
            $table->decimal('tax_charges', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->decimal('paid_amount', 10, 2)->nullable();
            $table->decimal('pending_amount', 10, 2)->nullable();
            $table->string('note')->nullable();
            $table->string('status')->default('not_paid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_invoices');
    }
};
