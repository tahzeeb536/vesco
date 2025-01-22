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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date('order_date');
            $table->date('email_date');
            $table->date('delivery_date');
            $table->string('order_name');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->string('invoice_number')->nullable();
            $table->string('status');
            $table->string('currency');
            $table->decimal('order_amount', 10, 2)->default(0);
            $table->decimal('damage_amount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->string('order_file_admin')->nullable();
            $table->string('order_file_manager')->nullable();
            $table->integer('total_boxes')->default(0);
            $table->json('boxes_details')->nullable();
            $table->string('shipping_carrier')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('airway_bill_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
