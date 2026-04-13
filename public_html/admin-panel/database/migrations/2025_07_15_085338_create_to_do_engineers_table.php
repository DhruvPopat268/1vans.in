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
        Schema::create('to_do_engineers', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('project_id');
        $table->string('name'); // Name field
        $table->unsignedBigInteger('engineer_id'); // Foreign key field
        $table->string('created_by');
        $table->string('created_user');
        $table->foreign('engineer_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('to_do_engineers');
    }
};
