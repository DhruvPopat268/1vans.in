<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'project_id',
        'image',
    ];

    public function attachments()
    {
        return $this->hasMany(\App\Models\ProjectDocumentAttachments::class, 'project_document_id');
    }
// In Drawings.php
public function project()
{
    return $this->belongsTo(\App\Models\Project::class);
}
}
