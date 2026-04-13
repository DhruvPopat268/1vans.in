<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialTestingReports extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'project_id',
        'image',
    ];

// In Drawings.php
public function project()
{
    return $this->belongsTo(\App\Models\Project::class);
}
public function details()
{
    return $this->hasMany(MaterialTestingReportDetails::class);
}

}
