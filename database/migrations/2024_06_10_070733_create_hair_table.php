<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hairs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('color');
            $table->integer('length');
            $table->longText('description');
            $table->json('details')->default(new Expression('(JSON_ARRAY())'));
            $table->boolean('available')->default(true);
            $table->integer('price');
            $table->integer('discount')->default(0);
            $table->json('images')->default(new Expression('(JSON_ARRAY())'));
            $table->softDeletes('deleted_at', precision: 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hair');
    }
};
