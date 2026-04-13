<?php

namespace App\Imports;

use App\Models\MesurementAttribute;
use App\Models\MesurementSubAttribute;
use App\Models\Project;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Auth;

class TypesOfMeasurementsImport implements ToCollection
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
        $rows->skip(1)->each(function ($row, $index) use ($user, $userProjectId) {
            $rowNumber = $index + 1;

            $projectName  = trim($row[0]); // Project Name
            $attributeName = trim($row[1]); // Attribute Name
            $subAttrNames  = trim($row[2]); // Sub Attributes, comma separated

            if (empty($projectName) || empty($attributeName)) {
                $this->skippedRows[] = [
                    'row' => $rowNumber,
                    'name' => $attributeName ?: 'N/A',
                    'project' => $projectName ?: 'N/A',
                    'reason' => 'Project or Types Of Measurements name is empty'
                ];
                return;
            }

            // Check project matches user
            $project = Project::where('id', $userProjectId)
                ->where('project_name', $projectName)
                ->first();

            if (!$project) {
                $this->skippedRows[] = [
                    'row' => $rowNumber,
                    'name' => $attributeName,
                    'project' => $projectName,
                    'reason' => 'Project does not match assigned project'
                ];
                return;
            }

            // Create Attribute if not exists
            $attribute = MesurementAttribute::firstOrCreate(
                ['project_id' => $userProjectId, 'name' => $attributeName],
                ['created_by' => $user->id]
            );

            // Handle Sub Attributes
            if (!empty($subAttrNames)) {
                $subAttrArray = array_map('trim', explode(',', $subAttrNames));
                foreach ($subAttrArray as $subName) {
                    if (empty($subName)) continue;

                    $exists = MesurementSubAttribute::where('attribute_id', $attribute->id)
                        ->where('name', $subName)
                        ->exists();

                    if (!$exists) {
                        MesurementSubAttribute::create([
                            'attribute_id' => $attribute->id,
                            'name' => $subName
                        ]);
                    } else {
                        $this->skippedRows[] = [
                            'row' => $rowNumber,
                            'name' => $subName,
                            'project' => $projectName,
                            'reason' => "Unit '{$subName}' already exists under this Types Of Measurement"
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
