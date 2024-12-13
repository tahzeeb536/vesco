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
        Schema::create('packaging_lists', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('e_form_no')->nullable();
            $table->string('invoice_no')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->string('port_of_landing')->nullable();
            $table->string('port_of_discharge')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packaging_lists');
    }
};
