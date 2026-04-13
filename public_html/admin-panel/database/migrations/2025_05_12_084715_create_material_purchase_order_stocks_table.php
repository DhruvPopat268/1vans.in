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
        Schema::create('material_purchase_order_stocks', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('material_purchase_orders_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->integer('stock'); // or float if needed
            $table->timestamps();

$table->foreign('material_purchase_orders_id')
              ->references('id')
              ->on('material_purchase_orders')
              ->onDelete('cascade')
              ->name('mpo_stock_mpo_id_fk'); // Custom short name
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_purchase_order_stocks');
    }
};
