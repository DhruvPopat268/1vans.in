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
        Schema::create('to_do_engineer_task_files', function (Blueprint $table) {
             $table->id();
        $table->unsignedBigInteger('task_id');
        $table->string('file_path'); // or `file_name` depending on what you're storing

        $table->foreign('task_id')->references('id')->on('to_do_engineer_tasks')->onDelete('cascade');

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('to_do_engineer_task_files');
    }
};
