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
        Schema::table('salaries', function (Blueprint $table) {
            $table->unsignedInteger('basic_salary')->default(0)->after('total_overtime_minutes');
            $table->unsignedInteger('loan_deduction')->default(0)->after('deduction');
            $table->unsignedInteger('temp_deduction')->default(0)->after('loan_deduction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            $table->dropColumn('basic_salary');
            $table->dropColumn('loan_deduction');
            $table->dropColumn('temp_deduction');
        });
    }
};
