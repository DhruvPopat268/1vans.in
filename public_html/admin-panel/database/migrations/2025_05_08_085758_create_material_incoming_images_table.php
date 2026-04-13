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
        Schema::create('material_incoming_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_incomings_id');
            $table->string('image_path');
            $table->timestamps();

            $table->foreign('material_incomings_id')->references('id')->on('material_incomings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_incoming_images');
    }
};
