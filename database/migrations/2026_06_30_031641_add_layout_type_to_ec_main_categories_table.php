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
        Schema::table('ec_main_categories', function (Blueprint $table) {
            $table->enum('layout_type', ['portrait', 'landscape'])->default('portrait')->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ec_main_categories', function (Blueprint $table) {
            $table->dropColumn('layout_type');
        });
    }
};
