<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttributeController extends Controller
{
    public function index()
    {
                 $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage material units', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage material units'))
    );

    if ($hasPermission) {
            $user = \Auth::user();

            $attribute = \App\Models\Attribute::where('created_by', $user->id)->get();

            return view('attribute.index', compact('attribute'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create material units')) {
            $user = \Auth::user();

            return view('attribute.create');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


public function store(Request $request)
{
    if (\Auth::user()->can('create material units')) {

        // Validate the request without project_id input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);


        // Create a new attribute record
        $attribute = new \App\Models\Attribute();
        $attribute->name = $request->name;
        $attribute->created_by = \Auth::user()->id;

        // Save the attribute
        $attribute->save();

        // Redirect with a success message
        return redirect()->route('attribute.index')->with('success', __('Attribute Created Successfully.'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function edit(Attribute $attribute)
{
    if(\Auth::user()->can('edit material units'))
    {

        return view('attribute.edit',compact('attribute'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function update(Request $request, $id)
{
    if (\Auth::user()->can('edit material units')) {
        $attribute = \App\Models\Attribute::find($id);

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

        return redirect()->route('attribute.index')->with('success', __('Attribute Updated Successfully'));
    } else {
        return redirect()->back()->with('error' , 'Permission Denied.');
    }
}
}
