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
        Schema::create('project_document_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_document_id');
            $table->string('files');
            $table->timestamps();

            $table->foreign('project_document_id')->references('id')->on('project_documents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_document_attachments');
    }
};
