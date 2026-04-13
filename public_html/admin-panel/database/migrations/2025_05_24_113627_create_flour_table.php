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
        Schema::create('flour', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('wing_id');
            $table->string('created_by')->nullable(); // if image can be optional
            $table->timestamps();

            // Optional: add foreign key constraint if you have a projects table
            $table->foreign('wing_id')->references('id')->on('wings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flour');
    }
};
