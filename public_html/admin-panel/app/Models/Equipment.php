<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'project_id',
        'rate',
        'created_by',
    ];
    
        public function equipmentFormItems()
{
    return $this->hasMany(EquipmentFormItem::class);
}

public function user()
{
    return $this->belongsTo(User::class, 'created_by');
}

}
