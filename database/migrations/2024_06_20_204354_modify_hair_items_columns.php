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
            $table->string('type')->change();
            $table->string('dimensions')->change();
            $table->string('quality')->change();
            $table->string('adhesion')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hair_items', function (Blueprint $table) {
            $table->enum('type', ['frontal', 'closure'])->change();
            $table->enum('dimensions', ['13x6', '13x4', '2x4', '5x5', '6x6', '7x7'])->change();
            $table->enum('quality', ['HD', 'transparent'])->change();
            $table->enum('adhesion', ['glueless', 'non-glueless'])->change();
        });
    }
};
