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
        Schema::table('frames', function (Blueprint $table) {
        // Add unsignedBigInt column and set as foreign key
            $table->foreignId('frame_texture_id')
                  ->nullable() // if you want it optional
                  ->constrained('frame_textures')
                  ->nullOnDelete(); // set to NULL if texture is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('frames', function (Blueprint $table) {
              $table->dropForeign(['frame_texture_id']);
            $table->dropColumn('frame_texture_id');
        });
    }
};


