<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'project_id',
        'created_by',
    ];


// In Drawings.php
public function project()
{
    return $this->belongsTo(\App\Models\Project::class);
}
public function subcategories()
{
    return $this->hasMany(\App\Models\MaterialSubCategory::class, 'category_id');
}

public function purchaseOrders()
{
    return $this->hasMany(\App\Models\MaterialPurchaseOrder::class, 'category_id');
}

public function materialIncomings()
{
    return $this->hasMany(\App\Models\MaterialIncoming::class, 'category_id');
}

}
