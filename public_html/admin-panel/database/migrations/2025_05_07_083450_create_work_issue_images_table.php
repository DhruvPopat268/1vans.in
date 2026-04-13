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
        Schema::create('work_issue_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_issues_id');
            $table->string('image_path');
            $table->timestamps();

            $table->foreign('work_issues_id')->references('id')->on('work_issues')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_issue_images');
    }
};
