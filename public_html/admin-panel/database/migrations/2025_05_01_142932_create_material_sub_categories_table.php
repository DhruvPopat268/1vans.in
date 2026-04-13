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
        Schema::create('material_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('attribute_id');
            $table->string('price')->nullable(); // if image can be optional
            $table->string('created_by')->nullable(); // if image can be optional
            $table->timestamps();

            // Optional: add foreign key constraint if you have a projects table
            $table->foreign('category_id')->references('id')->on('material_categories')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_sub_categories');
    }
};
