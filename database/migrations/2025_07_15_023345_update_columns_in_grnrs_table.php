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
        Schema::table('grnrs', function (Blueprint $table) {
            $table->dropForeign(['grn_id']);
            $table->foreignId('grn_id')->nullable()->change();
            $table->foreign('grn_id')->references('id')->on('grns')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grnrs', function (Blueprint $table) {
            $table->dropForeign(['grn_id']);
            $table->foreignId('grn_id')->nullable(false)->change();
            $table->foreign('grn_id')->references('id')->on('grns')->onDelete('cascade');
        });
    }
};
