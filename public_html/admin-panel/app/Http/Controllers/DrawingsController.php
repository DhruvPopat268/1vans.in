<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Drawings;
use App\Models\Project;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DrawingsController extends Controller
{

       public function index()
    {
         $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage working drawings', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage working drawings'))
    );

    if ($hasPermission) {
            $user = \Auth::user();

            $drawings = Drawings::where('project_id', $user->project_assign_id)
                ->with(['attachments' => function ($query) {
                    $query->latest()->limit(3); // only fetch latest 3 attachments
                }])
                ->get();

            return view('drawings.index', compact('drawings'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create working drawings')) {
            $user = \Auth::user();
            $projects = Project::where('created_by', \Auth::id())->where('id', $user->project_assign_id)->pluck('project_name', 'id');
            return view('drawings.create', compact('projects'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


public function store(Request $request)
{
    if (\Auth::user()->can('create working drawings')) {

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
        $drawing = new \App\Models\Drawings();
        $drawing->project_id = $project_id; // Automatically assign project_id
        $drawing->name = $request->name;
        $drawing->created_by = \Auth::user()->id;

        // Check if the image file is present in the request
        if ($request->hasFile('image')) {
            $fileName = time() . '_' . $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('Working-Drawings', $fileName, 'local'); // Goes to storage/app/public/Working-Drawings
            $drawing->image = 'Working-Drawings/' . $fileName;
        }

        // Save the drawing
        $drawing->save();

        // Redirect with a success message
        return redirect()->route('drawings.index')->with('success', __('Working Drawing Created Successfully.'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}


public function edit(Drawings $drawing)
{
    if(\Auth::user()->can('edit working drawings'))
    {

        return view('drawings.edit',compact('drawing'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function update(Request $request, $id)
{
    if (\Auth::user()->can('edit working drawings')) {
        $drawing = \App\Models\Drawings::find($id);

        if (!$drawing) {
            return redirect()->back()->with('error' ,'Drawing not found.');
        }

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

       

        // Update drawing data
        $drawing->name = $request->name;

        if ($request->hasFile('image')) {
            $fileName = time() . '_' . $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('Working-Drawings', $fileName, 'local');
            $drawing->image = 'Working-Drawings/' . $fileName;
        }

        $drawing->save();

        return redirect()->route('drawings.index')->with('success', __('Drawing Updated Successfully'));
    } else {
        return redirect()->back()->with('error' , 'Permission Denied.');
    }
}




    public function show(Drawings $drawing)
    {
        if (\Auth::user()->can('show working drawings')) {
             $drawing->load([
    'attachments' => function ($query) {
        $query->orderBy('id', 'desc'); // latest first
    }
]);
            return view('drawings.show', compact('drawing'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function fileUpload($id, Request $request)
    {
        
            $drawing = Drawings::find($id);
            $request->validate(['file' => 'required']);

            $file_path = 'drawing_attachment/';
            $image_size = $request->file('file')->getSize();

            $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
            if ($result == 1) {
                $files = $id . '_' . $request->file->getClientOriginalName();
                $dir = 'drawing_attachment/';

                try {
                    $path = Utility::upload_file($request, 'file', $files, $dir, []);

                    \Log::info('Upload Path Response', $path);

                    if (isset($path['flag']) && $path['flag'] == 1) {
                        $file = $path['url'];
                    } else {
                        return response()->json([
                            'is_success' => false,
                            'error' => $path['msg'] ?? 'Unknown upload error',
                        ], 400);
                    }
                } catch (\Exception $e) {
                    \Log::error('Upload Exception: ' . $e->getMessage());
                    return response()->json([
                        'is_success' => false,
                        'error' => 'Upload failed: ' . $e->getMessage(),
                    ], 500);
                }


                // Save in drawings_attachments table
                $attachment = \App\Models\DrawingsAttachments::create([
                    'drawing_id' => $drawing->id,
                    'files' => $files,
                ]);

                $return = [
                    'is_success' => true,
                    'download' => route('drawings.file.download', [$drawing->id, $attachment->id]),
                    'delete' => route('drawings.attachment.delete', [$drawing->id, $attachment->id]),
                ];

                return response()->json($return);
            }

            return response()->json(['is_success' => false, 'error' => __('Storage Limit Exceeded')], 400);
        }

      

    public function deleteAttachment($id)
{
     if (\Auth::user()->can('delete working drawings')) {
    $attachment = \App\Models\DrawingsAttachments::findOrFail($id);
    $filePath = storage_path('storage/drawing_attachment/' . $attachment->files);

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
