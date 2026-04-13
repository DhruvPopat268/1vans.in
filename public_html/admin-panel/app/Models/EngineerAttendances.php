<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerAttendances extends Model
{
    use HasFactory;

    protected $fillable = [
    'engineer_id',
    'project_id',
    'check_in',
    'check_in_latitude',
    'check_in_longitude',
    'check_out',
    'check_out_latitude',
    'check_out_longitude',
    'attendance_type',
    'duration',
    'late',
    'overtime',
    'date',
    'updated_by'
];

public function project()
{
    return $this->belongsTo(\App\Models\Project::class, 'project_id');
}

}
