<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReportEquipments extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_reports_id',
        'equipment_id',
        'start_time',
        'end_time',
        'total_hours',
        'rate',
        'total_amount',
        'created_by',
        'date'
    ];

    public function equipment()
{
    return $this->belongsTo(\App\Models\Equipment::class, 'equipment_id');
}

}
