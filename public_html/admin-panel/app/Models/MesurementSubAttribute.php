<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MesurementSubAttribute extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'attribute_id',
    ];

}
