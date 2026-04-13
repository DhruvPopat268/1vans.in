<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;

     protected $fillable = [
        'project_id',
        'name_of_work_id',
        'category_id',
        'sub_category_id',
        'for',
        'location',
        'description',
        'signature',
        'comment',
        'created_by',
        'date',
        'at',
        'video_path',
        'latitude',
        'longitude',
        'weather',
        'daily_report_main_category_id'
    ];

    // DailyReport.php

public function nameOfWork()
{
    return $this->belongsTo(\App\Models\NameOfWork::class, 'name_of_work_id');
}

public function manpowers()
{
    return $this->hasMany(\App\Models\DailyReportManPower::class, 'daily_reports_id');
}

public function materials()
{
    return $this->hasMany(\App\Models\DailyReportMaterialUsedStock::class, 'daily_reports_id');
}

public function equipments()
{
    return $this->hasMany(\App\Models\DailyReportEquipments::class, 'daily_reports_id');
}

public function subCategory() {
    return $this->belongsTo(\App\Models\UnitSubCategory::class, 'sub_category_id');
}

public function UnitCategory() {
    return $this->belongsTo(\App\Models\UnitCategory::class, 'category_id');
}

public function flour() {
    return $this->belongsTo(\App\Models\Flour::class, 'flour_id');
}

public function wing() {
    return $this->belongsTo(\App\Models\wing::class, 'wing_id');
}

public function measurements()
{
    return $this->hasMany(\App\Models\DailyReportMesurement::class, 'daily_reports_id');
}

public function dailyReportImage()
{
    return $this->hasMany(\App\Models\DailyReportImages::class, 'daily_reports_id');
}

public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

public function project()
{
    return $this->belongsTo(Project::class, 'project_id');
}

public function mainCategory() {
    return $this->belongsTo(\App\Models\DailyReportMainCategory::class, 'daily_report_main_category_id');
}

}
