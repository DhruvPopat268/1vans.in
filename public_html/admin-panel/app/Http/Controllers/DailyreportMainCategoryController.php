<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyReportMainCategory;

class DailyreportMainCategoryController extends Controller
{
        public function index()
    {
         $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage types of work', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage types of work'))
    );

    if ($hasPermission) {
            $user = \Auth::user();

            $maincategory = \App\Models\DailyReportMainCategory::where('project_id', $user->project_assign_id)->get();

            return view('dailyReport.main_category.index', compact('maincategory'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create types of work')) {
            $user = \Auth::user();
                        $projects = \App\Models\Project::where('created_by', \Auth::id())->where('id', $user->project_assign_id)->pluck('project_name', 'id');
    
          return view('dailyReport.main_category.create', compact('projects'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
{
    if (\Auth::user()->can('create types of work')) {

          $project_id = \Auth::user()->project_assign_id;

            if (!$project_id) {
                return redirect()->back()->with('error', __('Please create a project first before adding a Name Of Work.'));
            }

        // Validate the request without project_id input
        $request->validate([
            'name' => 'required|string|max:255'

        ]);


        // Create a new attribute record
        $maincategory = new \App\Models\DailyReportMainCategory();
        $maincategory->project_id = $project_id;
        $maincategory->name = $request->name; 
        $maincategory->created_by = \Auth::user()->id;

        // Save the attribute
        $maincategory->save();

        // Redirect with a success message
        return redirect()->route('main-category.index')->with('success', __('Main Category Created Successfully.'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function edit(DailyReportMainCategory $main_category)
{
    if(\Auth::user()->can('edit types of work'))
    {


        return view('dailyReport.main_category.edit', compact('main_category'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function update(Request $request, $id)
{
    if (\Auth::user()->can('edit types of work')) {
        $maincategory = \App\Models\DailyReportMainCategory::find($id);

        if (!$maincategory) {
            return redirect()->back()->with('error' ,'Main Category not found.');
        }

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

         $project_id = \Auth::user()->project_assign_id;

        if (!$project_id) {
            return redirect()->back()->with('error', 'No project assigned to the user.');
        }

        // Update drawing data
        $maincategory->project_id = $project_id;
        $maincategory->name = $request->name;
           $maincategory->created_by = \Auth::user()->id;

        $maincategory->save();

        return redirect()->route('main-category.index')->with('success', __('Main Category Updated Successfully'));
    } else {
        return redirect()->back()->with('error' , 'Permission Denied.');
    }
}
}
