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
        Schema::create('design_templates', function (Blueprint $table) {
             $table->id();

            $table->string('name'); 
            // Example: "Birthday Card", "Double Photo Frame"

            $table->enum('type', ['frame', 'card'])->default('frame');
            // frame = normal photo frame
            // card  = birthday / anniversary / etc.

            $table->string('category')->nullable();
            // birthday, anniversary, collage, love, etc.

            $table->unsignedInteger('canvas_width')->default(400);
            $table->unsignedInteger('canvas_height')->default(400);

            $table->longText('template_json');
            // Fabric canvas JSON (FULL layout)

            $table->string('preview_image')->nullable();
            // Thumbnail path (optional, auto-generated later)

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_templates');
    }
};
