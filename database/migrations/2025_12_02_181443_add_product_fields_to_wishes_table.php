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
        Schema::table('wishes', function (Blueprint $table) {
            $table->string('product_name', 500)->nullable()->after('description');
            $table->string('product_sku', 50)->nullable()->after('product_name');
            $table->string('product_image', 1000)->nullable()->after('product_sku');
            $table->decimal('product_weight', 8, 2)->nullable()->after('product_image'); // in pounds
            $table->decimal('product_price', 10, 2)->nullable()->after('product_weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wishes', function (Blueprint $table) {
            $table->dropColumn([
                'product_name',
                'product_sku',
                'product_image',
                'product_weight',
                'product_price',
            ]);
        });
    }
};
