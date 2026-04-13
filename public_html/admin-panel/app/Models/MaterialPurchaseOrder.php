<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialPurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'category_id',
        'location',
        'date',
        'vendor_name',
        'description',
        'status',
        'created_by',
        'signature'


    ];

    public function stocks()
{
    return $this->hasMany(\App\Models\MaterialPurchaseOrderStock::class, 'material_purchase_orders_id');
}

public function user()
{
    return $this->belongsTo(\App\Models\User::class, 'created_by');
}

public function category()
{
    return $this->belongsTo(\App\Models\MaterialCategory::class, 'category_id');
}

public function project()
{
    return $this->belongsTo(Project::class, 'project_id');
}
}
