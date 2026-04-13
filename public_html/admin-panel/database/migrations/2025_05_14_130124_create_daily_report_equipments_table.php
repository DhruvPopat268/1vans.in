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
        Schema::create('daily_report_equipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_reports_id');
            $table->unsignedBigInteger('equipment_id');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('total_hours', 8, 2);
            $table->decimal('rate', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('created_by')->nullable();
            $table->timestamps();

             $table->foreign('daily_reports_id')->references('id')->on('daily_reports')->onDelete('cascade');
            $table->foreign('equipment_id')->references('id')->on('equipment')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_report_equipments');
    }
};
