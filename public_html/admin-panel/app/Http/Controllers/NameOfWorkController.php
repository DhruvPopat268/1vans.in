<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NameOfWork;
use App\Models\Project;
use App\Models\UnitCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NameOfWorkController extends Controller
{
    public function index()
    {
         $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage name of works', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage name of works'))
    );

    if ($hasPermission) {
            $user = \Auth::user();

            $nameOfWork = \App\Models\NameOfWork::where('project_id', $user->project_assign_id)->with(['unitCategory', 'mainCategory'])->get();

            return view('dailyReport.name_of_work.index', compact('nameOfWork'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create name of works')) {
            $user = \Auth::user();
                        $projects = \App\Models\Project::where('created_by', \Auth::id())->where('id', $user->project_assign_id)->pluck('project_name', 'id');

               $unitCategories = \App\Models\UnitCategory::where('project_id', $user->project_assign_id)
                   ->pluck('name', 'id');
                   $attributes = \App\Models\MesurementAttribute::where('project_id', $user->project_assign_id)
    ->pluck('name', 'id');
              $maincategory = \App\Models\DailyReportMainCategory::where('project_id', $user->project_assign_id)
                       ->pluck('name', 'id');
    
          return view('dailyReport.name_of_work.create', compact('projects', 'unitCategories','attributes','maincategory'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    
 

public function getSubAttributes($attributeId)
    {
        $groups = \App\Models\MesurementSubAttribute::where('attribute_id', $attributeId)->pluck('name', 'id');
        return response()->json($groups);
    }



public function store(Request $request)
{
    if (\Auth::user()->can('create name of works')) {

          $project_id = \Auth::user()->project_assign_id;

            if (!$project_id) {
                return redirect()->back()->with('error', __('Please create a project first before adding a Name Of Work.'));
            }

        // Validate the request without project_id input
        $request->validate([
            'name' => 'required|string|max:255',
                'unit_category_id' => 'nullable|exists:unit_categories,id',
                 'mesurement_attribute_id' => 'nullable|exists:mesurement_attributes,id',
            'mesurement_sub_attribute_id' => 'nullable|exists:mesurement_sub_attributes,id',
             'daily_report_main_category_id' => 'nullable|exists:daily_report_main_categories,id',
             'total_mesurement' => 'required|string|max:255',
              'start_date' => 'required|date',
    'end_date' => 'required|date|after_or_equal:start_date',    

        ]);


        // Create a new attribute record
        $work = new \App\Models\NameOfWork();
        $work->project_id = $project_id;
        $work->name = $request->name;
        $work->unit_category_id = $request->unit_category_id; // save the unit category
         $work->mesurement_attribute_id = $request->mesurement_attribute_id; // save the unit category
        $work->mesurement_sub_attribute_id = $request->mesurement_sub_attribute_id; // save the unit category
        $work->daily_report_main_category_id = $request->daily_report_main_category_id;
         $work->total_mesurement = $request->total_mesurement;
          $work->start_date = $request->start_date;
$work->end_date = $request->end_date;
        
        $work->created_by = \Auth::user()->id;

        // Save the attribute
        $work->save();

        // Redirect with a success message
        return response()->json(['success' => true, 'message' => __('Name Of Work Created Successfully.')]);
    } else {
        return response()->json(['success' => false, 'message' => __('Permission Denied.')], 403);
    }
}

public function edit(NameOfWork $name_of_work)
{
    if(\Auth::user()->can('edit name of works'))
    {

         $project_id = \Auth::user()->project_assign_id;

        $unitCategories = \App\Models\UnitCategory::where('project_id', $project_id)
            ->pluck('name', 'id');
        $maincategory = \App\Models\DailyReportMainCategory::where('project_id', $project_id)
            ->pluck('name', 'id');

        return view('dailyReport.name_of_work.edit', compact('name_of_work', 'unitCategories','maincategory'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function update(Request $request, $id)
{
    if (\Auth::user()->can('edit name of works')) {
        $work = \App\Models\NameOfWork::find($id);

        if (!$work) {
            return redirect()->back()->with('error' ,'Name Of Work not found.');
        }

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
                        'unit_category_id' => 'nullable|exists:unit_categories,id',
             'daily_report_main_category_id' => 'nullable|exists:daily_report_main_categories,id',
             'total_mesurement' => 'required|string|max:255',
              'start_date' => 'required|date',
    'end_date' => 'required|date|after_or_equal:start_date',

        ]);

         $project_id = \Auth::user()->project_assign_id;

        if (!$project_id) {
            return redirect()->back()->with('error', 'No project assigned to the user.');
        }

        // Update drawing data
        $work->project_id = $project_id;
        $work->name = $request->name;
          $work->unit_category_id = $request->unit_category_id;
           $work->daily_report_main_category_id = $request->daily_report_main_category_id;
            $work->total_mesurement = $request->total_mesurement;
             $work->start_date = $request->start_date;
$work->end_date = $request->end_date;
           $work->created_by = \Auth::user()->id;

        $work->save();

        return response()->json(['success' => true, 'message' => __('Name Of Work Updated Successfully')]);
    } else {
        return response()->json(['success' => false, 'message' => 'Permission Denied.'], 403);
    }
}
}
