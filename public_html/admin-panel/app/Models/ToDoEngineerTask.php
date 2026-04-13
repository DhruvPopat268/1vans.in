<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDoEngineerTask extends Model
{
    use HasFactory;
     protected $fillable = [
        'to_do_engineer_id',
        'date',
        'task_title',
        'description',
        'due_date',
        'status',
        'created_user',
        'created_by'

    ];

    public function attachment()
{
    return $this->hasMany(\App\Models\ToDoEngineerTaskFiles::class, 'task_id');
}

public function engineer()
{
    return $this->belongsTo(\App\Models\ToDoEngineer::class, 'to_do_engineer_id');
}



}
