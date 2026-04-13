<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MesurementAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MesurementAttributeController extends Controller
{
    public function index()
    {
                 $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage types of measurements', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage types of measurements'))
    );

    if ($hasPermission) {
            $user = \Auth::user();

            $attribute = \App\Models\MesurementAttribute::where('project_id', $user->project_assign_id)->get();

            return view('dailyReport.attribute.index', compact('attribute'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create types of measurements')) {
            $user = \Auth::user();

            return view('dailyReport.attribute.create');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


public function store(Request $request)
{
    if (\Auth::user()->can('create types of measurements')) {

        $project_id = \Auth::user()->project_assign_id;

            if (!$project_id) {
                return redirect()->back()->with('error', __('Please create a project first before adding a Attribute.'));
            }

        // Validate the request without project_id input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);


        // Create a new attribute record
        $attribute = new \App\Models\MesurementAttribute();
        $attribute->name = $request->name;
        $attribute->project_id = $project_id;
        $attribute->created_by = \Auth::user()->id;

        // Save the attribute
        $attribute->save();

        // Redirect with a success message
        return response()->json(['success' => true, 'message' => __('Attribute Created Successfully.')]);
    } else {
        return response()->json(['success' => false, 'message' => __('Permission Denied.')], 403);
    }
}

public function edit(MesurementAttribute $mesurement_attribute)
{
    if(\Auth::user()->can('edit types of measurements'))
    {

        return view('dailyReport.attribute.edit',compact('mesurement_attribute'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function update(Request $request, $id)
{
    if (\Auth::user()->can('edit types of measurements')) {
        $attribute = \App\Models\MesurementAttribute::find($id);

        if (!$attribute) {
            return redirect()->back()->with('error' ,'Attribute not found.');
        }

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Update drawing data
        $attribute->name = $request->name;

        $attribute->save();

        return response()->json(['success' => true, 'message' => __('Attribute Updated Successfully')]);
    } else {
        return response()->json(['success' => false, 'message' => 'Permission Denied.'], 403);
    }
}

public function subAttributeindex(Request $request, $id)
{
    if (\Auth::user()->can('show types of measurements')) {
        $mesaurement_sub_attribute = \App\Models\MesurementSubAttribute::where('attribute_id', $id)->get();

        return view('dailyReport.attribute.sub_attribute.index', compact('mesaurement_sub_attribute', 'id'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function subAttributecreate($AttributeId)
{
    if (\Auth::user()->can('create types of measurements')) {
        $attribute = \App\Models\MesurementAttribute::findOrFail($AttributeId);

        return view('dailyReport.attribute.sub_attribute.create', compact('attribute'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function subAttributestore(Request $request)
{
    if (\Auth::user()->can('create types of measurements')) {

        // Validate the request
        $request->validate([
            'attribute_id' => 'required|exists:mesurement_attributes,id',
            'name' => 'required|string',
        ]);


        // Save to MaterialTestingReportDetails
        $sub_category = new \App\Models\MesurementSubAttribute();
        $sub_category->attribute_id = $request->attribute_id;
        $sub_category->name = $request->name;


        $sub_category->save();

        return response()->json(['success' => true, 'message' => __('Sub Attribute Created Successfully.')]);
    } else {
        return response()->json(['success' => false, 'message' => __('Permission Denied.')], 403);
    }
}

public function subAttributeedit(\App\Models\MesurementSubAttribute $mesaurement_sub_attribute)
{
    if(\Auth::user()->can('edit types of measurements'))
    {

        return view('dailyReport.attribute.sub_attribute.edit',compact('mesaurement_sub_attribute'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function subAttributeupdate(Request $request, $id)
{
    if (\Auth::user()->can('edit types of measurements')) {
        $mesaurement_sub_attribute = \App\Models\MesurementSubAttribute::find($id);

        if (!$mesaurement_sub_attribute) {
            return redirect()->back()->with('error' ,'Sub Attribute not found.');
        }

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);


        $mesaurement_sub_attribute->name = $request->name;
        $mesaurement_sub_attribute->save();

        return response()->json(['success' => true, 'message' => __('Mesaurement Sub Attribute Updated Successfully')]);
    } else {
        return response()->json(['success' => false, 'message' => 'Permission Denied.'], 403);
    }
}
}
