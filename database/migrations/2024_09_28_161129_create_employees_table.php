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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('name_urdu', 60)->nullable();
            $table->string('father_name', 60)->nullable();
            $table->date('dob')->nullable();
            $table->string('cnic', 20)->nullable();
            $table->string('photo')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address')->nullable();
            $table->enum('type', ['Salary Based'])->nullable();
            $table->double('basic_salary', 10, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
