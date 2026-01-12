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
        Schema::create('frames', function (Blueprint $table) {
            $table->id();

            $table->string('name'); // e.g. Gold Frame
            $table->string('shape'); 
            // rectangle, circle, oval, rounded_rect, polygon, svg, etc.

            $table->unsignedInteger('border_width'); // 3,5,6,10
            $table->string('border_color', 20); // hex code

            // for future shapes (rounded rectangle, etc.)
            $table->unsignedInteger('border_radius')->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frames');
    }
};
