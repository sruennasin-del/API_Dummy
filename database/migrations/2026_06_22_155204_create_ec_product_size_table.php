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
        Schema::create('ec_product_size', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('ec_products')->cascadeOnDelete();
            $table->foreignId('size_id')->constrained('ec_sizes')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ec_product_size');
    }
};
