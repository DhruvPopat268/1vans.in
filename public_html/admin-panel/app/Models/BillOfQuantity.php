<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOfQuantity extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'files',
        'created_by'
    ];


// In Drawings.php
public function project()
{
    return $this->belongsTo(\App\Models\Project::class);
}
}
