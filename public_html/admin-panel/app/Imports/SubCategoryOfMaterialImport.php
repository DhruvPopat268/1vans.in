<?php

namespace App\Imports;

use App\Models\Project;
use App\Models\MaterialCategory;
use App\Models\Attribute;
use App\Models\MaterialSubCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;

class SubCategoryOfMaterialImport implements ToCollection
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
            $rowNumber       = $index + 2; // Excel row number
            $projectName     = trim($row[0]); // Project Name
            $categoryName    = trim($row[1]); // Category Name
            $attributeName   = trim($row[3]); // Attribute Name
            $subAttrName     = trim($row[2]); // Sub Attribute Name
            $price           = trim($row[4]); // Price

            // ✅ Check required values
            if (empty($projectName) || empty($categoryName) || empty($attributeName) || empty($subAttrName)) {
                $this->skippedRows[] = [
                    'row'     => $rowNumber,
                    'name'    => $subAttrName ?: 'N/A',
                    'project' => $projectName ?: 'N/A',
                    'reason'  => 'Missing required fields (Project, Category, Attribute, Sub Attribute)'
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
                    'name'    => $subAttrName,
                    'project' => $projectName,
                    'reason'  => 'Project not found or not assigned to you'
                ];
                return;
            }

            // ✅ Find Category
            $category = MaterialCategory::where('project_id', $project->id)
                ->where('name', $categoryName)
                ->first();

            if (!$category) {
                $this->skippedRows[] = [
                    'row'     => $rowNumber,
                    'name'    => $subAttrName,
                    'project' => $projectName,
                    'reason'  => "Types Of Material '{$categoryName}' not found"
                ];
                return;
            }

            // ✅ Find Attribute
            $attribute = Attribute::where('name', $attributeName)->where('created_by', $user->id)
    ->first();

            if (!$attribute) {
                $this->skippedRows[] = [
                    'row'     => $rowNumber,
                    'name'    => $subAttrName,
                    'project' => $projectName,
                    'reason'  => "Material Units '{$attributeName}' not found"
                ];
                return;
            }

            // ✅ Check duplicate
            $exists = MaterialSubCategory::where('category_id', $category->id)
                ->where('attribute_id', $attribute->id)
                ->where('name', $subAttrName)
                ->exists();

            if (!$exists) {
                MaterialSubCategory::create([
                    'name'        => $subAttrName,
                    'category_id' => $category->id,
                    'attribute_id'=> $attribute->id,
                    'price'       => $price ?: 0,
                    'created_by'  => $user->id,
                    'status'      => 'Active',
                ]);
            } else {
                $this->skippedRows[] = [
                    'row'     => $rowNumber,
                    'name'    => $subAttrName,
                    'project' => $projectName,
                    'reason'  => "Sub Category Material '{$subAttrName}' already exists under '{$categoryName}' + '{$attributeName}'"
                ];
            }
        });
    }

    public function getSkippedRows()
    {
        return $this->skippedRows;
    }
}
