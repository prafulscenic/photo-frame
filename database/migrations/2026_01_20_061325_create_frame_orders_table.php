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
        Schema::create('frame_orders', function (Blueprint $table) {
              $table->id();

            // User info
            $table->string('name');
            $table->string('email');

            // Frame relation
            $table->foreignId('frame_id')
                  ->constrained('frames')
                  ->cascadeOnDelete();

            // Frame options (universal for all shapes)
            $table->string('frame_size');
            $table->string('frame_thickness');

            // Images
            $table->string('uploaded_image');      
            $table->string('final_frame_image');   

            // Status (future-proof)
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frame_orders');
    }
};
