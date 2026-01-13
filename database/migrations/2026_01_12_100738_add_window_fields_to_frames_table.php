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
            // 🔥 Photo window (auto-detected from PNG)
            $table->integer('window_x')->nullable()->after('frame_texture');
            $table->integer('window_y')->nullable()->after('window_x');
            $table->integer('window_width')->nullable()->after('window_y');
            $table->integer('window_height')->nullable()->after('window_width');

            // for future (circle / oval / custom)
            $table->string('window_shape')->default('rect')->after('window_height');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('frames', function (Blueprint $table) {
             $table->dropColumn([
                'window_x',
                'window_y',
                'window_width',
                'window_height',
                'window_shape',
            ]);
        });
    }
};
