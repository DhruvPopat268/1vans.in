<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentFormItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'equipment_form_id',
        'equipment_id',
        'start_time',
        'end_time',
        'rate',
        'total_hours',
        'total_amount',
        'date'
    ];

    public function form()
    {
        return $this->belongsTo(EquipmentForm::class, 'equipment_form_id');
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
