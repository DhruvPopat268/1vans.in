<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialIncomingImages extends Model
{
    use HasFactory;
    protected $fillable = [
        'material_incomings_id',
        'image_path',
    ];
}
