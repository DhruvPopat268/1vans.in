<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReportManPower extends Model
{
    use HasFactory;
    protected $fillable = [
        'daily_reports_id',
        'man_powers_id',
        'total_person',
        'created_by',
    ];

    public function manPower()
{
    return $this->belongsTo(\App\Models\ManPower::class, 'man_powers_id');
}

}
