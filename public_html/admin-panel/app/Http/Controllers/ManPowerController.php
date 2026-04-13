<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManPowerController extends Controller
{
        public function index()
    {
                $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage man power', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage man power'))
    );

    if ($hasPermission) {
            $user = \Auth::user();

            $manpower = \App\Models\ManPower::where('project_id', $user->project_assign_id)->get();

            return view('dailyReport.manpower.index', compact('manpower'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create man power')) {
            $user = \Auth::user();
                        $projects = \App\Models\Project::where('created_by', \Auth::id())->where('id', $user->project_assign_id)->pluck('project_name', 'id');

          return view('dailyReport.manpower.create', compact('projects'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


public function store(Request $request)
{
    if (\Auth::user()->can('create man power')) {

          $project_id = \Auth::user()->project_assign_id;

            if (!$project_id) {
                return redirect()->back()->with('error', __('Please create a project first before adding a Man Power.'));
            }

        // Validate the request without project_id input
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|string',
        ]);


        // Create a new attribute record
        $manpower = new \App\Models\ManPower();
        $manpower->project_id = $project_id;
        $manpower->name = $request->name;
        $manpower->price = $request->price; // save the unit category
        $manpower->created_by = \Auth::user()->id;

        // Save the attribute
        $manpower->save();

        // Redirect with a success message
        return redirect()->route('man-power.index')->with('success', __('Man Power Created Successfully.'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}
public function edit(\App\Models\ManPower $man_power)
{
    if(\Auth::user()->can('edit man power'))
    {

        return view('dailyReport.manpower.edit',compact('man_power'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function update(Request $request, $id)
{
    if (\Auth::user()->can('edit man power')) {
        $man_power = \App\Models\ManPower::find($id);

        if (!$man_power) {
            return redirect()->back()->with('error' ,'ManPower not found.');
        }

        // Validate input
        $request->validate([
           'status' => 'required|string|max:255',
        ]);

        // Update drawing data
        $man_power->status = $request->status;

        $man_power->save();

        return redirect()->route('man-power.index')->with('success', __('ManPower Updated Successfully'));
    } else {
        return redirect()->back()->with('error' , 'Permission Denied.');
    }
}

public function editmanpowerdata(\App\Models\ManPower $man_power)
{
    if(\Auth::user()->can('edit man power'))
    {

        return view('dailyReport.manpower.other_edit',compact('man_power'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function updatemanpowerdata(Request $request, $id)
{
    if (\Auth::user()->can('edit man power')) {
        $man_power = \App\Models\ManPower::find($id);

        if (!$man_power) {
            return redirect()->back()->with('error' ,'ManPower not found.');
        }

        // Validate input
        $request->validate([
           'name' => 'nullable|string|max:255',
            'price'  => 'nullable|numeric|min:0',
        ]);

        // Update drawing data
        $man_power->name = $request->name;
         $man_power->price = $request->price;

        $man_power->save();

        return redirect()->route('man-power.index')->with('success', __('ManPower Updated Successfully'));
    } else {
        return redirect()->back()->with('error' , 'Permission Denied.');
    }
}
}
