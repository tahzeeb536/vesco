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
        Schema::table('advance_salary_deductions', function (Blueprint $table) {
            $table->integer('year')->after('return_date')->nullable();
            $table->integer('month')->after('year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advance_salary_deductions', function (Blueprint $table) {
            $table->dropColumn('year');
            $table->dropColumn('month');
        });
    }
};
