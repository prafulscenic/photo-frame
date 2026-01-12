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
            $table->unsignedTinyInteger('polygon_sides')
                ->nullable()
                ->after('shape');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('frames', function (Blueprint $table) {
           $table->dropColumn('polygon_sides');
        });
    }
};
