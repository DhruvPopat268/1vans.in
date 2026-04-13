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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('name_of_work_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->string('for'); // context/purpose
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->string('signature')->nullable(); // file name/path for signature
            $table->text('comment')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();

             $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('name_of_work_id')->references('id')->on('name_of_works')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('unit_categories')->onDelete('cascade');
            $table->foreign('sub_category_id')->references('id')->on('unit_sub_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
