<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MaterialCategory;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialCategoryController extends Controller
{
    public function index()
    {
                 $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage types of material', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage types of material'))
    );

    if ($hasPermission) {
            $user = \Auth::user();

            $materialcategory = \App\Models\MaterialCategory::where('project_id', $user->project_assign_id)->get();

            return view('material.category.index', compact('materialcategory'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create types of material')) {
            $user = \Auth::user();
            $projects = \App\Models\Project::where('created_by', \Auth::id())->where('id', $user->project_assign_id)->pluck('project_name', 'id');

            return view('material.category.create', compact('projects'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create types of material')) {

            $project_id = \Auth::user()->project_assign_id;

            if (!$project_id) {
                return redirect()->back()->with('error', __('Please create a project first before adding a Material Category.'));
            }

            // Check if category with same name already exists in the same project
            $exists = \App\Models\MaterialCategory::where('project_id', $project_id)
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
            $materialcategory = new \App\Models\MaterialCategory();
            $materialcategory->project_id = $project_id;
            $materialcategory->name = $request->name;
            $materialcategory->created_by = \Auth::user()->id;
            $materialcategory->save();

            return response()->json(['success' => true, 'message' => __('Material Category Created Successfully.')]);
        } else {
            return response()->json(['success' => false, 'message' => __('Permission Denied.')], 403);
        }
    }


public function edit(MaterialCategory $material_category)
{
    if(\Auth::user()->can('edit types of material'))
    {

        return view('material.category.edit',compact('material_category'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function update(Request $request, $id)
{
    if (\Auth::user()->can('edit types of material')) {
        $materialcategory = \App\Models\MaterialCategory::find($id);

        if (!$materialcategory) {
            return redirect()->back()->with('error' ,'Drawing not found.');
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
        $materialcategory->project_id = $project_id;
        $materialcategory->name = $request->name;
        $materialcategory->save();

        return response()->json(['success' => true, 'message' => __('Material Category Updated Successfully')]);
    } else {
        return response()->json(['success' => false, 'message' => 'Permission Denied.'], 403);
    }
}

public function subcategoryindex(Request $request, $id)
{
    if (\Auth::user()->can('show types of material')) {
        $material_sub_category = \App\Models\MaterialSubCategory::where('category_id', $id)->get();

        return view('material.sub_category.index', compact('material_sub_category', 'id'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function subcategorycreate($categoryId)
{
    if (\Auth::user()->can('create types of material')) {
        $category = \App\Models\MaterialCategory::findOrFail($categoryId);
        $attribute = \App\Models\Attribute::where('created_by', \Auth::id())->pluck('name', 'id');

        return view('material.sub_category.create', compact('category','attribute'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function subcategorystore(Request $request)
{
    if (\Auth::user()->can('create types of material')) {

        // Validate the request
        $request->validate([
            'category_id' => 'required|exists:material_categories,id',
            'attribute_id' => 'required|exists:attributes,id',
            'name' => 'required|string',
            'price' => 'required|string',
        ]);


        // Save to MaterialTestingReportDetails
        $sub_category = new \App\Models\MaterialSubCategory();
        $sub_category->category_id = $request->category_id;
        $sub_category->attribute_id = $request->attribute_id;
        $sub_category->name = $request->name;
        $sub_category->price = $request->price;
        $sub_category->created_by = \Auth::user()->id;


        $sub_category->save();

        return response()->json(['success' => true, 'message' => __('Sub Category Created Successfully.')]);
    } else {
        return response()->json(['success' => false, 'message' => __('Permission Denied.')], 403);
    }
}

public function subcategoryedit(\App\Models\MaterialSubCategory $material_sub_category)
{
    if(\Auth::user()->can('edit types of material'))
    {

        return view('material.sub_category.edit',compact('material_sub_category'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function subcategoryupdate(Request $request, $id)
{
    if (\Auth::user()->can('edit types of material')) {
        $material_sub_category = \App\Models\MaterialSubCategory::find($id);

        if (!$material_sub_category) {
            return redirect()->back()->with('error' ,'Sub Category not found.');
        }

        // Validate input
        $request->validate([
            'status' => 'required|string|max:255',
        ]);


        $material_sub_category->status = $request->status;
        $material_sub_category->save();

        return response()->json(['success' => true, 'message' => __('Material Sub Category Updated Successfully')]);
    } else {
        return response()->json(['success' => false, 'message' => 'Permission Denied.'], 403);
    }
}

}
