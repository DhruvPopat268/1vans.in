<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\UnitCategory;
use App\Models\UnitSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitCategoryController extends Controller
{
       public function index()
    {
        if (\Auth::user()->can('manage working agency')) {
            $user = \Auth::user();

            $unitcategory = \App\Models\UnitCategory::where('project_id', $user->project_assign_id)->get();

            return view('dailyReport.category.index', compact('unitcategory'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create working agency')) {
            $user = \Auth::user();
            $projects = \App\Models\Project::where('created_by', \Auth::id())->where('id', $user->project_assign_id)->pluck('project_name', 'id');

            return view('dailyReport.category.create', compact('projects'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create working agency')) {

            $project_id = \Auth::user()->project_assign_id;

            if (!$project_id) {
                return redirect()->back()->with('error', __('Please create a project first before adding a Category.'));
            }

            // Check if category with same name already exists in the same project
            $exists = \App\Models\UnitCategory::where('project_id', $project_id)
                        ->where('name', $request->name)
                        ->exists();

            if ($exists) {
                return redirect()->back()->with('error', __('A category with the same name already exists in this project.'));
            }

            // Validate the request
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Save
            $unitcategory = new \App\Models\UnitCategory();
            $unitcategory->project_id = $project_id;
            $unitcategory->name = $request->name;
            $unitcategory->created_by = \Auth::user()->id;
            $unitcategory->save();

            return redirect()->route('unit-category.index')->with('success', __('Category Created Successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function edit(UnitCategory $unit_category)
{
    if(\Auth::user()->can('edit working agency'))
    {

        return view('dailyReport.category.edit',compact('unit_category'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function update(Request $request, $id)
{
    if (\Auth::user()->can('edit working agency')) {
        $unitcategory = UnitCategory::find($id);

        if (!$unitcategory) {
            return redirect()->back()->with('error' ,'Category not found.');
        }

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Get user's assigned project
        $project_id = \Auth::user()->project_assign_id;

        if (!$project_id) {
            return redirect()->back()->with('error', 'No project assigned to the user.');
        }

        // Update drawing data
        $unitcategory->project_id = $project_id;
        $unitcategory->name = $request->name;
        $unitcategory->save();

        return redirect()->route('unit-category.index')->with('success', __('Category Updated Successfully'));
    } else {
        return redirect()->back()->with('error' , 'Permission Denied.');
    }
}

public function unitsubcategoryindex(Request $request, $id)
{
    if (\Auth::user()->can('show working agency')) {
        $unit_sub_category = \App\Models\UnitSubCategory::where('category_id', $id)->get();

        return view('dailyReport.sub_category.index', compact('unit_sub_category', 'id'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function unitsubcategorycreate($categoryId)
{
    if (\Auth::user()->can('create working agency')) {
        $category = \App\Models\UnitCategory::findOrFail($categoryId);

        return view('dailyReport.sub_category.create', compact('category'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function unitsubcategorystore(Request $request)
{
    if (\Auth::user()->can('create working agency')) {

        // Validate the request
        $request->validate([
            'category_id' => 'required|exists:unit_categories,id',
            'name' => 'required|string',
        ]);


        // Save to MaterialTestingReportDetails
        $sub_category = new \App\Models\UnitSubCategory();
        $sub_category->category_id = $request->category_id;
        $sub_category->name = $request->name;
        $sub_category->created_by = \Auth::user()->id;


        $sub_category->save();

        return redirect()->back()->with('success', __('Sub Category Created Successfully.'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

    public function unitsubcategoryedit(UnitSubCategory $unit_sub_category)
   {
    if(\Auth::user()->can('edit working agency'))
    {

        return view('dailyReport.sub_category.edit',compact('unit_sub_category'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
   }

public function unitsubcategoryupdate(Request $request, UnitSubCategory $unit_sub_category)
{
    if (\Auth::user()->can('edit working agency')) {

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Update sub-category name only (category_id stays unchanged)
        $unit_sub_category->name = $request->name;
        $unit_sub_category->save();

        return redirect()->back()->with('success', __('Sub Category Updated Successfully'));
    } else {
        return redirect()->back()->with('error', 'Permission Denied.');
    }
}

}
