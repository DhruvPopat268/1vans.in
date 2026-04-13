<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReportMesurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_reports_id',
        'mesurement_attributes_id',
        'mesurements_value',
        'created_by',
    ];
    
        public function attribute()
{
    return $this->belongsTo(\App\Models\MesurementAttribute::class, 'mesurement_attributes_id');
}
}
