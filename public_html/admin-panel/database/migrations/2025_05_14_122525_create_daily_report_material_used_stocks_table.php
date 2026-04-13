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
        Schema::create('daily_report_material_used_stocks', function (Blueprint $table) {
            $table->id();
           $table->unsignedBigInteger('daily_reports_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->text('used_stock')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();

             $table->foreign('daily_reports_id')->references('id')->on('daily_reports')->onDelete('cascade');
            $table->foreign('sub_category_id')->references('id')->on('material_sub_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_report_material_used_stocks');
    }
};
