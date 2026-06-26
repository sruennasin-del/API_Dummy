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
        Schema::create('ec_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('ec_orders')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('ec_products')->nullOnDelete();
            $table->string('product_title');
            $table->string('product_thumbnail')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ec_order_items');
    }
};
