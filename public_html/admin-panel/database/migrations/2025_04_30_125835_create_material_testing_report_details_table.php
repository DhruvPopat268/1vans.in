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
        Schema::create('material_testing_report_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_testing_reports_id');
            $table->string('remark');
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('material_testing_reports_id', 'mtr_details_report_id_fk')
                  ->references('id')->on('material_testing_reports')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_testing_report_details');
    }
};
