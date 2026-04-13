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
        Schema::create('material_testing_reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('project_id');
            $table->string('image')->nullable(); // if image can be optional
            $table->timestamps();

            // Optional: add foreign key constraint if you have a projects table
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_testing_reports');
    }
};
