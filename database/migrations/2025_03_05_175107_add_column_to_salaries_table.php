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
            $table->integer('home_allowance')->default(0)->after('net_salary');
            $table->integer('home_allowance')->default(0)->after('net_salary');
            $table->integer('medical_allowance')->default(0)->after('home_allowance');
            $table->integer('mobile_allowance')->default(0)->after('medical_allowance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            $table->dropColumn('home_allowance');
            $table->dropColumn('medical_allowance');
            $table->dropColumn('mobile_allowance');
        });
    }
};
