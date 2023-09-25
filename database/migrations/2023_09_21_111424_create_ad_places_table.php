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
        Schema::create('ad_places', function (Blueprint $table) {
            $table->id();
            $table->integer('ad_type_id')->unsigned();
            $table->string('title'); 
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('ad_type_id')->references('id')->on('ad_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_places');
    }
};
