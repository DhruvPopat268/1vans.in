<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DailyReportController extends Controller
{
         public function index(Request $request)
    {
                $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage work reports', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage work reports'))
    );

    if ($hasPermission) {
        $user = \Auth::user();
         $mainCategoryId = $request->get('main_category_id');


           $dailyreportQuery = \App\Models\DailyReport::with([
            'nameOfWork.mainCategory',
            'manpowers.manPower',
            'materials.subCategory.category',
            'equipments.equipment',
            'measurements'
        ])->where('project_id', $user->project_assign_id);

        // Apply filter if selected
        if (!empty($mainCategoryId)) {
            $dailyreportQuery->whereHas('nameOfWork.mainCategory', function ($q) use ($mainCategoryId) {
                $q->where('id', $mainCategoryId);
            });
        }

        $dailyreport = $dailyreportQuery->get()->groupBy('name_of_work_id');

                $mainCategories = \App\Models\DailyReportMainCategory::where('project_id', $user->project_assign_id)->get();


        return view('dailyReport.index', compact('dailyreport','mainCategories','mainCategoryId'));
       } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
       }
    }

public function show(Request $request, $id)
{
    if (\Auth::user()->can('show work reports')) {
        $user = \Auth::user();

        // Get filter inputs
        $fromDate = $request->input('from_date');
        $toDate   = $request->input('to_date');

        // Base query
        $query = \App\Models\DailyReport::with([
            'manpowers.manPower',
            'materials.subCategory.category',
            'equipments.equipment',
            'nameOfWork','measurements'
        ])
        ->where('project_id', $user->project_assign_id)
        ->where('name_of_work_id', $id);

        // Apply date filter (assuming "date" column in DailyReport table)
        if ($fromDate && $toDate) {
            $query->whereBetween('date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->whereDate('date', '>=', $fromDate);
        } elseif ($toDate) {
            $query->whereDate('date', '<=', $toDate);
        }

        $reports = $query->orderBy('id', 'desc')->get();

        return view('dailyReport.show', compact('reports', 'fromDate', 'toDate','id'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function showDetail($id)
{
$report = \App\Models\DailyReport::with([
'manpowers.manPower',
'materials.subCategory.category',
'materials.subCategory.attribute',
'equipments.equipment',
'nameOfWork',
'measurements.attribute',
'dailyReportImage'
])->findOrFail($id);

return view('dailyReport.details', compact('report'));
}

public function downloaReportdPdf($id)
{
    $report = \App\Models\DailyReport::with([
        'nameOfWork',
        'manpowers.manPower',
        'materials.subCategory.attribute',
        'measurements.attribute',
        'equipments.equipment',
        'dailyReportImage',
        'project',
        'user'
    ])->findOrFail($id);

    // ✅ Signature
    $signaturePath = storage_path(($report->signature ?? 'abcd.png'));
    $signatureImg = file_exists($signaturePath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($signaturePath))
        : '';

     // User avatar
        // $user = \Illuminate\Support\Facades\Auth::user();
        // $avatarUrl = $user->avatar ?? null;

        // $profilePath = $avatarUrl
        //     ? storage_path('uploads/avatar/' . str_replace('/storage/', '', parse_url($avatarUrl, PHP_URL_PATH)))
        //     : storage_path('uploads/avatar/abcd.png');

        // $profileImg = file_exists($profilePath)
        //     ? 'data:image/png;base64,' . base64_encode(file_get_contents($profilePath))
        //     : '';

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

//         $Images = [];
// foreach ($report->dailyReportImage as $image) {
//     $imagePath = storage_path($image->image_path);
//     if (file_exists($imagePath)) {
//         $Images[] = 'data:image/png;base64,' . base64_encode(file_get_contents($imagePath));
//     }
// }

    $Images = [];
    foreach ($report->dailyReportImage as $image) {
        $imagePath = storage_path($image->image_path);
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
            $Images[] = 'data:image/jpeg;base64,' . base64_encode($compressedImageData);

                // Free memory
                imagedestroy($originalImage);
                imagedestroy($resizedImage);
            }
        }
    }

    // ✅ Generate PDF
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dailyReport.pdf', compact('report', 'profileImg', 'signatureImg', 'Images'));

    return $pdf->download('Construction Daily Work Progress Report.pdf');
}


public function allreportindex(Request $request)
{
            $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage all reports', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage all reports'))
    );

    if ($hasPermission) {
        $user = \Auth::user();

        $query = \App\Models\DailyReport::with([
                'nameOfWork',
                'manpowers.manPower',
                'materials.subCategory.category',
                'equipments.equipment',
                'flour',
                'wing',
            ])
            ->where('project_id', $user->project_assign_id)->orderBy('id', 'desc');

        // Apply filters
        if ($request->filled('name_of_work_id')) {
            $query->where('name_of_work_id', $request->name_of_work_id);
        }

        if ($request->filled('flour_id')) {
            $query->where('flour_id', $request->flour_id);
        }

        if ($request->filled('wing_id')) {
            $query->where('wing_id', $request->wing_id);
        }

        $dailyreport = $query->get();

     $wings = \App\Models\wing::where('project_id', $user->project_assign_id)->get();
$wingIds = $wings->pluck('id')->toArray();

$nameOfWorks = \App\Models\NameOfWork::where('project_id', $user->project_assign_id)->get();

// Load flours only if a specific wing is selected
$flours = collect(); // empty collection by default
if ($request->filled('wing_id')) {
    $flours = \App\Models\Flour::where('wing_id', $request->wing_id)->get();
}




        return view('dailyReport.allreport', compact('dailyreport', 'nameOfWorks', 'flours', 'wings'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function getFloursByWing($wing_id)
{
    $flours = \App\Models\Flour::where('wing_id', $wing_id)->get();

    return response()->json($flours);
}





}
