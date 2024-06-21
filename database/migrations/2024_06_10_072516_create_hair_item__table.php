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
        Schema::create('hair_items', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['frontal','closure']);
            $table->enum('dimensions',['13x6','13x4','2x4','5x5','6x6','7x7']);
            $table->enum('quality',['HD','transparent']);
            $table->enum('adhesion',['glueless','non-glueless']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hair_items');
    }
};
