<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDoEngineer extends Model
{
    use HasFactory;
     protected $fillable = [
        'project_id',
        'name',
        'engineer_id',
        'created_by',
        'created_user'

    ];

    public function engineer()
{
    return $this->belongsTo(\App\Models\User::class, 'engineer_id');
}
public function tasks()
{
    return $this->hasMany(\App\Models\ToDoEngineerTask::class, 'to_do_engineer_id');
}

}
