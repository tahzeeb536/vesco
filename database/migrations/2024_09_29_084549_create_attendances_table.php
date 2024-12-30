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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['Present', 'Leave', 'Absent'])->nullable();
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->unsignedInteger('hours_worked')->nullable()->default(0);
            $table->unsignedInteger('minutes_worked')->nullable()->default(0);
            $table->unsignedInteger('overtime_hours')->nullable()->default(0);
            $table->unsignedInteger('overtime_minutes')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
