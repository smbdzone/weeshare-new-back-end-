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
        Schema::create('ad_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ad_timeslots_id');
            $table->unsignedInteger('ad_places_id');
            $table->decimal('saturday', 8, 2);
            $table->decimal('sunday', 8, 2);
            $table->decimal('tuesday', 8, 2);
            $table->decimal('otherday', 8, 2);
            $table->timestamps();

            $table->foreign('ad_timeslots_id')->references('id')->on('ad_timeslots')->onDelete('cascade');
            $table->foreign('ad_places_id')->references('id')->on('ad_places')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_prices');
    }
};
