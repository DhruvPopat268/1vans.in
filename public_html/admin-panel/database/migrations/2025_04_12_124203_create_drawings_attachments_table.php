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
        Schema::create('drawings_attachments', function (Blueprint $table) {
            $table->id();
    $table->unsignedBigInteger('drawing_id');
    $table->string('files');
    $table->timestamps();

    $table->foreign('drawing_id')->references('id')->on('drawings')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drawings_attachments');
    }
};
