<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReportMaterialUsedStock extends Model
{
    use HasFactory;
    protected $fillable = [
        'daily_reports_id',
        'sub_category_id',
        'used_stock',
        'created_by',
    ];

    public function subCategory()
{
    return $this->belongsTo(\App\Models\MaterialSubCategory::class, 'sub_category_id');
}

}
