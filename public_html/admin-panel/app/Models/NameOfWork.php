<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NameOfWork extends Model
{
    use HasFactory;
    protected $fillable = [
       'name',
       'unit_category_id',
       'project_id',
       'created_by',
       'mesurement_attribute_id',
       'mesurement_sub_attribute_id',
         'daily_report_main_category_id',
       'total_mesurement',
       'start_date',
       'end_date'
    ];
    
    public function unitCategory()
{
    return $this->belongsTo(\App\Models\UnitCategory::class, 'unit_category_id');
}

public function mesurementattribute()
{
    return $this->belongsTo(MesurementAttribute::class, 'mesurement_attribute_id');
}

public function mesurementsubAttribute()
{
    return $this->belongsTo(MesurementSubAttribute::class, 'mesurement_sub_attribute_id');
}

 public function mainCategory()
{
    return $this->belongsTo(\App\Models\DailyReportMainCategory::class, 'daily_report_main_category_id');
}

}
