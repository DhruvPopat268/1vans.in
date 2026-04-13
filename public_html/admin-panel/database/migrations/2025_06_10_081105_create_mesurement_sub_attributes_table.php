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
        Schema::create('mesurement_sub_attributes', function (Blueprint $table) {
             $table->id();
            $table->string('name');
            $table->unsignedBigInteger('attribute_id');
            $table->timestamps();

            // Optional: add foreign key constraint if you have a projects table
            $table->foreign('attribute_id')->references('id')->on('mesurement_attributes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesurement_sub_attributes');
    }
};
