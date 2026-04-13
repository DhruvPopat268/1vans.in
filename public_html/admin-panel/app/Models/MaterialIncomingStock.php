<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialIncomingStock extends Model
{
    use HasFactory;
    protected $fillable = [
        'material_incomings_id',
        'sub_category_id',
        'stock',
    ];

    public function subCategory()
{
    return $this->belongsTo(\App\Models\MaterialSubCategory::class, 'sub_category_id');
}
}
