<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoDex extends Model
{
    use HasFactory;
    protected $fillable = [
       'name',
       'project_id',
       'created_by',
       'created_user'
    ];

     public function attachments()
{
    return $this->hasMany(\App\Models\AutoDexAttachments::class, 'auto_dexes_id');
}
// In Drawings.php
public function project()
{
    return $this->belongsTo(\App\Models\Project::class);
}
}
