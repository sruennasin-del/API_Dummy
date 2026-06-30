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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();                          // e.g. SUMMER20
            $table->string('description')->nullable();
            $table->enum('type', ['percent', 'fixed'])->default('percent'); // % or flat $
            $table->decimal('value', 10, 2);                          // e.g. 20 (%) or 5.00 ($)
            $table->decimal('min_order', 10, 2)->default(0);          // minimum cart total
            $table->decimal('max_discount', 10, 2)->nullable();       // cap for % discounts
            $table->integer('usage_limit')->nullable();               // null = unlimited
            $table->integer('used_count')->default(0);
            $table->date('starts_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
