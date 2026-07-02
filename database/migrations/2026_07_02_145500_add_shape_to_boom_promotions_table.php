<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('boom_promotions', function (Blueprint $table) {
            $table->string('shape')->default('starburst')->after('image'); // starburst, circle, heart, square
        });
    }

    public function down(): void
    {
        Schema::table('boom_promotions', function (Blueprint $table) {
            $table->dropColumn('shape');
        });
    }
};
