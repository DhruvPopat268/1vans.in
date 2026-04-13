<?php

namespace App\Imports;

use App\Models\wing;
use App\Models\Flour;
use App\Models\Project;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Auth;

class WorkingAreaImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public $skippedRows = [];

      public function collection(Collection $rows)
    {
        $user = Auth::user();
        $userProjectId = $user->project_assign_id;

        // Skip header row
        $rows->skip(1)->each(function ($row, $index) use ($userProjectId, $user) {
            $rowNumber = $index + 1;

            $projectName = trim($row[0]);  // Project Name column
            $wingName    = trim($row[1]);  // Wing Name column
            $floorNames  = trim($row[2]);  // Floors column (comma separated)

            if (empty($projectName) || empty($wingName)) {
                $this->skippedRows[] = [
                    'row' => $rowNumber,
                    'name' => $wingName,
                    'project' => $projectName,
                    'reason' => 'Project or Wing name is empty'
                ];
                return;
            }

            // Check project matches the assigned project
            $project = Project::where('id', $userProjectId)
                ->where('project_name', $projectName)
                ->first();

            if (!$project) {
                $this->skippedRows[] = [
                    'row' => $rowNumber,
                    'name' => $wingName,
                    'project' => $projectName,
                    'reason' => 'Project does not match assigned project'
                ];
                return;
            }

            // Wing
            $wing = wing::firstOrCreate(
                ['project_id' => $userProjectId, 'name' => $wingName],
                ['created_by' => $user->id]
            );

            // Floors
            if (!empty($floorNames)) {
                $floorArray = array_map('trim', explode(',', $floorNames));
                foreach ($floorArray as $floorName) {
                    if (empty($floorName)) continue;

                    $exists = Flour::where('wing_id', $wing->id)
                        ->where('name', $floorName)
                        ->exists();

                    if (!$exists) {
                        Flour::create([
                            'wing_id' => $wing->id,
                            'name' => $floorName,
                            'created_by' => $user->id,
                        ]);
                    } else {
                        $this->skippedRows[] = [
                            'row' => $rowNumber,
                            'name' => $wingName,
                            'project' => $projectName,
                            'reason' => "Unit '{$floorName}' already exists under this Wing"
                        ];
                    }
                }
            }
        });
    }

    public function getSkippedRows()
    {
        return $this->skippedRows;
    }
}
