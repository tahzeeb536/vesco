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
        Schema::create('courier_receipts', function (Blueprint $table) {
            $table->id();

            $table->date('date');
            $table->string('airway_bill_number')->nullable();
            $table->string('destination_code');
            $table->string('origin_code')->nullable();

            // Shipper Information
            $table->string('shipper_account_number');
            $table->string('shipper_credit_card')->nullable();
            $table->string('shipper_name')->nullable();
            $table->string('shipper_address')->nullable();
            $table->string('shipper_city')->nullable();
            $table->string('shipper_zip')->nullable();
            $table->string('shipper_country')->nullable();
            $table->string('shipper_phone')->nullable();
            $table->string('shipper_department')->nullable();

             // Receiver Information
             $table->string('receiver_company_name')->nullable();
             $table->string('receiver_attention_to')->nullable();
             $table->string('receiver_address')->nullable();
             $table->string('receiver_city')->nullable();
             $table->string('receiver_state')->nullable();
             $table->string('receiver_country')->nullable();
             $table->string('receiver_zip')->nullable();
             $table->string('receiver_phone')->nullable();
 
             // Other Information
             $table->string('items')->nullable();
             $table->decimal('kilos', 8, 2)->nullable();
             $table->string('type')->nullable();
             $table->text('extra_information')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courier_receipts');
    }
};
