<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MesurementAttribute extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'project_id',
        'created_by'
    ];
    
       public function subattribute()
{
    return $this->hasMany(\App\Models\MesurementSubAttribute::class, 'attribute_id');
}

}
