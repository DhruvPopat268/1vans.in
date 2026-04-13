<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WorkIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class WorkIssueController extends Controller
{
    public function index()
    {
                         $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage work issue', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage work issue'))
    );

    if ($hasPermission) {
            $user = \Auth::user();

            $workissue = \App\Models\WorkIssue::where('project_id', $user->project_assign_id)->orderBy('date', 'desc')->get();

            return view('workissue.index', compact('workissue'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function show($id)
    {
         if(\Auth::user()->can('show work issue'))
    {
        $workissue = \App\Models\WorkIssue::with(['user',
        'workissueImage'
        ])->findOrFail($id);
        return view('workissue.show', compact('workissue'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
    }
        public function edit(WorkIssue $work_issue)
{
    if(\Auth::user()->can('edit work issue'))
    {

        return view('workissue.edit',compact('work_issue'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function update(Request $request, $id)
{
    if (\Auth::user()->can('edit work issue')) {
        $work_issue = \App\Models\WorkIssue::find($id);

        if (!$work_issue) {
            return redirect()->back()->with('error' ,'Work Issue not found.');
        }

        // Validate input
        $request->validate([
            'status' => 'required|string|max:255',
        ]);

        // Get user's assigned project
        $project_id = \Auth::user()->project_assign_id;

        if (!$project_id) {
            return redirect()->back()->with('error', 'No project assigned to the user.');
        }

        // Update drawing data
        $work_issue->project_id = $project_id;
        $work_issue->status = $request->status;
        $work_issue->save();

        return redirect()->route('work-issue.index')->with('success', __('Status Updated Successfully'));
    } else {
        return redirect()->back()->with('error' , 'Permission Denied.');
    }
}
public function downloaWorkIssuedPdf($id)
{
if (\Auth::user()->can('download work issue')) {
    $report = \App\Models\WorkIssue::findOrFail($id);


  
        
       $user = $report->user; // Fallback when Auth::user() is unavailable

// Get pdf_logo directly from logged-in user
$project = $report->project; // not nameOfWork if this is correct relation
$pdfLogo = $project->pdf_logo ?? null;

if ($pdfLogo) {
    $logoPath = storage_path('uploads/pdf_logo/' . $pdfLogo);
   // \Log::info("Using project pdf_logo: " . $pdfLogo);
} else {
    $company_logo = \App\Models\Utility::getValByName('company_logo');
    $fallbackLogo = !empty($company_logo) ? $company_logo : 'logo-dark.png';
    $logoPath = storage_path('uploads/logo/' . $fallbackLogo);
   // \Log::info("Using fallback company logo: " . $fallbackLogo);
}

if (file_exists($logoPath)) {
    $imageData = base64_encode(file_get_contents($logoPath));
    $profileImg = 'data:image/png;base64,' . $imageData;
   // \Log::info("Logo image loaded: " . $logoPath);
} else {
  //  \Log::error("Logo file missing: " . $logoPath);
    $profileImg = '';
}


$Images = [];
foreach ($report->workissueImage as $image) {
    $imagePath = storage_path($image->image_path);
    if (file_exists($imagePath)) {
        // Load image
        $originalImage = imagecreatefromstring(file_get_contents($imagePath));
        if ($originalImage) {
            // Resize to width = 800px (preserving aspect ratio)
            $originalWidth = imagesx($originalImage);
            $originalHeight = imagesy($originalImage);
            $newWidth = 800;
            $newHeight = intval(($originalHeight / $originalWidth) * $newWidth);

            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

            // Output to buffer as JPEG with quality 50
            ob_start();
            imagejpeg($resizedImage, null, 50); // 50 = medium compression
            $compressedImageData = ob_get_clean();

            $Images[] = 'data:image/jpeg;base64,' . base64_encode($compressedImageData);

            // Free memory
            imagedestroy($originalImage);
            imagedestroy($resizedImage);
        }
    }
}


    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('workissue.pdf', compact('report','profileImg','Images'));
    return $pdf->download('Work Issue.pdf');
} else {
        return redirect()->back()->with('error' , 'Permission Denied.');
    }
}
public function downloaWorkIssuedPdfApplication($id)
{

    $report = \App\Models\WorkIssue::findOrFail($id);


  
        
       $user = $report->user; // Fallback when Auth::user() is unavailable

// Get pdf_logo directly from logged-in user
$project = $report->project; // not nameOfWork if this is correct relation
$pdfLogo = $project->pdf_logo ?? null;

if ($pdfLogo) {
    $logoPath = storage_path('uploads/pdf_logo/' . $pdfLogo);
   // \Log::info("Using project pdf_logo: " . $pdfLogo);
} else {
    $company_logo = \App\Models\Utility::getValByName('company_logo');
    $fallbackLogo = !empty($company_logo) ? $company_logo : 'logo-dark.png';
    $logoPath = storage_path('uploads/logo/' . $fallbackLogo);
   // \Log::info("Using fallback company logo: " . $fallbackLogo);
}

if (file_exists($logoPath)) {
    $imageData = base64_encode(file_get_contents($logoPath));
    $profileImg = 'data:image/png;base64,' . $imageData;
   // \Log::info("Logo image loaded: " . $logoPath);
} else {
  //  \Log::error("Logo file missing: " . $logoPath);
    $profileImg = '';
}


$Images = [];
foreach ($report->workissueImage as $image) {
    $imagePath = storage_path($image->image_path);
    if (file_exists($imagePath)) {
        // Load image
        $originalImage = imagecreatefromstring(file_get_contents($imagePath));
        if ($originalImage) {
            // Resize to width = 800px (preserving aspect ratio)
            $originalWidth = imagesx($originalImage);
            $originalHeight = imagesy($originalImage);
            $newWidth = 800;
            $newHeight = intval(($originalHeight / $originalWidth) * $newWidth);

            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

            // Output to buffer as JPEG with quality 50
            ob_start();
            imagejpeg($resizedImage, null, 50); // 50 = medium compression
            $compressedImageData = ob_get_clean();

            $Images[] = 'data:image/jpeg;base64,' . base64_encode($compressedImageData);

            // Free memory
            imagedestroy($originalImage);
            imagedestroy($resizedImage);
        }
    }
}


    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('workissue.pdf', compact('report','profileImg','Images'));
    return $pdf->download('Work Issue.pdf');
}
}
