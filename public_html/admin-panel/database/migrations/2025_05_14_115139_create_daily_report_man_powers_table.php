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
        Schema::create('daily_report_man_powers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_reports_id');
            $table->unsignedBigInteger('man_powers_id');
            $table->text('total_person')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();

             $table->foreign('daily_reports_id')->references('id')->on('daily_reports')->onDelete('cascade');
            $table->foreign('man_powers_id')->references('id')->on('man_powers')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_report_man_powers');
    }
};
