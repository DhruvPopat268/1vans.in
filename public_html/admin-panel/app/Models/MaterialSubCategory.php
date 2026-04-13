<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialSubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'attribute_id',
        'price',
        'created_by',
        'status'
    ];

    public function attribute()
{
    return $this->belongsTo(\App\Models\Attribute::class, 'attribute_id');
}
public function category()
{
    return $this->belongsTo(\App\Models\MaterialCategory::class, 'category_id');
}


}
