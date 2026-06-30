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
        // 1. Add Price to ec_product_color
        Schema::table('ec_product_color', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->nullable()->after('color_id');
        });

        // 2. Change ec_product_size to link to ec_product_color instead of ec_products
        Schema::table('ec_product_size', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
            $table->foreignId('product_color_id')->after('id')->constrained('ec_product_color')->cascadeOnDelete();
        });

        // 3. Change ec_product_images to link to ec_product_color instead of ec_products
        Schema::table('ec_product_images', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
            $table->foreignId('product_color_id')->after('id')->constrained('ec_product_color')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ec_product_images', function (Blueprint $table) {
            $table->dropForeign(['product_color_id']);
            $table->dropColumn('product_color_id');
            $table->foreignId('product_id')->after('id')->constrained('ec_products')->cascadeOnDelete();
        });

        Schema::table('ec_product_size', function (Blueprint $table) {
            $table->dropForeign(['product_color_id']);
            $table->dropColumn('product_color_id');
            $table->foreignId('product_id')->after('id')->constrained('ec_products')->cascadeOnDelete();
        });

        Schema::table('ec_product_color', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
