<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentForm extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'location',
        'description',
        'signature',
        'created_by',
    ];

    public function items()
    {
        return $this->hasMany(EquipmentFormItem::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    

}
