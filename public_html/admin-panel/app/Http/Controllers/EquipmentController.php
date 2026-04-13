<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Drawings;
use App\Models\Equipment;
use App\Models\EquipmentFormItem;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage types of equipment', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage types of equipment'))
    );

    if ($hasPermission) {
            $user = \Auth::user();

            $equipment = \App\Models\Equipment::where('project_id', $user->project_assign_id)
                ->get();

            return view('equipment.index', compact('equipment'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create types of equipment')) {
            $user = \Auth::user();
            $projects = Project::where('created_by', \Auth::id())->where('id', $user->project_assign_id)->pluck('project_name', 'id');
            return view('equipment.create', compact('projects'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


public function store(Request $request)
{
    if (\Auth::user()->can('create types of equipment')) {

        // Validate the request without project_id input
        $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'nullable|string', // allow image/pdf up to 2MB
        ]);

        // Get the project_id from the authenticated user's project_assign_id
        $project_id = \Auth::user()->project_assign_id; // assuming the project_assign_id exists on the User model

        if (!$project_id) {
            return redirect()->back()->with('error', __('Please create a project first before adding a Equipments.'));
        }

        // Create a new drawing record
        $equipments = new \App\Models\Equipment();
        $equipments->project_id = $project_id; // Automatically assign project_id
        $equipments->name = $request->name;
        $equipments->rate = $request->rate;
        $equipments->created_by = \Auth::user()->id;


        // Save the drawing
        $equipments->save();

        // Redirect with a success message
        return redirect()->route('equipment.index')->with('success', __('Equipments Created Successfully.'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}


public function edit(Equipment $equipment)
{
    if(\Auth::user()->can('edit types of equipment'))
    {

        return view('equipment.edit',compact('equipment'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function update(Request $request, $id)
{
    if (\Auth::user()->can('edit types of equipment')) {
        $equipment = \App\Models\Equipment::find($id);

        if (!$equipment) {
            return redirect()->back()->with('error' ,'Equipment not found.');
        }

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'nullable|string', // allow image/pdf up to 2MB
        ]);


        $equipment->name = $request->name;
        $equipment->rate = $request->rate;



        $equipment->save();

        return redirect()->route('equipment.index')->with('success', __('Equipment Updated Successfully'));
    } else {
        return redirect()->back()->with('error' , 'Permission Denied.');
    }
}




    public function show(Equipment $equipment)
    {
        if (\Auth::user()->can('show types of equipment')) {

            return view('equipment.show', compact('equipment'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    
public function historyindex()
{
             $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage equipments summary', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage equipments summary'))
    );

    if ($hasPermission) {
        $user = \Auth::user();

        $items = EquipmentFormItem::with('equipment')
            ->whereHas('equipment', function ($query) use ($user) {
                $query->where('project_id', $user->project_assign_id);
            })
            ->selectRaw('equipment_id, SUM(total_hours) as total_hours, SUM(total_amount) as total_amount, MAX(rate) as rate')
            ->groupBy('equipment_id')
            ->get();

        return view('equipment.history.index', compact('items'));
    }

    return redirect()->back()->with('error', __('Permission Denied.'));
}


public function historyshow($equipment_id)
{
    if (\Auth::user()->can('show equipments summary')) {
    $items = EquipmentFormItem::where('equipment_id', $equipment_id)
        ->orderBy('date')
        ->get();

    $equipment = Equipment::findOrFail($equipment_id);

    return view('equipment.history.show', compact('equipment', 'items'));
    }

    return redirect()->back()->with('error', __('Permission Denied.'));
}

public function reportindex()
{
             $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage equipments reports', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage equipments reports'))
    );

    if ($hasPermission) {
        $user = \Auth::user();

        // Fetch all forms related to the user's assigned project
        $forms = \App\Models\EquipmentForm::with(['items', 'user'])
            ->where('project_id', $user->project_assign_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('equipment.report.index', compact('forms'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}


public function reportshow($id)
{
    if (\Auth::user()->can('show equipments reports')) {
    $form = \App\Models\EquipmentForm::with(['items.equipment', 'user'])->findOrFail($id);
    return view('equipment.report.show', compact('form'));
    }

    return redirect()->back()->with('error', __('Permission Denied.'));
}
public function downloadPdf($id)
{
     if (\Auth::user()->can('download equipments reports')) {
    // Fetch the form with the necessary relations
    $form = \App\Models\EquipmentForm::with(['items.equipment', 'user'])->findOrFail($id);

    // Fetch signature image
 $signaturePath = storage_path(($form->signature ?? 'abcd.png'));
 if (file_exists($signaturePath)) {
     $signatureData = base64_encode(file_get_contents($signaturePath));
     $signatureImg = 'data:image/png;base64,' . $signatureData;
 } else {
    //  \Log::error('Signature file does not exist: ' . $signaturePath);
     $signatureImg = ''; // Fallback image
 }

//  $user = Auth::user(); // or auth()->user();
//  $avatarUrl = $user->avatar ?? null;

//  if ($avatarUrl) {
//      $avatarRelativePath = str_replace('/storage/', '', parse_url($avatarUrl, PHP_URL_PATH));
//      $profilePath = storage_path('uploads/avatar/' . $avatarRelativePath);
//  } else {
//      $profilePath = storage_path('uploads/avatar/abcd.png'); // fallback image path
//  }

//  if (file_exists($profilePath)) {
//     $profileData = base64_encode(file_get_contents($profilePath));
//     $profileImg = 'data:image/png;base64,' . $profileData;
// } else {
//     // \Log::error('Avatar file does not exist: ' . $profilePath);
//     $profileImg = ''; // Fallback
// }

$user = $form->user; // Fallback when Auth::user() is unavailable

// Get pdf_logo directly from logged-in user
$project = $form->project; // not nameOfWork if this is correct relation
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




    // Load the PDF view with the form and signature URL data
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('equipment.report.pdf', compact('form', 'signatureImg','profileImg'));

    // Stream the PDF to the browser
    return $pdf->download('Equipment Report' . '.pdf');
     }

    return redirect()->back()->with('error', __('Permission Denied.'));
}

public function downloadPdfApplication($id)
{
    // Fetch the form with the necessary relations
    $form = \App\Models\EquipmentForm::with(['items.equipment', 'user'])->findOrFail($id);

    // Fetch signature image
 $signaturePath = storage_path(($form->signature ?? 'abcd.png'));
 if (file_exists($signaturePath)) {
     $signatureData = base64_encode(file_get_contents($signaturePath));
     $signatureImg = 'data:image/png;base64,' . $signatureData;
 } else {
    //  \Log::error('Signature file does not exist: ' . $signaturePath);
     $signatureImg = ''; // Fallback image
 }

//  $user = Auth::user(); // or auth()->user();
//  $avatarUrl = $user->avatar ?? null;

//  if ($avatarUrl) {
//      $avatarRelativePath = str_replace('/storage/', '', parse_url($avatarUrl, PHP_URL_PATH));
//      $profilePath = storage_path('uploads/avatar/' . $avatarRelativePath);
//  } else {
//      $profilePath = storage_path('uploads/avatar/abcd.png'); // fallback image path
//  }

//  if (file_exists($profilePath)) {
//     $profileData = base64_encode(file_get_contents($profilePath));
//     $profileImg = 'data:image/png;base64,' . $profileData;
// } else {
//     // \Log::error('Avatar file does not exist: ' . $profilePath);
//     $profileImg = ''; // Fallback
// }

$user = $form->user; // Fallback when Auth::user() is unavailable

// Get pdf_logo directly from logged-in user
$project = $form->project; // not nameOfWork if this is correct relation
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




    // Load the PDF view with the form and signature URL data
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('equipment.report.pdf', compact('form', 'signatureImg','profileImg'));

    // Stream the PDF to the browser
    return $pdf->download('Equipment Report' . '.pdf');
    
}

public function reportcreate()
{
    if (\Auth::user()->can('create equipments reports')) {
        $projectId = \Auth::user()->project_assign_id;

        $equipments = \App\Models\Equipment::where('project_id', $projectId)->get();

        return view('equipment.report.create', compact('equipments'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}


public function reportstore(Request $request)
{
    if (\Auth::user()->can('create equipments reports')) {
        $validated = $request->validate([
           'location' => 'required|string|max:255',
    'description' => 'required|string',
    'equipment.*.equipment_id' => 'required|exists:equipment,id',
    'equipment.*.start_time' => 'required|date_format:H:i',
    'equipment.*.end_time' => 'required|date_format:H:i|after:equipment.*.start_time',

        ]);

        $form = new \App\Models\EquipmentForm();
        $form->location = $request->location;
        $form->description = $request->description;
        $form->created_by = \Auth::id();
        $form->project_id = \Auth::user()->project_assign_id;

        $form->save();

        foreach ($request->equipment as $item) {
            $equipment = \App\Models\Equipment::find($item['equipment_id']);

            // Combine today's date with time
            $today = now()->format('Y-m-d');
            $start = \Carbon\Carbon::parse("{$today} {$item['start_time']}");
            $end = \Carbon\Carbon::parse("{$today} {$item['end_time']}");
            
             if ($end->lessThan($start)) {
        $end->addDay(); // Handle overnight
    }

    $diffInMinutes = $start->diffInMinutes($end);
    $decimalHours = round($diffInMinutes / 60, 2); // This is the value you'll store: e.g. 2.5

            $rate = $equipment->rate;

            $form->items()->create([
                'equipment_id' => $item['equipment_id'],
                'start_time' => $item['start_time'],
                'end_time' => $item['end_time'],
                'total_hours' => $decimalHours, // Save 2.0 format
                'rate' => $rate,
                'total_amount' => $decimalHours * $rate,
                'date' => $today, // Save today's date
            ]);
        }



        return redirect()->route('equipment.report.index')->with('success', __('Report created successfully.'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}
}
