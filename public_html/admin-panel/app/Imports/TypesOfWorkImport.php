<?php

namespace App\Imports;

use App\Models\DailyReportMainCategory;
use App\Models\Project;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Auth;

class TypesOfWorkImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
   public $skippedRows = [];

    public function collection(Collection $rows)
    {
        // Skip header row
        $rows->skip(1)->each(function ($row, $index) {
            $project_name = trim($row[0] ?? '');
            $name         = trim($row[1] ?? '');
            

            if ($name && $project_name) {
                $project = Project::where('project_name', $project_name)->first();

                // ✅ Check project belongs to logged-in user
                if ($project && $project->id == Auth::user()->project_assign_id) {
                    
                    // ✅ Check duplicate by project_id + name
                    $exists = DailyReportMainCategory::where('project_id', $project->id)
                                ->where('name', $name)
                                ->exists();

                    if (!$exists) {
                        DailyReportMainCategory::create([
                            'name'       => $name,
                            'project_id' => $project->id,
                            'created_by' => Auth::id(),
                        ]);
                    } else {
                        $this->skippedRows[] = [
                            'row'     => $index + 1,
                            'name'    => $name,
                            'project' => $project_name,
                            'reason'  => 'Duplicate entry for this project'
                        ];
                    }
                } else {
                    $this->skippedRows[] = [
                        'row'     => $index + 1,
                        'name'    => $name,
                        'project' => $project_name,
                        'reason'  => 'Project not found or not assigned to you'
                    ];
                }
            }
        });
    }
}
