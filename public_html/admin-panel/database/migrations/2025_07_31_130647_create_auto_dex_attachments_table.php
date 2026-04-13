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
        Schema::create('auto_dex_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auto_dexes_id');
            $table->string('files');
                        $table->string('created_by')->nullable();
            $table->timestamps();

                $table->foreign('auto_dexes_id')->references('id')->on('auto_dexes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_dex_attachments');
    }
};
