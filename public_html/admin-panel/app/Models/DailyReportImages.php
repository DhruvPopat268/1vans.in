<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReportImages extends Model
{
    use HasFactory;
    protected $fillable = [
        'daily_reports_id',
        'image_path',
    ];
    
        public function dailyReport()
{
    return $this->belongsTo(DailyReport::class, 'daily_reports_id');
}
}
