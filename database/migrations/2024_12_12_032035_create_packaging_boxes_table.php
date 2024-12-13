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
        Schema::create('packaging_boxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('packaging_list_id')->constrained('packaging_lists')->onDelete('cascade');
            $table->integer('cartons')->nullable();
            $table->integer('qty_cartons')->nullable();
            $table->string('article_no')->nullable();
            $table->string('details')->nullable();
            $table->string('size_qty')->nullable();
            $table->integer('total_qty')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packaging_boxes');
    }
};
