<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AutoDex;
use App\Models\AutoDexAttachments;
use App\Models\Project;
use App\Models\Utility;
use Illuminate\Support\Facades\Http;



class AutoDexController extends Controller
{
   public function index()
    {
         $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage autocad files', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage autocad files'))
    );

    if ($hasPermission) {
            $user = \Auth::user();

            $auto_dex = AutoDex::where('project_id', $user->project_assign_id)->orderBy('id', 'desc')
                ->with(['attachments' => function ($query) {
                   $query->orderBy('id', 'desc')->limit(3); // only fetch latest 3 attachments
                }])
                ->get();

            return view('auto_dex.index', compact('auto_dex'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

     public function create()
    {
        if (\Auth::user()->can('create autocad files')) {
            $user = \Auth::user();
            $projects = Project::where('created_by', \Auth::id())->where('id', $user->project_assign_id)->pluck('project_name', 'id');
            return view('auto_dex.create', compact('projects'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


public function store(Request $request)
{
    if (\Auth::user()->can('create autocad files')) {

        // Validate the request without project_id input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Get the project_id from the authenticated user's project_assign_id
        $project_id = \Auth::user()->project_assign_id; // assuming the project_assign_id exists on the User model

        if (!$project_id) {
            return redirect()->back()->with('error', __('Please create a project first before adding a Auto Dex.'));
        }

        // Create a new drawing record
        $auto_dex = new \App\Models\AutoDex();
        $auto_dex->project_id = $project_id; // Automatically assign project_id
        $auto_dex->name = $request->name;
        $auto_dex->created_by = \Auth::user()->id;
                $auto_dex->created_user = "Admin"; 

        // Save the drawing
        $auto_dex->save();

        // Redirect with a success message
        return redirect()->route('auto-desk.index')->with('success', __('Auto Dex Created Successfully.'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

    public function edit($id)
    {
        if (\Auth::user()->can('create autocad files')) {
            $auto_dex = \App\Models\AutoDex::findOrFail($id);

            $project_id = \Auth::user()->project_assign_id;

            return view('auto_dex.edit', compact('auto_dex'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('create autocad files')) {

            $auto_dex = \App\Models\AutoDex::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $project_id = \Auth::user()->project_assign_id;

            if (! $project_id) {
                return redirect()->back()->with('error', 'No project assigned to the user.');
            }

            $auto_dex->project_id = $project_id;
            $auto_dex->name = $request->name;
            $auto_dex->save();

            return redirect()->route('auto-desk.index')->with('success', __('Auto Dex Updated Successfully'));
        }

        return redirect()->back()->with('error', 'Permission Denied.');
    }
    
    

 public function show(AutoDex $auto_desk)
    {
        if (\Auth::user()->can('show autocad files')) {
            $auto_desk->load([
            'attachments' => function ($query) {
                $query->orderBy('id', 'desc'); // latest first (DESC)
            }
        ]);
            return view('auto_dex.show', compact('auto_desk'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

public function saveViewerUrl(Request $request)
{
    $request->validate([
        'id' => 'required|exists:auto_dex_attachments,id',
        'viewer_url' => 'required|url',
    ]);

    $attachment = AutoDexAttachments::findOrFail($request->id);
    $attachment->view_url = $request->viewer_url;
    $attachment->save();

    return response()->json(['success' => true]);
}


public function fileUploadAutoDex($id, Request $request)
{
   
        $auto_dex = AutoDex::find($id);

        // Validate only DWG files
       // Basic file presence validation
        $request->validate([
            'file' => 'required|file',
        ]);

        $file = $request->file('file');

        // ✅ Manually validate extension
        $allowedExtensions = [
              'dwg', 'dxf', 'ipt', 'f3d', 'f2d', 'stp',
              'iges', 'igs', 'smt', 'sat', 'sab',
              'obj', 'mtl', 'stl', 'fbx', '3dm',
              'x_t', 'x_b', 'rvt', 'rfa', 'rte',
              'nwd', 'nwc', 'dwf', 'dwfx', 'max'
         ];
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedExtensions)) {
            return response()->json([
                'is_success' => false,
                'error' => 'Only DWG and FBX files are allowed.'
            ], 422);
        }
        
        $originalName = $file->getClientOriginalName();
        $fileName = $id . '_' . $originalName;
        $path = 'autodex_attachment';

        // Upload to storage/app/public/autodex_attachment
        $storedPath = $file->storeAs($path, $fileName, 'local');

        // Send file to external API (using absolute path)
        $filePath = storage_path($storedPath);

        try {
            $response = Http::attach(
                'modelFile',
                file_get_contents($filePath),
                $fileName
            )->post('https://auto.onevans.com/api/models');

            if ($response->successful()) {
                $data = $response->json();
                $viewerUrl = $data['viewerUrl'] ?? null;
            } else {
                $viewerUrl = null;
            }
        } catch (\Exception $e) {
            // Log error for debugging
            $viewerUrl = null;
        }

        // Save in AutoDexAttachments table
        $attachment = \App\Models\AutoDexAttachments::create([
            'auto_dexes_id' => $auto_dex->id,
            'files' => $fileName,
            'created_by' => \Auth::user()->id,
            'view_url' => $viewerUrl, // Save viewerUrl here
            'created_user' => "Admin" 
        ]);

        // Generate download & delete links
        $downloadUrl = route('auto.dex.file.download', [$auto_dex->id, $attachment->id]);
        $deleteUrl = route('auto.dex.attachment.delete', [$auto_dex->id, $attachment->id]);

        return response()->json([
            'is_success' => true,
            'download' => $downloadUrl,
            'delete' => $deleteUrl,
            'viewer_url' => $viewerUrl, // Optional: return it for frontend
        ]);
   
}

// public function fileUploadAutoDex($id, Request $request)
// {
//     if (\Auth::user()->type == 'company' || \Auth::user()->type == 'client') {
//         $auto_dex = AutoDex::find($id);

//         // Validate only DWG files
//         $request->validate([
//             'file' => 'required|mimes:dwg',
//         ], [
//             'file.mimes' => 'Only DWG files are allowed.',
//         ]);

//         $file = $request->file('file');
//         $originalName = $file->getClientOriginalName();
//         $fileName = $id . '_' . $originalName;

//         // Log start
//         \Log::info('AutoDex API Upload STARTED', [
//             'file_name' => $fileName,
//             'user_id' => \Auth::id(),
//             'auto_dex_id' => $id,
//             'timestamp' => now()->toDateTimeString()
//         ]);

//         $viewerUrl = null;

//         try {
//             $response = Http::timeout(60)
//                 ->attach(
//                     'modelFile',                              // must match key expected by API
//                     fopen($file->getRealPath(), 'r'),         // use fopen() for reliable streaming
//                     $fileName
//                 )
//                 ->post('https://autodesk.1vans.in/api/models');

//             \Log::info('AutoDex API Upload Raw Response', [
//                 'response' => $response->body(),
//                 'status' => $response->status()
//             ]);

//             if ($response->successful()) {
//                 $data = $response->json();
//                 $viewerUrl = $data['viewerUrl'] ?? null;

//                 \Log::info('AutoDex API Upload SUCCESS', [
//                     'file_name' => $fileName,
//                     'viewer_url' => $viewerUrl,
//                     'user_id' => \Auth::id()
//                 ]);
//             } else {
//                 \Log::error('AutoDex API Upload FAILED', [
//                     'file_name' => $fileName,
//                     'status' => $response->status(),
//                     'body' => $response->body()
//                 ]);
//             }
//         } catch (\Exception $e) {
//             \Log::error('AutoDex Upload EXCEPTION', [
//                 'file_name' => $fileName,
//                 'error' => $e->getMessage()
//             ]);
//         }

//         if ($viewerUrl) {
//             \App\Models\AutoDexAttachments::create([
//                 'auto_dexes_id' => $auto_dex->id,
//                 'files' => null,
//                 'created_by' => \Auth::id(),
//                 'view_url' => $viewerUrl,
//             ]);
//         }

//         return response()->json([
//             'is_success' => (bool)$viewerUrl,
//             'viewer_url' => $viewerUrl,
//             'message' => $viewerUrl ? 'Upload and viewer URL generated successfully.' : 'Upload failed or viewer URL not generated.',
//         ]);
//     }

//     return response()->json([
//         'is_success' => false,
//         'error' => __('Permission Denied.')
//     ], 401);
// }

    public function autoDexdeleteAttachment($id)
{
    $attachment = \App\Models\AutoDexAttachments::findOrFail($id);
    $filePath = storage_path('storage/autodex_attachment/' . $attachment->files);

    if (file_exists($filePath)) {
        unlink($filePath);
    }

    $attachment->delete();

    return redirect()->back()->with('success', 'Attachment deleted successfully.');
}


}
