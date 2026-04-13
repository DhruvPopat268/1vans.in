<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TypesOfWorkImport;

class ImportDataController extends Controller
{

     public function index()
    {
                 $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage import data', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage import data'))
    );

    if ($hasPermission) {
            $user = \Auth::user();

            return view('importData.index');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

public function importTypesOfWork(Request $request)
{
    $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

    $import = new TypesOfWorkImport;
    Excel::import($import, $request->file('file'));

    if (!empty($import->skippedRows)) {
        return back()
            ->with('error', 'Some rows were skipped.')
            ->with('skippedRows', $import->skippedRows);
    }

    return back()->with('success', 'Types of Work imported successfully!');
}



public function importNameOfWorks(Request $request)
{
    $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

    try {
        $import = new \App\Imports\NameOfWorksImport();
        Excel::import($import, $request->file('file'));

        $skippedRows = $import->getSkippedRows();

        if (!empty($skippedRows)) {
            return back()->with([
                'error' => 'Some rows were skipped during import.',
                'skippedRows' => $skippedRows
            ]);
        }

        return back()->with('success', 'Name of Works imported successfully!');
    } catch (\Exception $e) {
        return back()->with('error', 'Import failed: ' . $e->getMessage());
    }
}

public function importWorkingAreaWorkSection(Request $request)
{
    $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

    $import = new \App\Imports\WorkingAreaImport();
    Excel::import($import, $request->file('file'));

    if (!empty($import->skippedRows)) {
        return back()->with([
            'error' => 'Some rows were skipped during import.',
            'skippedRows' => $import->skippedRows
        ]);
    }

    return back()->with('success', 'Working Area imported successfully!');
}

public function importTypesOfMeasurementsUnit(Request $request)
{
    $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

    $import = new \App\Imports\TypesOfMeasurementsImport();
    Excel::import($import, $request->file('file'));

    if (!empty($import->skippedRows)) {
        return back()->with([
            'error' => 'Some rows were skipped during import.',
            'skippedRows' => $import->skippedRows
        ]);
    }

    return back()->with('success', 'Types Of Measurements & Unit imported successfully!');
}

public function importTypesOfEquipment(Request $request)
{
    $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

    $import = new \App\Imports\TypesOfEquipmentImport;
    Excel::import($import, $request->file('file'));

    if (!empty($import->skippedRows)) {
        return back()
            ->with('error', 'Some rows were skipped.')
            ->with('skippedRows', $import->skippedRows);
    }

    return back()->with('success', 'Types of Equipment imported successfully!');
}

public function importMaterialUnit(Request $request)
{
    $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

    $import = new \App\Imports\MaterialUnitImport;
    Excel::import($import, $request->file('file'));

    if (!empty($import->skippedRows)) {
        return back()
            ->with('error', 'Some rows were skipped.')
            ->with('skippedRows', $import->skippedRows);
    }

    return back()->with('success', 'Material Unit imported successfully!');
}

public function importTypesofMaterial(Request $request)
{
    $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

    $import = new \App\Imports\TypesofMaterialImport();
    Excel::import($import, $request->file('file'));

    if (!empty($import->skippedRows)) {
        return back()->with([
            'error' => 'Some rows were skipped during import.',
            'skippedRows' => $import->skippedRows
        ]);
    }

    return back()->with('success', 'Types Of Material imported successfully!');
}

public function importTypesofMaterialSubCategory(Request $request)
{
    $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

    $import = new \App\Imports\SubCategoryOfMaterialImport();
    Excel::import($import, $request->file('file'));

    if (!empty($import->skippedRows)) {
        return back()->with([
            'error' => 'Some rows were skipped during import.',
            'skippedRows' => $import->skippedRows
        ]);
    }

    return back()->with('success', 'Sub Category Of Material imported successfully!');
}


}
