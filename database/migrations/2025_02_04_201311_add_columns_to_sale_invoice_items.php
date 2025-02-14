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
        Schema::table('sale_invoice_items', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->unsignedBigInteger('variant_id')->nullable()->change();
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->string('product_name')->nullable()->after('variant_id');
            $table->string('article_number')->nullable()->after('product_name');
            $table->string('size')->nullable()->after('article_number');
            $table->string('color')->nullable()->after('size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_invoice_items', function (Blueprint $table) {
            $table->dropColumn(['product_name', 'article_number', 'size', 'color']);
            $table->dropForeign(['variant_id']);
            $firstVariantId = DB::table('product_variants')->select('id')->first();
            if ($firstVariantId) {
                DB::statement("UPDATE sale_invoice_items SET variant_id = {$firstVariantId->id} WHERE variant_id IS NULL");
            } else {
                throw new \Exception("No product_variants exist. Cannot update NULL variant_id values.");
            }
            $table->unsignedBigInteger('variant_id')->nullable(false)->default(0)->change();
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }
};
