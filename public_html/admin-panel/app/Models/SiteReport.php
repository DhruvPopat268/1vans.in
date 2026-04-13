<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteReport extends Model
{
    use HasFactory;

        protected $fillable = [
        'name_of_work',
        'project_id',
        'work_description',
        'work_address',
        'date',
        'created_by'
    ];

    public function attachments()
{
    return $this->hasMany(\App\Models\SiteReportAttachments::class, 'site_reports_id');
}
// In Drawings.php
public function project()
{
    return $this->belongsTo(\App\Models\Project::class);
}
public function user()
{
    return $this->belongsTo(User::class, 'created_by');
}

}
