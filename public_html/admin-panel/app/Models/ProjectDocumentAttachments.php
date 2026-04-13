<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDocumentAttachments extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_document_id',
        'files'
    ];

    public function drawing()
{
    return $this->belongsTo(\App\Models\ProjectDocument::class, 'project_document_id');
}

}
