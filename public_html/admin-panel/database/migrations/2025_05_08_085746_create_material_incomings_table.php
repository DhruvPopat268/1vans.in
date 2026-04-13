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
        Schema::create('material_incomings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('category_id');
            $table->string('location');
            $table->date('date');
            $table->string('challan_number');
            $table->string('bill_number');
            $table->string('vehicle_number');
            $table->string('vendor_name');
            $table->text('description')->nullable();
            $table->text('remark')->nullable();
            $table->string('signature')->nullable(); // path to signature
            $table->string('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_incomings');
    }
};
