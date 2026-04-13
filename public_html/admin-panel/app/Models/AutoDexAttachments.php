<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoDexAttachments extends Model
{
    use HasFactory;

    protected $fillable = [
       'auto_dexes_id',
       'files',
       'created_by',
       'view_url',
       'created_user'
    ];

     public function autodex()
{
    return $this->belongsTo(AutoDex::class, 'auto_dexes_id');
}
}
