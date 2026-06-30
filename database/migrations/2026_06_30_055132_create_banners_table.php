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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('tag')->nullable();           // e.g. "🔥 Limited Offer"
            $table->string('title');                     // e.g. "Summer Sale"
            $table->string('subtitle')->nullable();      // e.g. "Up to 50% Off"
            $table->text('description')->nullable();
            $table->string('btn_primary_label')->nullable();
            $table->string('btn_primary_url')->nullable();
            $table->string('btn_secondary_label')->nullable();
            $table->string('btn_secondary_url')->nullable();
            $table->string('image')->nullable();         // uploaded image
            $table->string('bg_gradient')->nullable();   // CSS gradient string
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
