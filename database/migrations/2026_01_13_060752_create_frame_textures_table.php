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
        Schema::create('frame_textures', function (Blueprint $table) {
           $table->id();

            $table->foreignId('frame_id')
                  ->constrained('frames')
                  ->cascadeOnDelete();

            $table->string('name')->nullable(); 
            $table->string('texture_path'); // storage path

            $table->string('material_type')
                  ->default('wood'); // wood, metal, glass

            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frame_textures');
    }
};
