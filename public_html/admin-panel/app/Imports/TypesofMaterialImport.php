<?php

namespace App\Imports;

use App\Models\MaterialCategory;
use App\Models\MaterialSubCategory;
use App\Models\Project;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Auth;

class TypesofMaterialImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
public function collection(Collection $rows)
{
    $user = Auth::user();
    $userProjectId = $user->project_assign_id;

    // Skip header row
    $rows->skip(1)->each(function ($row, $index) use ($user, $userProjectId) {
        $rowNumber    = $index + 1; // Excel row number
        $projectName  = trim($row[0]); // Project Name
        $categoryName = trim($row[1]); // Category Name

        // ✅ Check required values
        if (empty($projectName) || empty($categoryName)) {
            $this->skippedRows[] = [
                'row'     => $rowNumber,
                'name'    => $categoryName ?: 'N/A',
                'project' => $projectName ?: 'N/A',
                'reason'  => 'Project or Types Of Material name is empty'
            ];
            return;
        }

        // ✅ Ensure project belongs to logged-in user
        $project = Project::where('id', $userProjectId)
            ->where('project_name', $projectName)
            ->first();

        if (!$project) {
            $this->skippedRows[] = [
                'row'     => $rowNumber,
                'name'    => $categoryName,
                'project' => $projectName,
                'reason'  => 'Project does not match your assigned project'
            ];
            return;
        }

        // ✅ Create category if not exists
        $exists = MaterialCategory::where('project_id', $userProjectId)
            ->where('name', $categoryName)
            ->exists();

        if (!$exists) {
            MaterialCategory::create([
                'name'       => $categoryName,
                'project_id' => $userProjectId,
                'created_by' => $user->id,
            ]);
        } else {
            $this->skippedRows[] = [
                'row'     => $rowNumber,
                'name'    => $categoryName,
                'project' => $projectName,
                'reason'  => "Types Of Material '{$categoryName}' already exists in this project"
            ];
        }
    });
}

public function getSkippedRows()
{
    return $this->skippedRows;
}


}
