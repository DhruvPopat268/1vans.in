<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitCategory extends Model
{
    use HasFactory;
     protected $fillable = [
        'name',
        'project_id',
        'created_by',
    ];


// In Drawings.php
public function project()
{
    return $this->belongsTo(\App\Models\Project::class);
}
public function subcategories()
{
    return $this->hasMany(\App\Models\UnitSubCategory::class, 'category_id');
}
}
