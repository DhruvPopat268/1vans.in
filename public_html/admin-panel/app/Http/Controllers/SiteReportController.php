<?php

namespace App\Http\Controllers;

use App\Models\SiteReport;
use Illuminate\Http\Request;

class SiteReportController extends Controller
{
    public function index(Request $request)
    {
        $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);
        

        // Permission check for both company & other roles
        $hasPermission = (
            ($user->type === 'company' && in_array('manage site report', $webAccess)) ||
            ($user->type !== 'company' && $user->can('manage site report'))
        );

        if ($hasPermission) {
            $user = \Auth::user();

            $query = SiteReport::with('user')
        ->where('project_id', $user->project_assign_id);

    // ✅ Date range filter
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('date', [
            $request->from_date,
            $request->to_date
        ]);
    }

    $sitereport = $query->orderByDesc('date')->get();

            return view('sitereport.index', compact('sitereport'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

        public function show($id)

    {
        $user = \Auth::user();
        $webAccess = is_array($user->web_access)
            ? $user->web_access
            : json_decode($user->web_access ?? '[]', true);

        // ✅ Same permission logic as index()
        $hasPermission = (
            ($user->type === 'company' && in_array('manage site report', $webAccess)) ||
            ($user->type !== 'company' && $user->can('manage site report'))
        );

        if ($hasPermission) {
            $sitereport = SiteReport::with('attachments')->findOrFail($id);

            return view('sitereport.show', compact('sitereport'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    
    public function downloadSiteReportPdf($id)
{
    $sitereport = \App\Models\SiteReport::with(['attachments','project','user'])->findOrFail($id);

    $user = $sitereport->user;
    $project = $sitereport->project;

    $pdfLogo = $project->pdf_logo ?? null;

    if ($pdfLogo) {
        $logoPath = storage_path('uploads/pdf_logo/'.$pdfLogo);
    } else {
        $company_logo = \App\Models\Utility::getValByName('company_logo');
        $fallbackLogo = ! empty($company_logo) ? $company_logo : 'logo-dark.png';
        $logoPath = storage_path('uploads/logo/'.$fallbackLogo);
    }

    if (file_exists($logoPath)) {
        $profileImg = 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath));
    } else {
        $profileImg = '';
    }

    // ✅ FIX HERE
    $sitereportImages = [];
    foreach ($sitereport->attachments as $image) {
        $imagePath = storage_path($image->files);
        if (file_exists($imagePath)) {
            $sitereportImages[] = 'data:image/png;base64,'.base64_encode(file_get_contents($imagePath));
        }
    }
    
       $sitereportImages = [];
    foreach ($sitereport->attachments as $image) {
        $imagePath = storage_path($image->files);
        if (file_exists($imagePath)) {
            $imageData = file_get_contents($imagePath);
            $originalImage = @imagecreatefromstring($imageData);

            if ($originalImage !== false) {
                // 🔄 Read EXIF orientation
                $exif = @exif_read_data($imagePath);
                if (!empty($exif['Orientation'])) {
                    switch ($exif['Orientation']) {
                        case 3:
                            $originalImage = imagerotate($originalImage, 180, 0);
                            break;
                        case 6:
                            $originalImage = imagerotate($originalImage, -90, 0);
                            break;
                        case 8:
                            $originalImage = imagerotate($originalImage, 90, 0);
                            break;
                    }
                }

                // 📏 Resize to width = 800px (maintain ratio)
                $originalWidth = imagesx($originalImage);
                $originalHeight = imagesy($originalImage);
                $newWidth = 800;
                $newHeight = intval(($originalHeight / $originalWidth) * $newWidth);

                $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
                imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);


                // 💾 Compress image to JPEG (quality 50)
                ob_start();
            imagejpeg($resizedImage, null, 50); // 50 = medium compression
            $compressedImageData = ob_get_clean();
                // 🧠 Add to array as base64
            $sitereportImages[] = 'data:image/jpeg;base64,' . base64_encode($compressedImageData);

                // Free memory
                imagedestroy($originalImage);
                imagedestroy($resizedImage);
            }
        }
    }

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'sitereport.pdf',
        compact('sitereport', 'profileImg', 'sitereportImages')
    );

    return $pdf->download('Site Report.pdf');
}
}
