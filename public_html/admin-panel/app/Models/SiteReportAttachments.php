<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteReportAttachments extends Model
{
    use HasFactory;

     protected $fillable = [
        'site_reports_id',
        'files'
    ];

    public function sitereport()
{
    return $this->belongsTo(SiteReport::class, 'site_reports_id');
}

}
