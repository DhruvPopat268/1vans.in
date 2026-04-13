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
        Schema::create('daily_report_mesurements', function (Blueprint $table) {
            $table->id();
           $table->unsignedBigInteger('daily_reports_id');
           $table->unsignedBigInteger('mesurement_attributes_id');
            $table->text('mesurements_value')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();

             $table->foreign('daily_reports_id')->references('id')->on('daily_reports')->onDelete('cascade');
                          $table->foreign('mesurement_attributes_id')->references('id')->on('mesurement_attributes')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_report_mesurements');
    }
};
