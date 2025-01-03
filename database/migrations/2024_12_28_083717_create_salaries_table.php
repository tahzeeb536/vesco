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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            $table->unsignedInteger('total_present_days')->default(0);
            $table->unsignedInteger('total_hours')->nullable()->default(0);
            $table->unsignedInteger('total_minutes')->nullable()->default(0);
            $table->unsignedInteger('total_overtime_hours')->nullable()->default(0);
            $table->unsignedInteger('total_overtime_minutes')->nullable()->default(0);
            $table->unsignedInteger('deduction')->default(0);
            $table->unsignedInteger('net_salary');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
