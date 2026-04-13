<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Drawings;
use App\Models\DrawingsAttachments;
use App\Models\Project;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MaterialTestingReports extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage material testing reports', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage material testing reports'))
    );

    if ($hasPermission) {
            $user = \Auth::user();

            $Materialtestingreports = \App\Models\MaterialTestingReports::where('project_id', $user->project_assign_id)

                ->get();

            return view('material_testing_reports.index', compact('Materialtestingreports'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create material testing reports')) {
            $user = \Auth::user();
            $projects = \App\Models\Project::where('created_by', \Auth::id())->where('id', $user->project_assign_id)->pluck('project_name', 'id');
            return view('material_testing_reports.create', compact('projects'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


public function store(Request $request)
{
    if (\Auth::user()->can('create material testing reports')) {

        // Validate the request without project_id input
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,pdf|max:2048', // allow image/pdf up to 2MB
        ]);

        // Get the project_id from the authenticated user's project_assign_id
        $project_id = \Auth::user()->project_assign_id; // assuming the project_assign_id exists on the User model

        if (!$project_id) {
            return redirect()->back()->with('error', __('Please create a project first before adding a Work Drawing Category.'));
        }

        // Create a new drawing record
        $Materialtestingreports = new \App\Models\MaterialTestingReports();
        $Materialtestingreports->project_id = $project_id; // Automatically assign project_id
        $Materialtestingreports->name = $request->name;
        $Materialtestingreports->created_by = \Auth::user()->id;

        // Check if the image file is present in the request
        if ($request->hasFile('image')) {
            $fileName = time() . '_' . $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('Material-Testing-Reports', $fileName, 'local'); // Goes to storage/app/public/Working-Drawings
            $Materialtestingreports->image = 'Material-Testing-Reports/' . $fileName;
        }

        // Save the drawing
        $Materialtestingreports->save();

        // Redirect with a success message
        return redirect()->route('material-testing-reports.index')->with('success', __('Material Testing Reports Created Successfully.'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}


public function edit(\App\Models\MaterialTestingReports $material_testing_report)
{
    if(\Auth::user()->can('edit material testing reports')) {
        return view('material_testing_reports.edit', compact('material_testing_report'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}


public function update(Request $request, $id)
{
    if (\Auth::user()->can('edit material testing reports')) {
        $Materialtestingreports = \App\Models\MaterialTestingReports::find($id);

        if (!$Materialtestingreports) {
            return redirect()->back()->with('error' ,'Material Testing Reports not found.');
        }

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);



        // Update drawing data
        $Materialtestingreports->name = $request->name;

        if ($request->hasFile('image')) {
            $fileName = time() . '_' . $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('Material-Testing-Reports', $fileName, 'local');
            $Materialtestingreports->image = 'Material-Testing-Reports/' . $fileName;
        }

        $Materialtestingreports->save();

        return redirect()->route('material-testing-reports.index')->with('success', __('Material Testing Reports Updated Successfully'));
    } else {
        return redirect()->back()->with('error' , 'Permission Denied.');
    }
}




public function show(\App\Models\MaterialTestingReports $material_testing_report)
{
    if (\Auth::user()->can('show material testing reports')) {
        // Retrieve related MaterialTestingReportDetails
        $details = $material_testing_report->details()->orderBy('id', 'desc')->get();
        return view('material_testing_reports.show', compact('material_testing_report', 'details'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}



    public function reportsDetailscreate($reportId)
    {
        if (\Auth::user()->can('show material testing reports')) {
            $report = \App\Models\MaterialTestingReports::findOrFail($reportId);
            return view('material_testing_reports.details.create', compact('report'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }



    public function reportsDetailsstore(Request $request)
    {
        if (\Auth::user()->can('show material testing reports')) {

            // Validate the request
            $request->validate([
                'material_testing_reports_id' => 'required|exists:material_testing_reports,id',
                'remark' => 'required|string|max:255',
                'file' => 'required|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,txt|max:5000', // Add more formats if needed
            ]);

            // Handle document upload
            $filePath = null;
            if ($request->hasFile('file')) {
                // Generate a unique file name
                $fileName = time() . '_' . $request->file('file')->getClientOriginalName();

                // Store the file in the specified directory
                $filePath = $request->file('file')->storeAs('Material-Testing-Reports/Details/', $fileName, 'local');
            }

            // Save to MaterialTestingReportDetails
            $details = new \App\Models\MaterialTestingReportDetails();
            $details->material_testing_reports_id = $request->material_testing_reports_id;
            $details->remark = $request->remark;
            $details->file = $filePath ? 'Material-Testing-Reports/Details/' . $fileName : null; // Save document path
            $details->save();

            return redirect()->back()->with('success', __('Material Testing Report Detail Created Successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    
      public function reportsDetailsedit(\App\Models\MaterialTestingReportDetails $material_testing_report_details)
{
    if(\Auth::user()->can('show material testing reports')) {
        return view('material_testing_reports.details.edit', compact('material_testing_report_details'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}



public function reportsDetailsupdate(Request $request, $id)
{
    if (\Auth::user()->can('show material testing reports')) {
        $materialTestingReport = \App\Models\MaterialTestingReportDetails::find($id);

        if (!$materialTestingReport) {
            return redirect()->back()->with('error', 'Material Testing Report not found.');
        }

        $request->validate([
            'image_name' => 'nullable|string|max:255',
        ]);

        if ($request->has('image_name')) {
            $oldFilePath = $materialTestingReport->file; // e.g. Material-Testing-Reports/Details/OldName.pdf
            $pathInfo = pathinfo($oldFilePath);
            $extension = isset($pathInfo['extension']) ? $pathInfo['extension'] : '';
            $newFilename = $request->image_name . '.' . $extension;
            $newFilePath = $pathInfo['dirname'] . '/' . $newFilename;

            // Rename the physical file
            if (\Illuminate\Support\Facades\Storage::disk('local')->exists($oldFilePath)) {
                \Illuminate\Support\Facades\Storage::disk('local')->move($oldFilePath, $newFilePath);
            } else {
                return redirect()->back()->with('error', 'Original file does not exist on disk.');
            }

            // Update DB path
            $materialTestingReport->file = $newFilePath;
            $materialTestingReport->save();
        }

        return redirect()->back()->with('success', __('Material Testing Report file name updated successfully.'));
    }

    return redirect()->back()->with('error', 'Permission Denied.');
}

    public function billofquantityindex()
    {
        $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage bill of quantity', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage bill of quantity'))
    );

    if ($hasPermission) {
            $user = \Auth::user();
            $bill_of_quantity = \App\Models\BillOfQuantity::where('project_id', $user->project_assign_id)->orderBy('id', 'desc')->get();
            return view('bill_of_quantity.index', compact('bill_of_quantity'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function billofquantityfileUpload(Request $request)
    {
       
            $assignedProjectId = \Auth::user()->project_assign_id;

            $project = \App\Models\Project::find($assignedProjectId);
            if (!$project) {
                return response()->json(['is_success' => false, 'error' => __('Assigned project not found.')], 404);
            }

            $request->validate(['file' => 'required']);
            $image_size = $request->file('file')->getSize();

            $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
            if ($result == 1) {
                $fileName = $assignedProjectId . '_' . $request->file->getClientOriginalName();
                $dir = 'bill_of_quantity/'; // Corrected folder name

                try {
                    $path = Utility::upload_file($request, 'file', $fileName, $dir, []);

                    if (isset($path['flag']) && $path['flag'] == 1) {
                        $file = $path['url'];
                    } else {
                        return response()->json(['is_success' => false, 'error' => $path['msg'] ?? 'Unknown upload error'], 400);
                    }
                } catch (\Exception $e) {
                    \Log::error('Upload Exception: ' . $e->getMessage());
                    return response()->json(['is_success' => false, 'error' => 'Upload failed: ' . $e->getMessage()], 500);
                }

                $attachment = \App\Models\BillOfQuantity::create([
                    'project_id' => $assignedProjectId,
                    'files' => $file,
                ]);

                // Assuming $drawing is available somehow – fix or remove this part if needed
                $return = [
                    'is_success' => true,
                    'download' => route('billofquantity.file.download', [$attachment->id]),
                    'delete' => route('billofquantity.file.delete', [$attachment->id]),

                ];


                return response()->json($return);
            }

            return response()->json(['is_success' => false, 'error' => __('Storage Limit Exceeded')], 400);
        }

      


    public function billOfQuantitydeleteAttachment($id)
{
     if (\Auth::user()->can('delete bill of quantity')) {
    $attachment = \App\Models\BillOfQuantity::findOrFail($id);
    $filePath = storage_path('storage/' . $attachment->files);

    if (file_exists($filePath)) {
        unlink($filePath);
    }

    $attachment->delete();

    return redirect()->back()->with('success', 'Attachment deleted successfully.');
     } else {
        return redirect()->back()->with('error' , 'Permission Denied.');
    }

}

}
