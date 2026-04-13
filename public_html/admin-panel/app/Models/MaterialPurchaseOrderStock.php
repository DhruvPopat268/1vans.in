<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialPurchaseOrderStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_purchase_orders_id',
        'sub_category_id',
        'stock',
    ];

    public function subCategory()
{
    return $this->belongsTo(\App\Models\MaterialSubCategory::class, 'sub_category_id');
}
}
