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
        Schema::create('name_of_works', function (Blueprint $table) {
            $table->id();
           $table->unsignedBigInteger('project_id');
        $table->unsignedBigInteger('unit_category_id');
        $table->string('name');
        $table->string('created_by');
        $table->timestamps();

        // Foreign keys
        $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        $table->foreign('unit_category_id')->references('id')->on('unit_categories')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('name_of_works');
    }
};
