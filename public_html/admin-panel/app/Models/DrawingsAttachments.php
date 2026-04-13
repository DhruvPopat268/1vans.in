<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrawingsAttachments extends Model
{
    use HasFactory;
    protected $fillable = [
        'drawing_id',
        'files'
    ];

    public function drawing()
{
    return $this->belongsTo(Drawings::class, 'drawing_id');
}




}
