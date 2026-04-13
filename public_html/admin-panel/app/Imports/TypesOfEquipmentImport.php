<?php

namespace App\Imports;

use App\Models\Equipment;
use App\Models\Project;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Auth;

class TypesOfEquipmentImport implements ToCollection
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
            $rate         = trim($row[2] ?? 0); // New rate column

            if ($name && $project_name) {
                $project = Project::where('project_name', $project_name)->first();

                // Check project belongs to logged-in user
                if ($project && $project->id == Auth::user()->project_assign_id) {
                    
                    // Check duplicate by project_id + name
                    $exists = Equipment::where('project_id', $project->id)
                                ->where('name', $name)
                                ->exists();

                    if (!$exists) {
                        Equipment::create([
                            'name'       => $name,
                            'project_id' => $project->id,
                            'rate'       => $rate,         // Save rate
                            'created_by' => Auth::id(),
                        ]);
                    } else {
                        $this->skippedRows[] = [
                            'row'     => $index + 1, // +2 to match Excel row number
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
            } else {
                $this->skippedRows[] = [
                    'row'     => $index + 1,
                    'name'    => $name ?: 'N/A',
                    'project' => $project_name ?: 'N/A',
                    'reason'  => 'Project or Equipment name missing'
                ];
            }
        });
    }
}
