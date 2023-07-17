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
        Schema::create('food_type_restaurant', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('food_type_id')
            ->references('id')
            ->on('food_types')
            ->onDelete('cascade');

            $table->foreignId('restaurant_id')
            ->references('id')
            ->on('restaurants')
            ->onDelete('cascade');

            $table->unique(['food_type_id', 'restaurant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_type_restaurant');
    }
};
