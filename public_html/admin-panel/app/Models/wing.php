<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wing extends Model
{
    use HasFactory;

     protected $fillable = [
        'name',
        'project_id',
        'created_by',
    ];

    public function project()
{
    return $this->belongsTo(\App\Models\Project::class);
}
public function flours()
{
    return $this->hasMany(\App\Models\Flour::class, 'wing_id');
}
}
