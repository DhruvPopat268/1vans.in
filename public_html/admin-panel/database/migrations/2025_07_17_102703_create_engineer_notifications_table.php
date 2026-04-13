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
        Schema::create('engineer_notifications', function (Blueprint $table) {
              $table->id();
            $table->unsignedBigInteger('engineer_id');
        $table->string('title');
        $table->text('message');
        $table->integer('key')->default(0);
        $table->timestamps();

        // Optional: foreign key constraint if engineers are in users table
        $table->foreign('engineer_id')->references('id')->on('users')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engineer_notifications');
    }
};
