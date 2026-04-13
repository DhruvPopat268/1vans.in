<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialTestingReportDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_testing_reports_id',
        'remark',
        'file'
    ];



public function materialTestingReport()
{
    return $this->belongsTo(MaterialTestingReports::class);
}

}
