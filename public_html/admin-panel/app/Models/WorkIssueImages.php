<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkIssueImages extends Model
{
    use HasFactory;
    protected $fillable = [
        'work_issues_id',
        'image_path',

    ];
}
