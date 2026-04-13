<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkIssue extends Model
{
    use HasFactory;
    protected $fillable = [
        'location',
        'description',
        'project_id',
        'status',
        'issue',
        'created_by',
        'date',
        'video_path',
        'name_of_work'
    ];

    public function user()
{
    return $this->belongsTo(\App\Models\User::class, 'created_by');
}

public function workissueImage()
{
    return $this->hasMany(\App\Models\WorkIssueImages::class, 'work_issues_id');
}
public function project()
{
    return $this->belongsTo(Project::class, 'project_id');
}
}
