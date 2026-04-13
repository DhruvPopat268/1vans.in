<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialIncoming extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'challan_number',
        'bill_number',
        'vehicle_number',
        'description',
        'remark',
        'signature',
        'location',
        'vendor_name',
        'date',
        'created_by',
        'category_id',
        'gst_number',
        'batch_number',
        'eway_bill_no',
        'eway_bill_file',
        'royalty_slip_no',
        'royalty_slip_file',
                'issue_status',
        'comment'
    ];

    public function stocks()
{
    return $this->hasMany(\App\Models\MaterialIncomingStock::class, 'material_incomings_id');
}

public function user()
{
    return $this->belongsTo(\App\Models\User::class, 'created_by');
}
public function materialIncomingImage()
{
    return $this->hasMany(\App\Models\MaterialIncomingImages::class, 'material_incomings_id');
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
