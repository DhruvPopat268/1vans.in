<?php

namespace App\Imports;

use App\Models\Project;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;

class HolidayImport implements ToModel
{
    /**
    * @param Collection $collection
    */
    use Importable;

    public function model(array $row)
    {
    }
}
