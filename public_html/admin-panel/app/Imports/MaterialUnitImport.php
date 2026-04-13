<?php

namespace App\Imports;

use App\Models\Attribute;
use App\Models\Project;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Auth;

class MaterialUnitImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
   public function collection(Collection $rows)
{
    $userId = Auth::id();

    // Skip header row
    $rows->skip(1)->each(function ($row, $index) use ($userId) {
        $rowNumber = $index + 2; // because we skipped header, Excel row starts at 2
        $name = trim($row[0] ?? ''); // First column: Name

        if (!empty($name)) {
            // ✅ Check duplicate by created_by + name
            $exists = Attribute::where('name', $name)
                ->where('created_by', $userId)
                ->exists();

            if (!$exists) {
                Attribute::create([
                    'name'       => $name,
                    'created_by' => $userId,
                ]);
            } else {
                $this->skippedRows[] = [
                    'row'    => $rowNumber,
                    'name'   => $name,
                    'reason' => "Duplicate entry for this user",
                ];
            }
        } else {
            $this->skippedRows[] = [
                'row'    => $rowNumber,
                'name'   => 'N/A',
                'reason' => "Name is empty",
            ];
        }
    });
}


}
