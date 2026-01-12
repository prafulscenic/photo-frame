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
            $table->unsignedInteger('frame_width')->nullable()->after('shape');
            $table->unsignedInteger('frame_height')->nullable()->after('frame_width');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('frames', function (Blueprint $table) {
             $table->dropColumn(['frame_width', 'frame_height']);
        });
    }
};
