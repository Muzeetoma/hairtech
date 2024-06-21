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
        Schema::table('hair_items', function (Blueprint $table) {
            $table->boolean('available')->default(true);
            $table->integer('price');
            $table->integer('discount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hair_items', function (Blueprint $table) {
            $table->dropColumn('available');
            $table->dropColumn('price');
            $table->dropColumn('discount');
        });
    }
};
