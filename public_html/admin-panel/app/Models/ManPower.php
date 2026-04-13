<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManPower extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'name',
        'price',
        'total_person',
        'created_by',
        'status'
    ];
}
