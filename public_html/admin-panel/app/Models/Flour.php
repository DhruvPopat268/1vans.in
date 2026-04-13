<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flour extends Model
{
    use HasFactory;
     protected $fillable = [
        'name',
        'wing_id',
        'created_by',
    ];

    public function wing()
{
    return $this->belongsTo(\App\Models\wing::class, 'wing_id');
}
}
