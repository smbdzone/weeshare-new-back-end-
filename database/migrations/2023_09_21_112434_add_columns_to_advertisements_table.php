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

        Schema::table('advertisements', function (Blueprint $table) {
            $table->Integer('ad_type_id');
            $table->Integer('ad_place_id');

            $table->foreign('ad_type_id')->references('id')->on('ad_types')->onDelete('cascade');
            $table->foreign('ad_place_id')->references('id')->on('ad_places')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->Integer('ad_type_id');
            $table->Integer('ad_place_id');
        });
    }
};
