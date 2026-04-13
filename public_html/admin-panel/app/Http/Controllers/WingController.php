<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Flour;
use App\Models\Project;
use App\Models\UnitCategory;
use App\Models\UnitSubCategory;
use App\Models\wing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WingController extends Controller
{
           public function index()
    {
         $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage working area', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage working area'))
    );

    if ($hasPermission) {
            $user = \Auth::user();

            $wing = \App\Models\wing::where('project_id', $user->project_assign_id)->get();

            return view('dailyReport.wing.index', compact('wing'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create working area')) {
            $user = \Auth::user();
            $projects = \App\Models\Project::where('created_by', \Auth::id())->where('id', $user->project_assign_id)->pluck('project_name', 'id');

            return view('dailyReport.wing.create', compact('projects'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create working area')) {

            $project_id = \Auth::user()->project_assign_id;

            if (!$project_id) {
                return redirect()->back()->with('error', __('Please create a project first before adding a Wing.'));
            }

            // Check if category with same name already exists in the same project
            $exists = \App\Models\wing::where('project_id', $project_id)
                        ->where('name', $request->name)
                        ->exists();

            if ($exists) {
                return redirect()->back()->with('error', __('A wing with the same name already exists in this project.'));
            }

            // Validate the request
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Save
            $wing = new \App\Models\wing();
            $wing->project_id = $project_id;
            $wing->name = $request->name;
            $wing->created_by = \Auth::user()->id;
            $wing->save();

            return response()->json(['success' => true, 'message' => __('Wing Created Successfully.')]);
        } else {
            return response()->json(['success' => false, 'message' => __('Permission Denied.')], 403);
        }
    }

    public function edit(wing $wing)
{
    if(\Auth::user()->can('edit working area'))
    {

        return view('dailyReport.wing.edit',compact('wing'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function update(Request $request, $id)
{
    if (\Auth::user()->can('edit working area')) {
        $wing = \App\Models\wing::find($id);

        if (!$wing) {
            return redirect()->back()->with('error' ,'wing not found.');
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
        $wing->project_id = $project_id;
        $wing->name = $request->name;
        $wing->save();

        return response()->json(['success' => true, 'message' => __('Wing Updated Successfully')]);
    } else {
        return response()->json(['success' => false, 'message' => 'Permission Denied.'], 403);
    }
}

public function flourindex(Request $request, $id)
{
    if (\Auth::user()->can('show working area')) {
        $flour = \App\Models\Flour::where('wing_id', $id)->get();

        return view('dailyReport.wing.flour.index', compact('flour', 'id'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function flourcreate($wingId)
{
    if (\Auth::user()->can('create working area')) {
        $wing = \App\Models\wing::findOrFail($wingId);

        return view('dailyReport.wing.flour.create', compact('wing'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function flourstore(Request $request)
{
    if (\Auth::user()->can('create working area')) {

        // Validate the request
        $request->validate([
            'wing_id' => 'required|exists:wings,id',
            'name' => 'required|string',
        ]);


        // Save to MaterialTestingReportDetails
        $flour = new \App\Models\Flour();
        $flour->wing_id = $request->wing_id;
        $flour->name = $request->name;
        $flour->created_by = \Auth::user()->id;


        $flour->save();

        return response()->json(['success' => true, 'message' => __('Flour Created Successfully.')]);
    } else {
        return response()->json(['success' => false, 'message' => __('Permission Denied.')], 403);
    }
}

    public function flouredit(Flour $flour)
   {
    if(\Auth::user()->can('edit working area'))
    {

        return view('dailyReport.wing.flour.edit',compact('flour'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
   }

public function flourupdate(Request $request, Flour $flour)
{
    if (\Auth::user()->can('edit working area')) {

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Update sub-category name only (category_id stays unchanged)
        $flour->name = $request->name;
        $flour->save();

        return response()->json(['success' => true, 'message' => __('Flour Updated Successfully')]);
    } else {
        return response()->json(['success' => false, 'message' => 'Permission Denied.'], 403);
    }
}
}
