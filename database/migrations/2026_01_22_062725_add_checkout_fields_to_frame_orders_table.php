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
        Schema::table('frame_orders', function (Blueprint $table) {
            // price (static for prototype)
            $table->integer('price')
                  ->default(0)
                  ->after('frame_thickness');

            // checkout fields
            $table->string('phone')
                  ->nullable()
                  ->after('email');

            $table->text('address')
                  ->nullable()
                  ->after('phone');   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('frame_orders', function (Blueprint $table) {
            $table->dropColumn([
                'price',
                'phone',
                'address',
            ]);
        });
    }
};
