<?php

namespace App\Imports;

use App\Models\Project;
use App\Models\UnitCategory;
use App\Models\MesurementAttribute;
use App\Models\MesurementSubAttribute;
use App\Models\DailyReportMainCategory;
use App\Models\NameOfWork;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;

class NameOfWorksImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    protected $skippedRows = [];

    public function collection(Collection $rows)
    {
        $user = Auth::user();
        $projectId = $user->project_assign_id;

        // Skip header row
        foreach ($rows->skip(1) as $index => $row) {
            $rowNumber = $index + 1; // (account for header)

            $projectName = trim($row[0] ?? ''); 
            $name        = trim($row[1] ?? '');
            $attrName    = trim($row[2] ?? '');
            $subAttrName = trim($row[3] ?? '');
            $mainCatName = trim($row[4] ?? '');
            $totalMeasure= trim($row[5] ?? '');

            // Validate Project
            $project = Project::where('id', $projectId)
                ->where('project_name', $projectName)
                ->first();

            if (!$project) {
                $this->skippedRows[] = [
                    'row' => $rowNumber,
                    'name' => $name,
                    'project' => $projectName,
                    'reason' => 'Project does not match assigned project'
                ];
                continue;
            }

            // Prevent duplicate (same project + name)
            $exists = NameOfWork::where('project_id', $projectId)
                ->where('name', $name)
                ->exists();

            if ($exists) {
                $this->skippedRows[] = [
                    'row' => $rowNumber,
                    'name' => $name,
                    'project' => $projectName,
                    'reason' => 'Duplicate entry for this project'
                ];
                continue;
            }

            // Lookups
            $attrId         = MesurementAttribute::where('project_id', $projectId)->where('name', $attrName)->value('id');
            // $subAttrId      = $attrId 
            //                     ? MesurementSubAttribute::where('attribute_id', $attrId)->where('name', $subAttrName)->value('id')
            //                     : null;
            
            // Lookup Attribute
$attrId = MesurementAttribute::where('project_id', $projectId)
            ->whereRaw('LOWER(name) = ?', [strtolower($attrName)])
            ->value('id');

// Default NULL
$subAttrId = null;

if ($attrId && !empty($subAttrName)) {

    // Split multiple values: "CUBIK FEET, SQUARE METER"
    $subNames = array_map('trim', explode(',', $subAttrName));

    foreach ($subNames as $subName) {

        $clean = strtolower(preg_replace('/\s+/', ' ', $subName));

        // Fetch matching record
        $subAttr = MesurementSubAttribute::where('attribute_id', $attrId)
            ->get()
            ->first(function ($item) use ($clean) {
                return strtolower(preg_replace('/\s+/', ' ', trim($item->name))) === $clean;
            });

        // If matched any sub-attribute → use the first one
        if ($subAttr) {
            $subAttrId = $subAttr->id;
            break;
        }
    }
}

            $mainCatId      = DailyReportMainCategory::where('project_id', $projectId)->where('name', $mainCatName)->value('id');

            // Save record
            NameOfWork::create([
                'project_id' => $projectId,
                'name' => $name,
                'mesurement_attribute_id' => $attrId,
                'mesurement_sub_attribute_id' => $subAttrId,
                'daily_report_main_category_id' => $mainCatId,
                'total_mesurement' => $totalMeasure,
                'created_by' => $user->id,
            ]);
        }
    }

    public function getSkippedRows()
    {
        return $this->skippedRows;
    }
}
