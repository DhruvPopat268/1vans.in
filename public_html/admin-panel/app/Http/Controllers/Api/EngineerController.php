<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentForm;
use App\Models\EquipmentFormItem;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\AppVersion;
use Carbon\Carbon;
use App\Models\EngineerAttendances;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EngineerController extends Controller
{

public function getUserProjects(Request $request)
{
    $user = Auth::user();

    // Decode project_id array
    $projectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    // Fetch and transform projects
    $projects = Project::whereIn('id', $projectIds)
        ->select([
            'id',
            'project_name',
            'start_date',
            'end_date',
            'project_image',
            'budget',
            'description',
            'status',
            'site_address',
            'latitude',
            'longitude',
            'crite_area'
        ])
        ->get()
        ->map(function ($project) {
            return [
                'id' => $project->id,
                'name' => $project->project_name,
                'startDate' => $project->start_date,
                'endDate' => $project->end_date,
                'imageUrl' => $project->project_image
                    ? asset(Storage::url($project->project_image))
                    : null,
                'budget' => $project->budget,
                'description' => $project->description,
                'status' => $project->status,
                'location' => $project->site_address,
                'latitude' => $project->latitude,
                'longitude' => $project->longitude,
                'crite_area' => $project->crite_area,
            ];
        });
        

    $notificationCount = 0;
        $notificationCount = \App\Models\EngineerNotification::where('engineer_id', $user->id)
            ->where('status', 'Pending') // Only unread or active
            ->count();
            
             $androidVersion = AppVersion::where('type', 'android')->first();
$iosVersion     = AppVersion::where('type', 'ios')->first();

$maintenanceMode = false;

if (
    optional($androidVersion)->maintenance_mode ||
    optional($iosVersion)->maintenance_mode
) {
    $maintenanceMode = true;
}
    

    return response()->json([
        'status' => 1,
       //  'app_version' => "1.0.19",
        // 'ios_version' => "1.18",
          'app_version' => optional($androidVersion)->version,
        'ios_version'     => optional($iosVersion)->version,

        // 🔥 one maintenance flag for both
         'maintenance_mode' => (bool) $maintenanceMode,
                 'engineer_notification_count' => $notificationCount,
        'projects' => $projects
    ]);
}

public function getEquipmentsByProject(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer',
    ]);

    $user = Auth::user();

    // Decode user project_id array (assumes JSON stored)
    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    // Check if the given project_id is assigned to the user
    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    // Fetch equipment by project_id
    $equipments = Equipment::where('project_id', $request->project_id)
        ->select('id','name', 'rate')->orderBy('id', 'DESC')
        ->get();

    return response()->json([
        'status' => 1,
        'equipments' => $equipments
    ]);
}

public function getUserAccess()
{
    $user = Auth::user();
    
 if (!$user) {
        return response()->json([
            'status' => 0,
            'error' => 'Unauthenticated access. Please log in.'
        ], 401);
    }

    // Decode user_access if stored as JSON
    $access = is_array($user->user_access)
        ? $user->user_access
        : json_decode($user->user_access, true);

    // Map of name => [id, image]
    $accessMap = [
        'Daily Reports' => ['id' => 1, 'image' => 'Daily_Reports.png'],
        'Project Documents' => ['id' => 2, 'image' => 'Project_Document.png'],
        'Working Drawings' => ['id' => 3, 'image' => 'Drawing_Reports.png'],
        'Work Issues' => ['id' => 4, 'image' => 'Work_Issues.png'],
        'Testing Reports' => ['id' => 5, 'image' => 'Testing_Reports.png'],
        'Equipment Log' => ['id' => 6, 'image' => 'Equipment_Reports.png'],
        'BOQ & R.A Bill' => ['id' => 7, 'image' => 'BOQ_Document.png'],
        'Material Order' => ['id' => 8, 'image' => 'Purchase_Order.png'],
        'Material Incoming' => ['id' => 9, 'image' => 'Material_Incoming.png'],
        'Site Gallery' => ['id' => 10, 'image' => 'Site_Gallery.png'],
        'To-Do List' => ['id' => 11, 'image' => 'ToDo_list.png'],
        'AutoCAD Files' => ['id' => 12, 'image' => 'Auto_desk.png'],
         'Attendance' => ['id' => 13, 'image' => 'attendance.png'],
         'Site Reports' => ['id' => 14, 'image' => 'site_report.png'],
    ];

    $accessList = [];

    foreach ($access as $name) {
        $entry = $accessMap[$name] ?? ['id' => 0, 'image' => 'default.png'];
        $imageUrl = Storage::url('work_report_icon/' . $entry['image']);

        $accessList[] = [
            'id' => $entry['id'],
            'name' => $name,
            'image' => $imageUrl,
        ];
    }

    return response()->json([
        'status' => 1,
        'client_visibility' => $user->client_visibility ? 1 : 0,
        'access' => $accessList
    ]);
}

public function storeFormBasic(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer',
        'location' => 'required|string',
        'description' => 'nullable|string',
        'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
    ]);

    $user = Auth::user();
    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $signaturePath = null;
    if ($request->hasFile('signature')) {
        $signaturePath = $request->file('signature')->store('form_signatures', 'local');
    }

    $form = EquipmentForm::create([
        'project_id' => $request->project_id,
        'location' => $request->location,
        'description' => $request->description,
        'signature' => $signaturePath,
        'created_by' => $user->id,
    ]);
    
     // ✅ Notification Logic
    if ($user->created_by) {
        $creator = \App\Models\User::find($user->created_by);
        if ($creator && $creator->web_player_id) {

            // Get project name
            $project = \App\Models\Project::find($request->project_id);
            $projectName = $project->project_name ?? 'Unknown';
            
            $title = 'Equipment Report Submitted by ' . $user->name;

$formattedDate = \Carbon\Carbon::today()->format('d-m-Y');
$projectName = $project->project_name ?? 'N/A';
$location = $request->location ?? 'N/A';

$message = "Date - $formattedDate\nProject - $projectName\nLocation - $location";


            // Optional: save notification
            \App\Models\WebNotification::create([
                'project_id'  => $request->project_id,
                'engineer_id' => $user->id,
                'title'       => $title,
                'message'     => $message,
                'status'      => 'Pending',
                'key'         => 3, // You can change this key to indicate different types,
                'report_id'   => $form->id
            ]);

            // Send push notification
            $this->sendWebPushNotification($title, $message, $creator->web_player_id);
        }
    }

    return response()->json([
        'status' => 1,
        'message' => 'Basic form saved successfully.',
        'form_id' => $form->id
    ]);
}





public function storeFormDetails(Request $request)
{
    $request->validate([
        'form_id' => 'required|exists:equipment_forms,id',
        'equipment_id' => 'required|integer|exists:equipment,id',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
        'total_hours' => 'required|numeric',
    ]);

    $form = EquipmentForm::findOrFail($request->form_id);
    $equipment = Equipment::findOrFail($request->equipment_id);
    $rate = $equipment->rate ?? 0;

    // Use total_hours from input
    $totalHours = floatval($request->total_hours);
    $totalAmount = $rate * $totalHours;

    EquipmentFormItem::updateOrCreate(
        [
            'equipment_form_id' => $form->id,
            'equipment_id' => $request->equipment_id,
            'date' => Carbon::today()->toDateString(), // include date in unique condition if you want entries per day
        ],
        [
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'rate' => $equipment->rate,
            'total_hours' => $request->total_hours,
            'total_amount' => $totalAmount,
            'date' => Carbon::today()->toDateString(), // Save today's date
        ]
    );

    return response()->json([
        'status' => 1,
        'message' => 'Equipment item saved successfully.'
    ]);
}

public function fetchFormDataByProject(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
    ]);

    $forms = EquipmentForm::where('project_id', $request->project_id)
        ->with(['user', 'items.equipment'])->orderBy('id', 'DESC') // Assuming relations exist
        ->get();

    $data = $forms->map(function ($form) {
        $equipmentDetails = $form->items->map(function ($item) {
            $hours = floor($item->total_hours);
            $minutes = round(($item->total_hours - $hours) * 60);

            $hoursLabel = $hours . ' Hour' . ($hours != 1 ? 's' : '');
            $minutesLabel = $minutes . ' Minute' . ($minutes != 1 ? 's' : '');

            return "{$item->equipment->name} - {$hoursLabel} {$minutesLabel}";
        })->implode(', '); // comma-separated list

        return [
            'project_id'      => $form->project_id,
            'date'            => $form->created_at->toDateString(),
            'pdf_file_url'    => url("api/equipment/form/pdf/{$form->id}"),
            'created_by_name' => $form->user->name ?? '',
            'equipment_list'  => $equipmentDetails,
        ];
    });

    return response()->json([
        'status' => 1,
        'data' => $data,
    ]);
}

public function getMaterialCategoryByProject(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer',
    ]);

    $user = Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $materialCategory = \App\Models\MaterialCategory::where('project_id', $request->project_id)
        ->select('id', 'name')
        ->get()
        ->map(function ($item) {
            return [
                'category_id' => $item->id,
                'name' => $item->name,
            ];
        });

    return response()->json([
        'status' => 1,
        'material_category' => $materialCategory
    ]);
}

public function getMaterialSubCategoryByCategory(Request $request)
{
    $request->validate([
        'category_id' => 'required|integer',
    ]);

    $subCategories = \App\Models\MaterialSubCategory::with('attribute')->where('status', 'Active') // eager-load attributes
        ->where('category_id', $request->category_id)
        ->get()
        ->map(function ($sub) {
            $availableStock = ($sub->total_stock ?? 0) - ($sub->used_stock ?? 0);
            return [
                'subcategory_id' => $sub->id,
                'name' => $sub->name,
                'attribute' => $sub->attribute->name,
                'available_stock' => $availableStock,
            ];
        });

    return response()->json([
        'status' => 1,
        'material_subcategory' => $subCategories
    ]);
}


public function storeMaterialFormBasic(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer',
        'category_id' => 'required|integer',
        'challan_number' => 'nullable|string',
         'location' => 'nullable|string',
        'bill_number' => 'nullable|string',
        'vehicle_number' => 'nullable|string',
        'description' => 'nullable|string',
        'vendor_name' => 'nullable|string',
        'remark' => 'nullable|string',
        'gst_number' => 'nullable|string',
        'batch_number' => 'nullable|string',
        'eway_bill_no' => 'nullable|string',
        'eway_bill_file' => 'nullable|mimes:jpeg,png,jpg,pdf,doc,docx|max:10240',
        'royalty_slip_no' => 'nullable|string',
        'royalty_slip_file' => 'nullable|mimes:jpeg,png,jpg,pdf,doc,docx|max:10240',
        'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:512000',
        'images' => 'nullable|array',
         'issue_status' => 'nullable|string',
        'comment' => 'nullable|string',

    ]);

    $user = Auth::user();
    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $signaturePath = null;
    if ($request->hasFile('signature')) {
        $signaturePath = $request->file('signature')->store('material/incoming_signatures', 'local');
    }

    $ewayBillPath = null;
if ($request->hasFile('eway_bill_file')) {
    $ewayBillPath = $request->file('eway_bill_file')->store('material/eway_bills', 'local');
}

$royaltySlipPath = null;
if ($request->hasFile('royalty_slip_file')) {
    $royaltySlipPath = $request->file('royalty_slip_file')->store('material/royalty_slips', 'local');
}


    $imagePaths = [];

if ($request->hasFile('images')) {
    foreach ($request->file('images') as $image) {
        $path = $image->store('material/incoming_images', 'local');
        $imagePaths[] = $path;
    }
}


    $form = \App\Models\MaterialIncoming::create([
        'project_id' => $request->project_id,
        'category_id' => $request->category_id,
        'challan_number' => $request->challan_number,
        'location' => $request->location,
        'bill_number' => $request->bill_number,
        'vehicle_number' => $request->vehicle_number,
        'description' => $request->description,
        'vendor_name' => $request->vendor_name,
        'remark' => $request->remark,
        'gst_number' => $request->gst_number,
        'batch_number' => $request->batch_number,
        'eway_bill_no' => $request->eway_bill_no,
        'eway_bill_file' => $ewayBillPath,
        'royalty_slip_no' => $request->royalty_slip_no,
        'royalty_slip_file' => $royaltySlipPath,
        'signature' => $signaturePath,
        'date' => \Carbon\Carbon::today()->toDateString(),
        'issue_status' => $request->issue_status,
        'comment' => $request->comment,
        'created_by' => $user->id,
    ]);

    foreach ($imagePaths as $path) {
        \App\Models\MaterialIncomingImages::create([
            'material_incomings_id' => $form->id,
            'image_path' => $path,
        ]);
    }
    
     if ($user->created_by) {
        $creator = \App\Models\User::find($user->created_by);
        if ($creator && $creator->web_player_id) {

            $project = \App\Models\Project::find($request->project_id);
            $projectName = $project->project_name ?? 'Unknown';

            
            $title = 'Material Incoming Report Submitted by ' . $user->name;

$formattedDate = \Carbon\Carbon::today()->format('d-m-Y');
$projectName = $project->project_name ?? 'N/A';
$location = $request->location ?? 'N/A';

$message = "Date - $formattedDate\nProject - $projectName\nLocation - $location";


            // Optional: Save to WebNotification table
            \App\Models\WebNotification::create([
                'project_id'  => $request->project_id,
                'engineer_id' => $user->id,
                'title'       => $title,
                'message'     => $message,
                'status'      => 'Pending',
                'key'         => 6, // Notification key for purchase order
                'report_id'     => $form->id,
            ]);

            // Send Push Notification
            $this->sendWebPushNotification($title, $message, $creator->web_player_id);
        }
    }


    return response()->json([
        'status' => 1,
        'message' => 'Material Incoming saved successfully.',
        'form_id' => $form->id
    ]);
}

public function storeMaterialStock(Request $request)
{
    $request->validate([
        'form_id' => 'required|exists:material_incomings,id',
        'sub_category_id' => 'required|integer|exists:material_sub_categories,id',
        'stock' => 'required|numeric|min:0',
    ]);

    // 1. Create the stock entry
    $stock = \App\Models\MaterialIncomingStock::create([
        'material_incomings_id' => $request->form_id,
        'sub_category_id' => $request->sub_category_id,
        'stock' => $request->stock,
    ]);

    // 2. Update total_stock in MaterialSubCategory
    $subcategory = \App\Models\MaterialSubCategory::find($request->sub_category_id);
    $subcategory->total_stock += $request->stock;
    $subcategory->save();

    return response()->json([
        'status' => 1,
        'message' => 'Material stock added and total stock updated successfully.'
    ]);
}

public function fetchMaterialIncomingByProject(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
    ]);

    $forms = \App\Models\MaterialIncoming::where('project_id', $request->project_id)
        ->with(['user', 'stocks.subCategory.category'])->orderBy('id', 'desc') // eager-load all related models
        ->get();

       $data = $forms->map(function ($form) {
    $stockDetails = $form->stocks->map(function ($stock) {
        $subCategoryName = $stock->subCategory->name ?? 'N/A';
        $categoryName = $stock->subCategory->category->name ?? 'N/A';
        $stockValue = $stock->stock ?? 0;
        $attribute = $stock->subCategory->attribute->name ?? '';

        return [
            'text' => "{$subCategoryName} - {$stockValue}{$attribute} ({$categoryName})",
            'category_name' => $categoryName,
        ];
    });

    return [
        'date'           => $form->date,
        'challan_number' => $form->challan_number,
        'bill_number'    => $form->bill_number,
        'vendor_name'    => $form->vendor_name,
        'uploaded_by'    => $form->user->name ?? '',
        'pdf_file_url'   => url("api/material/stock/pdf/{$form->id}"),
        'stocks'         => $stockDetails->pluck('text')->implode(', '),
        // take first category name if available
        'material_name'  => $stockDetails->pluck('category_name')->unique()->implode(', '),
    ];
});



    return response()->json([
        'status' => 1,
        'data' => $data,
    ]);
}

public function storeworkIssue(Request $request)
{
    $validator = \Validator::make($request->all(), [
        'project_id' => 'required|integer|exists:projects,id',
        'location' => 'nullable|string',
        'name_of_work' => 'nullable|string',
        'description' => 'nullable|string',
        'issue' => 'nullable|string',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        'images' => 'nullable|array',
        'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:512000' // max 500MB
    ], [
        'project_id.required' => 'The project ID is required.',
        'project_id.integer' => 'The project ID must be an integer.',
        'project_id.exists' => 'The selected project ID does not exist.',
        'location.required' => 'The location is required.',
        'images.*.image' => 'Each uploaded file must be an image.',
        'images.*.mimes' => 'Images must be in jpeg, png, jpg, gif, or svg format.',
        'video.mimes' => 'The video must be a file of type: mp4, mov, avi, wmv.',
        'video.max' => 'The video may not be greater than 500 MB.',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 0,
            'errors' => $validator->errors()
        ], 422);
    }

    $user = Auth::user();
    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    if (!\App\Models\Project::where('id', $request->project_id)->exists()) {
        return response()->json([
            'status' => 0,
            'error' => 'Invalid project ID.'
        ], 422);
    }



    $imagePaths = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('work_issue', 'local');
            $imagePaths[] = $path;
        }
    }

    $videoPath = null;
    if ($request->hasFile('video')) {
        $videoPath = $request->file('video')->store('work_issue/videos', 'local');
    }

    $form = \App\Models\WorkIssue::create([
        'project_id' => $request->project_id,
        'location' => $request->location,
        'name_of_work' => $request->name_of_work,
        'description' => $request->description,
        'issue' => $request->issue,
        'created_by' => $user->id,
        'status' => 'Pending',
        'date' => Carbon::today()->toDateString(),
        'video_path' => $videoPath,
    ]);

    foreach ($imagePaths as $path) {
        \App\Models\WorkIssueImages::create([
            'work_issues_id' => $form->id,
            'image_path' => $path,
        ]);
    }
    
     // ✅ Send Notification
    if ($user->created_by) {
        $creator = \App\Models\User::find($user->created_by);
        if ($creator && $creator->web_player_id) {

            $project = \App\Models\Project::find($request->project_id);
            $projectName = $project->project_name ?? 'Unknown';

           $title = 'Work Issue Submitted by ' . $user->name;

$formattedDate = \Carbon\Carbon::today()->format('d-m-Y');
$projectName = $project->project_name ?? 'N/A';
$workName = $request->name_of_work ?? 'N/A';
$location = $request->location ?? 'N/A';

$message = "Date - $formattedDate\nProject - $projectName\nWork - $workName\nLocation - $location";


            // Optional: Save notification in WebNotification table
            \App\Models\WebNotification::create([
                'project_id'  => $request->project_id,
                'engineer_id' => $user->id,
                'title'       => $title,
                'message'     => $message,
                'status'      => 'Pending',
                'key'         => 4,
                'report_id'   => $form->id
            ]);

            // Send push notification
            $this->sendWebPushNotification($title, $message, $creator->web_player_id);
        }
    }

    return response()->json([
        'status' => 1,
        'message' => 'Work Issue saved successfully.'
    ]);
}

public function getWorkIssueByProject(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
    ]);

    $user = Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $workIssues = \App\Models\WorkIssue::withCount('workissueImage')
        ->where('project_id', $request->project_id)
        ->orderBy('id', 'desc')
        ->get()
        ->map(function ($issue) {
            return [
                'status' => $issue->status,
                'issue' => $issue->issue,
                'name_of_work' => $issue->name_of_work,
                'description' => $issue->description,
                'date' => $issue->date,
                'uploaded_by' => $issue->user->name,
                 'pdf_file_url'    => url("api/work-issue/pdf/{$issue->id}"),
                'images' => $issue->workissueImage->map(function ($img) {
    return url('storage/' . $img->image_path); // uses APP_URL from .env
}),
                'video' => $issue->video_path ? url('storage/' . $issue->video_path) : null,


            ];
        });


    return response()->json([
        'status' => 1,
        'data' => $workIssues,
    ]);
}

public function projectdocumentGetCategory(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
    ]);

    $user = Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $documents = \App\Models\ProjectDocument::where('project_id', $request->project_id)
        ->get()
        ->map(function ($doc) {
            return [
                'project_document_id' => $doc->id,
                'name' => $doc->name,
                'image' => url('storage/' . $doc->image), // full URL using APP_URL
            ];
        });

    return response()->json([
        'status' => 1,
        'data' => $documents,
    ]);
}


public function projectdocumentuploadAttachments(Request $request)
{
    $request->validate([
        'project_document_id' => 'required|integer|exists:project_documents,id',
        'attachments' => 'required|array',
        'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,xls|max:512000',
    ]);

    $user = Auth::user();

    $projectDocument = \App\Models\ProjectDocument::with('project')->find($request->project_document_id);

    // Check project access
    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($projectDocument->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project document.'
        ], 403);
    }

    $savedFiles = [];

foreach ($request->file('attachments') as $file) {
    $originalName = $file->getClientOriginalName();
    $path = $file->storeAs('project_document_attachment', $originalName, 'local');

    $attachment = \App\Models\ProjectDocumentAttachments::create([
        'project_document_id' => $request->project_document_id,
        'files' => $originalName,
    ]);

    $savedFiles[] = [
        'id' => $attachment->id,
        'files' => url('storage/' . $attachment->file_path),
    ];
}


    return response()->json([
        'status' => 1,
        'message' => 'Files uploaded successfully.',

    ]);
}


public function getprojectdocumentAttachments(Request $request)
{
    $request->validate([
        'project_document_id' => 'required|integer|exists:project_documents,id',
    ]);

    $user = Auth::user();

    $projectDocument = \App\Models\ProjectDocument::find($request->project_document_id);

    // Validate user's access to the project
    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($projectDocument->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project document.'
        ], 403);
    }

    $attachments = \App\Models\ProjectDocumentAttachments::where('project_document_id', $request->project_document_id)
        ->get()
        ->map(function ($file) {
            return [
                'id' => $file->id,
                'attachments_url' => url('storage/project_document_attachment/' . $file->files),
                 'document_name' => $file->files,
            ];
        });

    return response()->json([
        'status' => 1,
        'attachments' => $attachments,
    ]);
}

public function storeMaterialPurchaseOrderBasic(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer',
        'category_id' => 'required|integer',
         'location' => 'nullable|string',
        'description' => 'nullable|string',
        'vendor_name' => 'nullable|string',
        'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',


    ]);

    $user = Auth::user();
    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $signaturePath = null;
    if ($request->hasFile('signature')) {
        $signaturePath = $request->file('signature')->store('material/purchaseorder_signatures', 'local');
    }



    $form = \App\Models\MaterialPurchaseOrder::create([
        'project_id' => $request->project_id,
        'category_id' => $request->category_id,
        'location' => $request->location,
        'description' => $request->description,
        'vendor_name' => $request->vendor_name,
        'signature' => $signaturePath,
        'date' => \Carbon\Carbon::today()->toDateString(),
        'created_by' => $user->id,
        'status' => 'Pending',
    ]);
    
      // ✅ Notification Logic
    if ($user->created_by) {
        $creator = \App\Models\User::find($user->created_by);
        if ($creator && $creator->web_player_id) {

            $project = \App\Models\Project::find($request->project_id);
            $projectName = $project->project_name ?? 'Unknown';

            $title = 'Purchase Order Submitted by ' . $user->name;

$formattedDate = \Carbon\Carbon::today()->format('d-m-Y');
$projectName = $project->project_name ?? 'N/A';
$location = $request->location ?? 'N/A';

$message = "Date - $formattedDate\nProject - $projectName\nLocation - $location";


            // Optional: Save to WebNotification table
            \App\Models\WebNotification::create([
                'project_id'  => $request->project_id,
                'engineer_id' => $user->id,
                'title'       => $title,
                'message'     => $message,
                'status'      => 'Pending',
                'key'         => 5, // Notification key for purchase order
                 'report_id'     => $form->id,
            ]);

            // Send Push Notification
            $this->sendWebPushNotification($title, $message, $creator->web_player_id);
        }
    }


    return response()->json([
        'status' => 1,
        'message' => 'Material Purchase Order saved successfully.',
        'form_id' => $form->id
    ]);
}

public function storeMaterialPurchaseOrderStock(Request $request)
{
    $request->validate([
        'form_id' => 'required|exists:material_incomings,id',
        'sub_category_id' => 'required|integer|exists:material_sub_categories,id',
        'stock' => 'required|numeric|min:0',
    ]);

    // 1. Create the stock entry
    $stock = \App\Models\MaterialPurchaseOrderStock::create([
        'material_purchase_orders_id' => $request->form_id,
        'sub_category_id' => $request->sub_category_id,
        'stock' => $request->stock,
    ]);


    return response()->json([
        'status' => 1,
        'message' => 'Material Purchase Order stock updated successfully.'
    ]);
}

public function fetchMaterialPurchaseOrderByProject(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
    ]);

    $forms = \App\Models\MaterialPurchaseOrder::where('project_id', $request->project_id)
        ->with(['user', 'stocks.subCategory.category'])->orderBy('id', 'DESC') // eager-load all related models
        ->get();

       $data = $forms->map(function ($form) {
        $stockDetails = $form->stocks->map(function ($stock) {
            $subCategoryName = $stock->subCategory->name ?? 'N/A';
            $categoryName    = $stock->subCategory->category->name ?? 'N/A';
            $stockValue      = $stock->stock ?? 0;
            $attribute       = $stock->subCategory->attribute->name ?? '';

            return [
                'text'          => "{$subCategoryName} - {$stockValue}{$attribute} ({$categoryName})",
                'category_name' => $categoryName,
            ];
        });

        return [
            'date'          => $form->date,
            'location'      => $form->location,
            'vendor_name'   => $form->vendor_name,
            'uploaded_by'   => $form->user->name ?? '',
            'status'        => $form->status,
            'pdf_file_url'  => url("api/material/purchase-order/pdf/{$form->id}"),
            'stocks'        => $stockDetails->pluck('text')->implode(', '),
            // ✅ Add material_name (all unique categories)
            'material_name' => $stockDetails->pluck('category_name')->unique()->implode(', '),
        ];
    });


    return response()->json([
        'status' => 1,
        'data' => $data,
    ]);
}

public function projectTestingReportGetCategory(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
    ]);

    $user = Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $documents = \App\Models\MaterialTestingReports::where('project_id', $request->project_id)
        ->get()
        ->map(function ($doc) {
            return [
                'material_testing_reports_id' => $doc->id,
                'name' => $doc->name,
                'image' => url('storage/' . $doc->image), // full URL using APP_URL
            ];
        });

    return response()->json([
        'status' => 1,
        'data' => $documents,
    ]);
}


public function projectTestingReportuploadAttachments(Request $request)
{
    $request->validate([
        'material_testing_reports_id' => 'required|integer|exists:material_testing_reports,id',
        'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,xls|max:10240',
        'remark' => 'required|string',
    ]);

    $user = Auth::user();

    $projectDocument = \App\Models\MaterialTestingReports::with('project')->find($request->material_testing_reports_id);

    // Check project access
    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($projectDocument->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project document.'
        ], 403);
    }

    // Handle single attachment
    $file = $request->file('attachment');
    $originalName = $file->getClientOriginalName();
    $path = $file->storeAs('Material-Testing-Reports/Details', $originalName, 'local'); // store in 'public' disk

    // Save to DB
    $attachment = \App\Models\MaterialTestingReportDetails::create([
        'material_testing_reports_id' => $request->material_testing_reports_id,
        'remark' => $request->remark,
        'file' => $path, // Make sure your DB table has this column
    ]);

    return response()->json([
        'status' => 1,
        'message' => 'Files uploaded successfully.',

    ]);
}



public function getprojectTestingReportAttachments(Request $request)
{
    $request->validate([
        'material_testing_reports_id' => 'required|integer|exists:material_testing_reports,id',
    ]);

    $user = Auth::user();

    $projectDocument = \App\Models\MaterialTestingReports::find($request->material_testing_reports_id);

    // Validate user's access to the project
    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($projectDocument->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project document.'
        ], 403);
    }

    $attachments = \App\Models\MaterialTestingReportDetails::where('material_testing_reports_id', $request->material_testing_reports_id)
        ->get()
        ->map(function ($file) {
            return [
                'id' => $file->id,
                'remark' => $file->remark,
                'attachments_url' => url('storage/' . $file->file),
                 'document_name' => basename($file->file)
            ];
        });

    return response()->json([
        'status' => 1,
        'attachments' => $attachments,
    ]);
}

public function workingDrawingsGetCategory(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
    ]);

    $user = Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $documents = \App\Models\Drawings::where('project_id', $request->project_id)
        ->get()
        ->map(function ($doc) {
            return [
                'drawing_id' => $doc->id,
                'name' => $doc->name,
                'image' => url('storage/' . $doc->image), // full URL using APP_URL
            ];
        });

    return response()->json([
        'status' => 1,
        'data' => $documents,
    ]);
}


public function workingDrawingsuploadAttachments(Request $request)
{
    $request->validate([
        'drawing_id' => 'required|integer|exists:drawings,id',
        'attachments' => 'required|array',
        'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,xls|max:512000',
    ]);

    $user = Auth::user();

    $drawing = \App\Models\Drawings::with('project')->find($request->drawing_id);

    // Check project access
    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($drawing->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project document.'
        ], 403);
    }

    $savedFiles = [];

    foreach ($request->file('attachments') as $file) {
    $originalName = $file->getClientOriginalName();
    $path = $file->storeAs('drawing_attachment', $originalName, 'local');

    $attachment = \App\Models\DrawingsAttachments::create([
        'drawing_id' => $request->drawing_id,
        'files' => $originalName,
    ]);

    $savedFiles[] = [
        'id' => $attachment->id,
        'files' => url('storage/' . $attachment->files),
    ];
  }


    return response()->json([
        'status' => 1,
        'message' => 'Files uploaded successfully.',

    ]);
}


public function getworkingDrawingsAttachments(Request $request)
{
    $request->validate([
        'drawing_id' => 'required|integer|exists:drawings,id',
    ]);

    $user = Auth::user();

    $drawing = \App\Models\Drawings::find($request->drawing_id);

    // Validate user's access to the project
    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($drawing->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project document.'
        ], 403);
    }

    $attachments = \App\Models\DrawingsAttachments::where('drawing_id', $request->drawing_id)
        ->get()
        ->map(function ($file) {
            return [
                'id' => $file->id,
                'attachments_url' => url('storage/drawing_attachment/' . $file->files),
                 'document_name' => $file->files,
            ];
        });

    return response()->json([
        'status' => 1,
        'attachments' => $attachments,
    ]);
}

public function billOfQuantityuploadAttachments(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
        'attachments' => 'required|array',
        'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,xls|max:512000',
    ]);

    $user = Auth::user();

    // Check project access
    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $savedFiles = [];

    foreach ($request->file('attachments') as $file) {
    $originalName = $file->getClientOriginalName();
    $path = $file->storeAs('bill_of_quantity', $originalName, 'local');

    $attachment = \App\Models\BillOfQuantity::create([
        'project_id' => $request->project_id,
        'files' => $path,
    ]);

    $savedFiles[] = [
        'id' => $attachment->id,
        'files' => url('storage/' . $attachment->files),
    ];
  }

    return response()->json([
        'status' => 1,
        'message' => 'Files uploaded successfully.',

    ]);
}



public function billOfQuantityAttachments(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
    ]);

    $user = Auth::user();

    // Validate user's access to the project
    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    // Fetch attachments from BillOfQuantity table
    $attachments = \App\Models\BillOfQuantity::where('project_id', $request->project_id)
        ->get()
        ->map(function ($file) {
            return [
                'id' => $file->id,
                'attachments_url' => asset('storage/' . $file->files),
                'document_name' => basename($file->files),
            ];
        });

    return response()->json([
        'status' => 1,
        'attachments' => $attachments,
    ]);
}

public function getMainCategoryList(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
    ]);

    $user = Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $categories = \App\Models\DailyReportMainCategory::where('project_id', $request->project_id)
        ->get()
        ->map(function ($cat) {
            return [
                'main_category_id' => $cat->id,
                'name' => $cat->name,
            ];
        });

    return response()->json([
        'status' => 1,
        'data' => $categories,
    ]);
}

public function getNameOfWorkList(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
        'main_category_id' => 'nullable|integer|exists:daily_report_main_categories,id',
    ]);

    $user = Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $query = \App\Models\NameOfWork::where('project_id', $request->project_id);

    // ✅ Apply filter if main_category_id provided
    if ($request->filled('main_category_id')) {
        $query->where('daily_report_main_category_id', $request->main_category_id);
    }

    $nameofwork = $query->get()->map(function ($work) {
        // 🔹 Calculate used measurement (sum from daily_reports.measurements)
        $usedMeasurement = \App\Models\DailyReport::where('name_of_work_id', $work->id)
            ->with('measurements')
            ->get()
            ->sum(function ($report) {
                return $report->measurements->sum('mesurements_value');
            });

        // 🔹 Calculate available measurement
        $availableMeasurement = max(($work->total_mesurement ?? 0) - $usedMeasurement, 0);

        return [
            'name_of_work_id'       => $work->id,
            'name'                  => $work->name,
            'main_category_name'    => $work->mainCategory->name ?? "Null",
            'mesurement_attribute'  => $work->mesurementattribute->name ?? "Null",
            'mesurement_attribute_id' => $work->mesurement_attribute_id ?? "Null",
            'mesurement_sub_attribute' => $work->mesurementsubAttribute->name ?? "Null",
            'unit_category_name'    => $work->unitCategory->name ?? "Null",
            'unit_category_id'      => $work->unit_category_id ?? "Null",
            'total_mesurement'      => $work->total_mesurement,
            'used_mesurement'       => $usedMeasurement,
            'available_mesurement'  => $availableMeasurement,
        ];
    });

    return response()->json([
        'status' => 1,
        'data' => $nameofwork,
    ]);
}

public function getUnitSubCategoryList(Request $request)
{
    $request->validate([
        'category_id' => 'required|integer|exists:unit_categories,id',
    ]);

    $user = Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    // Fetch the UnitCategory
    $unitCategory = \App\Models\UnitCategory::find($request->category_id);

    if (!$unitCategory || !in_array($unitCategory->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this category or project.',
        ], 403);
    }

    // Fetch subcategories by category_id
    $unitsubcategory = \App\Models\UnitSubCategory::where('category_id', $request->category_id)
        ->get()
        ->map(function ($work) {
            return [
                'unit_sub_category_id' => $work->id,
                'name' => $work->name,
            ];
        });

    return response()->json([
        'status' => 1,
        'data' => $unitsubcategory,
    ]);
}

public function getManPowerkList(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
    ]);

    $user = Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $manpower = \App\Models\ManPower::where('project_id', $request->project_id)->where('status', 'Active')
        ->get()
        ->map(function ($work) {
            return [
                'man_powers_id' => $work->id,
                'name' => $work->name,
                'price' => $work->price,
                'status' => $work->status,
            ];
        });

    return response()->json([
        'status' => 1,
        'data' => $manpower,
    ]);
}


public function getMesurementAttributeList(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
    ]);

    $user = Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $mesurement = \App\Models\MesurementAttribute::where('project_id', $request->project_id)
        ->get()
        ->map(function ($work) {
            return [
                'mesurement_attributes_id' => $work->id,
                'name' => $work->name,

            ];
        });

    return response()->json([
        'status' => 1,
        'data' => $mesurement,
    ]);
}

public function getwingsList(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
    ]);

    $user = Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $wing = \App\Models\wing::where('project_id', $request->project_id)
        ->get()
        ->map(function ($work) {
            return [
                'wing_id' => $work->id,
                'name' => $work->name
            ];
        });

    return response()->json([
        'status' => 1,
        'data' => $wing,
    ]);
}

public function getflourList(Request $request)
{
    $request->validate([
        'wing_id' => 'required|integer|exists:wings,id',
    ]);

    $user = Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    // Fetch the UnitCategory
    $wing = \App\Models\wing::find($request->wing_id);

    if (!$wing || !in_array($wing->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this wing or project.',
        ], 403);
    }

    // Fetch subcategories by category_id
    $flour = \App\Models\Flour::where('wing_id', $request->wing_id)
        ->get()
        ->map(function ($work) {
            return [
                'flour_id' => $work->id,
                'name' => $work->name,
            ];
        });

    return response()->json([
        'status' => 1,
        'data' => $flour,
    ]);
}

public function getProjectMasterData(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
    ]);

    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'status' => 0,
            'error' => 'Unauthorized: Token is missing or invalid.'
        ], 401);
    }

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $projectId = $request->project_id;

    $nameOfWorkList = \App\Models\NameOfWork::where('project_id', $projectId)
        ->get()
        ->map(function ($work) {
            return [
                'name_of_work_id' => $work->id,
                'name' => $work->name,
                'unit_category_name' => $work->unitCategory->name ?? "Null",
                'unit_category_id' => $work->unit_category_id ?? "Null",
            ];
        });

    $manPowerList = \App\Models\ManPower::where('project_id', $projectId)
        ->get()
        ->map(function ($manpower) {
            return [
                'man_powers_id' => $manpower->id,
                'name' => $manpower->name,
                'price' => $manpower->price,
            ];
        });

    $measurementAttributeList = \App\Models\MesurementAttribute::where('project_id', $projectId)
        ->get()
        ->map(function ($measurement) {
            return [
                'mesurement_attributes_id' => $measurement->id,
                'name' => $measurement->name,
            ];
        });

    $wingList = \App\Models\wing::where('project_id', $projectId)
        ->get()
        ->map(function ($wing) {
            return [
                'wing_id' => $wing->id,
                'name' => $wing->name
            ];
        });

    return response()->json([
        'status' => 1,
        'data' => [
            'name_of_work_list' => $nameOfWorkList,
            'man_power_list' => $manPowerList,
            'measurement_attribute_list' => $measurementAttributeList,
            'wing_list' => $wingList,
        ]
    ]);
}

public function dailyReportformBasic(Request $request)
{
    $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
            'name_of_work_id' => 'required|exists:name_of_works,id',
                            'main_category_id' => 'nullable|integer|exists:daily_report_main_categories,id',
            'category_id' => 'nullable|exists:unit_categories,id',
            'sub_category_id' => 'nullable|exists:unit_sub_categories,id',
            'wing_id' => 'nullable|exists:wings,id',
            'flour_id' => 'nullable|exists:flour,id',
            'for' => 'nullable|string|max:255',
             'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'weather' => 'nullable|string|max:255',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'comment' => 'nullable|string',
            'at' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:512000',
            'images' => 'nullable|array',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:512000' // max 500MB
    ]);

    $user = \Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $signaturePath = null;
    if ($request->hasFile('signature')) {
        $signaturePath = $request->file('signature')->store('daily-report/signature', 'local');
    }

     $imagePaths = [];

    if ($request->hasFile('images')) {
     foreach ($request->file('images') as $image) {
        $path = $image->store('daily-report/images', 'local');
        $imagePaths[] = $path;
     }
    }
    
       $videoPath = null;
    if ($request->hasFile('video')) {
        $videoPath = $request->file('video')->store('daily-report/videos', 'local');
    }


    $report = new \App\Models\DailyReport();
        $report->project_id = $request->project_id;
         $report->daily_report_main_category_id = $request->main_category_id;
        $report->name_of_work_id = $request->name_of_work_id;
        $report->category_id = $request->category_id;
        $report->sub_category_id = $request->sub_category_id;
         $report->wing_id = $request->wing_id;
        $report->flour_id = $request->flour_id;
        $report->for = $request->for;
         $report->latitude = $request->latitude;
        $report->longitude = $request->longitude;
        $report->location = $request->location;
        $report->weather = $request->weather;
        $report->description = $request->description;
        $report->signature = $signaturePath;
        $report->comment = $request->comment;
         $report->at = $request->at;
         $report->video_path = $videoPath;
       
        $report->created_by = \Auth::user()->id;
        $report->date = \Carbon\Carbon::today()->toDateString();

        $report->save();

         foreach ($imagePaths as $path) {
        \App\Models\DailyReportImages::create([
            'daily_reports_id' => $report->id,
            'image_path' => $path,
        ]);
    }
    
    // send Notification Logic

    if ($user->created_by) {
    $creator = \App\Models\User::find($user->created_by);
    if ($creator && $creator->web_player_id) {

        // Fetch project name
        $project = \App\Models\Project::find($request->project_id);
        $projectName = $project->project_name ?? 'Unknown';

       $title = 'Daily Report Submitted by ' . $user->name;

$formattedDate = \Carbon\Carbon::today()->format('d-m-Y');
$projectName = $project->project_name ?? 'N/A';
$workName = optional(\App\Models\NameOfWork::find($request->name_of_work_id))->name ?? 'N/A';
$location = $request->location ?? 'N/A';

$message = "Date - $formattedDate\nProject - $projectName\nWork - $workName\nLocation - $location";


        // Save to WebNotification table (optional)
        \App\Models\WebNotification::create([
            'project_id'  => $request->project_id,
            'engineer_id' => $user->id,
            'title'       => $title,
            'message'     => $message,
            'status'      => 'Pending',
            'key'         => 2,
            'report_id'         => $report->id,
            
        ]);

        // Send Push Notification
        $this->sendWebPushNotification($title, $message, $creator->web_player_id);
    }
}

        return response()->json([
            'status' => 1,
            'message' => 'Daily Report saved successfully.',
             'form_id' => $report->id
        ]);
}

public function storedailyReportManPower(Request $request)
{
    $request->validate([
        'form_id' => 'required|exists:daily_reports,id',
        'man_powers_id' => 'required|integer|exists:man_powers,id',
        'total_person' => 'required|numeric|min:0',
    ]);

    // 1. Create the stock entry
    $stock = \App\Models\DailyReportManPower::create([
        'daily_reports_id' => $request->form_id,
        'man_powers_id' => $request->man_powers_id,
        'total_person' => $request->total_person,
        'created_by' => \Auth::user()->id
    ]);

    // 2. Update total_stock in MaterialSubCategory
    $manpower = \App\Models\ManPower::find($request->man_powers_id);
    $manpower->total_person += $request->total_person;
    $manpower->save();

    return response()->json([
        'status' => 1,
        'message' => 'Main power updated successfully.'
    ]);
}


public function storedailyReportMaterialStock(Request $request)
{
    $request->validate([
        'form_id' => 'required|exists:daily_reports,id',
        'sub_category_id' => 'required|integer|exists:material_sub_categories,id',
        'stock' => 'required|numeric|min:0',
    ]);

    // Fetch the subcategory
    $subcategory = \App\Models\MaterialSubCategory::find($request->sub_category_id);

    // Check stock availability
    if ($subcategory->total_stock == 0) {
        return response()->json([
            'status' => 0,
            'message' => 'Cannot add stock: Total stock is zero.',
        ], 422);
    }

    if ($request->stock > $subcategory->total_stock - $subcategory->used_stock) {
        return response()->json([
            'status' => 0,
            'message' => 'Used stock cannot exceed available stock.',
        ], 422);
    }

    // Create the stock entry
    \App\Models\DailyReportMaterialUsedStock::create([
        'daily_reports_id' => $request->form_id,
        'sub_category_id' => $request->sub_category_id,
        'used_stock' => $request->stock,
        'created_by' => \Auth::user()->id
    ]);

    // Update used_stock in MaterialSubCategory
    $subcategory->used_stock += $request->stock;
    $subcategory->save();

    return response()->json([
        'status' => 1,
        'message' => 'Material stock added and Used stock updated successfully.',
    ]);
}

public function storedailyReportMesurement(Request $request)
{
    $request->validate([
        'form_id' => 'required|exists:daily_reports,id',
        'mesurement_attributes_id' => 'required|integer|exists:mesurement_attributes,id',
        'mesurements_value' => 'required|numeric|min:0',
    ]);

    // 1. Create the stock entry
    $stock = \App\Models\DailyReportMesurement::create([
        'daily_reports_id' => $request->form_id,
        'mesurement_attributes_id' => $request->mesurement_attributes_id,
        'mesurements_value' => $request->mesurements_value,
        'created_by' => \Auth::user()->id
    ]);


    return response()->json([
        'status' => 1,
        'message' => 'Mesurement Create Value successfully.'
    ]);
}

// public function storedailyReportEquipments(Request $request)
// {
//     $request->validate([
//         'form_id' => 'required|exists:daily_reports,id',
//         'equipment_id' => 'required|integer|exists:equipment,id',
//         'start_time' => 'required|date_format:H:i',
//         'end_time' => 'required|date_format:H:i|after:start_time',
//         'total_hours' => 'required|numeric',
//     ]);

//     $form = \App\Models\DailyReport::findOrFail($request->form_id);
//     $equipment = Equipment::findOrFail($request->equipment_id);
//     $rate = $equipment->rate ?? 0;

//     // Use total_hours from input
//     $totalHours = floatval($request->total_hours);
//     $totalAmount = $rate * $totalHours;

//     \App\Models\DailyReportEquipments::updateOrCreate(
//         [
//             'daily_reports_id' => $form->id,
//             'equipment_id' => $request->equipment_id,
//             'date' => Carbon::today()->toDateString(), // include date in unique condition if you want entries per day
//         ],
//         [
//             'start_time' => $request->start_time,
//             'end_time' => $request->end_time,
//             'rate' => $equipment->rate,
//             'total_hours' => $request->total_hours,
//             'total_amount' => $totalAmount,
//             'date' => Carbon::today()->toDateString(), // Save today's date
//             'created_by' => \Auth::user()->id
//         ]
//     );

//     return response()->json([
//         'status' => 1,
//         'message' => 'Daily Report Equipment saved successfully.'
//     ]);
// }

public function storedailyReportEquipments(Request $request)
{
    $request->validate([
        'form_id' => 'required|exists:daily_reports,id',
        'equipment_id' => 'required|integer|exists:equipment,id',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
        'total_hours' => 'required|numeric',
    ]);

    $form = \App\Models\DailyReport::findOrFail($request->form_id);
    $equipment = Equipment::findOrFail($request->equipment_id);
    $rate = $equipment->rate ?? 0;

    $totalHours = floatval($request->total_hours);
    $totalAmount = $rate * $totalHours;
    $todayDate = Carbon::today()->toDateString();

    // 1. Save to DailyReportEquipments
    $dailyEquipment = \App\Models\DailyReportEquipments::updateOrCreate(
        [
            'daily_reports_id' => $form->id,
            'equipment_id' => $request->equipment_id,
            'date' => $todayDate,
        ],
        [
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'rate' => $rate,
            'total_hours' => $request->total_hours,
            'total_amount' => $totalAmount,
            'date' => $todayDate,
            'created_by' => \Auth::user()->id
        ]
    );

    // 2. Save to EquipmentForm (if not exists, create one for same project)
    $equipmentForm = \App\Models\EquipmentForm::create(
        [
            'project_id' => $form->project_id,
            'location' => $form->location ?? 'N/A',
            'created_by' => \Auth::id(),
            'date' => $todayDate,
            'description' => $form->description ?? null,
            'signature' => $form->signature ?? null,
        ]
    );

    // 3. Save to EquipmentFormItem
    \App\Models\EquipmentFormItem::updateOrCreate(
        [
            'equipment_form_id' => $equipmentForm->id,
            'equipment_id' => $request->equipment_id,
            'date' => $todayDate,
        ],
        [
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'rate' => $rate,
            'total_hours' => $totalHours,
            'total_amount' => $totalAmount,
            'date' => $todayDate,
        ]
    );

    return response()->json([
        'status' => 1,
        'message' => 'Daily Report Equipment and Equipment Form item saved successfully.',
        'equipment_form_id' => $equipmentForm->id
    ]);
}


public function getUserProfile(Request $request)
{
    // Get the authenticated user
    $user = Auth::user();

    // Return the profile data
    return response()->json([
        'status' => 1,
        'profile' => [
            'name' => $user->name,
            'email' => $user->email,
           'profile_image' => $user->avatar ? asset('storage/uploads/avatar/' . $user->avatar) : null,
            'pdf_logo' => $user->pdf_logo ? asset('storage/uploads/pdf_logo/' . $user->pdf_logo) : null,
        ],
    ]);
}

public function getsitegalleryimages(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
        'name_of_work_id' => 'required|integer|exists:name_of_works,id',
    ]);

    $user = Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    // Fetch DailyReport records
    $dailyReports = \App\Models\DailyReport::where('project_id', $request->project_id)
        ->where('name_of_work_id', $request->name_of_work_id)
        ->get();

    // if ($dailyReports->isEmpty()) {
    //     return response()->json([
    //         'status' => 0,
    //         'error' => 'No daily reports found for the selected Name of Work.',
    //     ], 404);
    // }

    $dailyReportIds = $dailyReports->pluck('id');

    // Fetch images
    $images = \App\Models\DailyReportImages::whereIn('daily_reports_id', $dailyReportIds)
        ->select('id', 'image_path','created_at')
        ->get()
        ->map(function ($image) {
            return [
                'id' => $image->id,
                'image_path' => asset('storage/' . $image->image_path),
                'created_at' => $image->created_at ? $image->created_at->format('Y-m-d') : null,
            ];
        });

    // Fetch videos from DailyReport model
    $videos = $dailyReports
        ->filter(function ($report) {
            return !empty($report->video_path);
        })
        ->map(function ($report) {
            return [
                'daily_report_id' => $report->id,
                'video_path' => asset('storage/' . $report->video_path),
                'created_at' => $report->created_at ? $report->created_at->format('Y-m-d') : null,
            ];
        })
        ->values(); // reset keys

    return response()->json([
        'status' => 1,
        'images' => $images,
        'Video' => $videos,
    ]);
}

public function getDailyReportByProject(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
        'name_of_work_id' => 'nullable|integer|exists:name_of_works,id',
        'date' => 'nullable|date', // ✅ Optional date filter
    ]);

    $user = Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $dailyReportsQuery = \App\Models\DailyReport::withCount('dailyReportImage')
        ->where('project_id', $request->project_id);

    // 🔍 Filter by name_of_work_id if provided
    if ($request->has('name_of_work_id')) {
        $dailyReportsQuery->where('name_of_work_id', $request->name_of_work_id);
    }

    // 📅 Filter by date if provided
    if ($request->has('date')) {
        $dailyReportsQuery->whereDate('date', $request->date);
    }

    $dailyreport = $dailyReportsQuery
        ->orderBy('id', 'desc')
        ->get()
        ->map(function ($report) {
            return [
                'name_of_work' => $report->nameOfWork->name ?? null,
                'unit_category' => $report->UnitCategory->name ?? null,
                'unit_sub_category' => $report->subCategory->name ?? null,
                'forr' => $report->for,
                'location' => $report->location,
                'description' => $report->description,
                'comment' => $report->comment,
                'date' => $report->date,
                'at' => $report->at,
                'wing' => $report->wing->name ?? null,
                'flour' => $report->flour->name ?? null,
                'uploaded_by' => $report->user->name ?? null,
                'pdf_file_url' => url("api/daily-report/pdf/{$report->id}"),
                'images' => $report->dailyReportImage->map(function ($img) {
                    return url('storage/' . $img->image_path);
                }),
                'video' => $report->video_path ? url('storage/' . $report->video_path) : null,
            ];
        });

    return response()->json([
        'status' => 1,
        'data' => $dailyreport,
    ]);
}

public function uploadLogoimage(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['status' => 0, 'error' => 'Unauthorized'], 401);
    }

    // Validate image input(s)
    $request->validate([
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
        'pdf_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
    ]);

    $uploaded = [];

    // Handle avatar upload
    if ($request->hasFile('avatar')) {
        $avatarFile = $request->file('avatar');
        $avatarName = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $avatarFile->getClientOriginalExtension();
        $avatarPath = 'uploads/avatar';
        $avatarFile->storeAs($avatarPath, $avatarName, 'local');
        $user->avatar = $avatarName;
        $uploaded['profile_url'] = asset('storage/' . $avatarPath . '/' . $avatarName);
    }

    // Handle company logo upload
    if ($request->hasFile('pdf_logo')) {
        $logoFile = $request->file('pdf_logo');
        $logoName = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $logoFile->getClientOriginalExtension();
        $logoPath = 'uploads/pdf_logo';
        $logoFile->storeAs($logoPath, $logoName, 'local');
        $user->pdf_logo = $logoName;
        $uploaded['pdf_logo_url'] = asset('storage/' . $logoPath . '/' . $logoName);
    }

    // Save changes if any files were uploaded
    if (!empty($uploaded)) {
        $user->save();
        return response()->json([
            'status' => 1,
            'message' => 'File(s) uploaded successfully',
            'data' => $uploaded,
        ], 200);
    }

    return response()->json(['status' => 0, 'error' => 'No image provided'], 400);
}

public function getToDoListByProject(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
    ]);

    $user = Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $todoList = \App\Models\ToDoEngineer::withCount(['tasks as pending_tasks_count' => function ($query) {
            $query->where('status', 'Pending');
        }])
        ->where('project_id', $request->project_id)
        ->where('engineer_id', $user->id)
        ->get(['id', 'name', 'created_user'])
        ->map(function ($item) {
            return [
                'to_do_engineer_id' => $item->id,
                'task_category_name' => $item->name,
               
                'pending_count' => $item->pending_tasks_count,
            ];
        });

    return response()->json([
        'status' => 1,
        'data' => $todoList,
    ]);
}


public function ToDoListFolderstore(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_category_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        // Optional: Check if user is allowed to create in this project
        $userProjectIds = is_array($user->project_id)
            ? $user->project_id
            : json_decode($user->project_id, true);

        if (!in_array($request->project_id, $userProjectIds)) {
            return response()->json([
                'status' => 0,
                'message' => 'Access denied for this project.'
            ], 403);
        }

        // Create ToDoEngineer record
        $todo = \App\Models\ToDoEngineer::create([
            'project_id' => $request->project_id,
            'name' => $request->task_category_name,
            'engineer_id' => $user->id, // Assign logged-in user as engineer
            'created_by' => $user->id,
            'created_user' => "Engineer",
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'ToDo List Task Category created successfully.',
            'data' => [
                'id' => $todo->id,
                'task_category_name' => $todo->name,
            ],
        ]);
    }
    
     public function ToDoTaskstore(Request $request)
{
     $user = Auth::user();
    $request->validate([
        'to_do_engineer_id' => 'required|exists:to_do_engineers,id',
        'task_title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'due_date' => 'nullable|date',
        'files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:20480', // adjust MIME/types/size as needed

    ]);

    // Set status based on due_date
    $status = $request->filled('due_date') ? 'Pending' : '-';

    // Create task with current date and status
    $task = \App\Models\ToDoEngineerTask::create([
        'to_do_engineer_id' => $request->to_do_engineer_id,
        'task_title' => $request->task_title,
        'description' => $request->description,
        'due_date' => $request->due_date,
        'date' => Carbon::now()->toDateString(),
        'status' => $status,
        'created_by' => $user->id,
            'created_user' => "Engineer",
    ]);
    
     if ($request->hasFile('files')) {
        foreach ($request->file('files') as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $filepath = $file->storeAs('todo-tasks', $filename, 'local'); // save in /storage/app/public/todo-tasks

            \App\Models\ToDoEngineerTaskFiles::create([
                'task_id' => $task->id,
                'file_path' => $filename,
            ]);
        }
    }

    return response()->json([
        'status' => 1,
        'message' => 'Task created successfully.',
        'data' => $task,
    ]);
}

public function getToDoTaskByEngineer(Request $request)
{
    $request->validate([
        'to_do_engineer_id' => 'required|integer|exists:to_do_engineers,id',
    ]);

    $user = Auth::user();

  $toDoEngineer = \App\Models\ToDoEngineer::find($request->to_do_engineer_id);

    // Check if the engineer_id in the to_do_engineers table matches the logged-in user's ID
    if ($toDoEngineer->engineer_id != $user->id) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this engineer.'
        ], 403);
    }
    $todoListTask = \App\Models\ToDoEngineerTask::with(['engineer', 'attachment'])->where('to_do_engineer_id', $request->to_do_engineer_id)
        ->orderBy('created_at', 'desc')
        ->get(['id', 'to_do_engineer_id', 'task_title', 'description', 'due_date', 'date', 'status','created_user'])
        ->map(function ($item) {
            return [
                'task_id' => $item->id,
                'task_category_name' => $item->engineer->name, // You can replace this with actual category name if needed
                'task_title' => $item->task_title,
                'description' => $item->description,
                'due_date' => $item->due_date,
                'date' => $item->date,
                'status' => $item->status,
                'created_user' => $item->created_user,
                 'files' => $item->attachment->map(function ($file) {
                return asset('storage/todo-tasks/' . $file->file_path);
            }),

            ];
        });

    return response()->json([
        'status' => 1,
        'data' => $todoListTask,
    ]);
}

public function updateToDoTask(Request $request)
{
    $request->validate([
        'task_id'      => 'required|exists:to_do_engineer_tasks,id',
        'task_title'   => 'sometimes|required|string|max:255',
        'description'  => 'nullable|string',
        'due_date'     => 'nullable|date',
        'files.*'      => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:20480',
    ]);

    $task = \App\Models\ToDoEngineerTask::find($request->task_id);

    // Update fields only if present
    if ($request->has('task_title')) {
        $task->task_title = $request->task_title;
    }

    if ($request->has('description')) {
        $task->description = $request->description;
    }

    if ($request->has('due_date')) {
        $task->due_date = $request->due_date;
        $task->status = 'Pending';
    } elseif ($task->due_date === null) {
        $task->status = '-';
    }

    $task->save();

    // Upload and attach files
    if ($request->hasFile('files')) {
        foreach ($request->file('files') as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $filepath = $file->storeAs('todo-tasks', $filename, 'local');

            \App\Models\ToDoEngineerTaskFiles::create([
                'task_id' => $task->id,
                'file_path' => $filename,
            ]);
        }
    }

    return response()->json([
        'status' => 1,
        'message' => 'Task updated successfully.',
        'data' => $task,
    ]);
}

public function sendWebPushNotification($title, $message, $playerId)
{
    $user = \App\Models\User::where('web_player_id', $playerId)->first();

    $fields = [
        'app_id' => "f782d678-ff2c-47d3-93c9-840c8a3b2683",
        'include_player_ids' => [$playerId],
        'headings' => ['en' => $title],
        'contents' => ['en' => $message],
        'url' => url('/'),
                'chrome_web_icon' => asset('images/logo.png'), // ✅ Add your logo/icon

    ];

    $fieldsJson = json_encode($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic os_v2_app_66bnm6h7frd5he6jqqgiuozgqp7eaddocn6u3tnwlbhffnw5bh7w7esber2swynql3vo665dw4v57shfmh3ox4w5ykcjmv22tyncaba'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsJson);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $result = json_decode($response, true);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // ✅ Log to laravel.log
    \Log::info('Web Push Notification Sent', [
        'user_id' => $user->id ?? null,
        'player_id' => $playerId,
        'title' => $title,
        'message' => $message,
        'status' => ($httpCode === 200 && isset($result['id'])) ? 'Sent' : 'Failed',
        'response' => $response,
    ]);

    return $result;
}


public function updateToDoTaskStatus(Request $request)
{
    $request->validate([
        'task_id' => 'required|exists:to_do_engineer_tasks,id',
        'status' => 'required|string|in:Pending,Completed,-',
    ]);

    $task = \App\Models\ToDoEngineerTask::find($request->task_id);
    $task->status = $request->status;
    $task->save();

    // Step 1: Get related ToDoEngineer
    $toDoEngineer = \App\Models\ToDoEngineer::find($task->to_do_engineer_id);

    if ($toDoEngineer) {
        // Step 2: Get the engineer user
        $engineerUser = \App\Models\User::find($toDoEngineer->engineer_id);

        // Step 3: Send notification to the creator of that engineer
        if ($engineerUser && $engineerUser->created_by) {
            $creatorUser = \App\Models\User::find($engineerUser->created_by);

            if ($creatorUser && $creatorUser->web_player_id) {
                $engineerName = $engineerUser->name ?? 'Engineer';

               $title = 'Task Completed by ' . $engineerName;
               $message ='';
    $message .= 'Task - ' . ($task->task_title ?? 'N/A') . "\n";
    $message .= 'Description - ' . ($task->description ?? 'N/A');
    

                // Save notification in EngineerNotification model
                \App\Models\WebNotification::create([
                    'project_id'  => $toDoEngineer->project_id ?? null,
                    'engineer_id' => $engineerUser->id, // engineer_id is the user_id of engineer
                    'title'       => $title,
                    'message'     => $message,
                    'status'      => 'Pending',
                     'key'      => 1,
                     'report_id'     => $task->to_do_engineer_id ,
                ]);

                // Send web push notification
                $this->sendWebPushNotification($title, $message, $creatorUser->web_player_id);
            }
        }
    }

    return response()->json([
        'status' => 1,
        'message' => 'Task status updated successfully.',
        'data' => [
            'task_id' => $task->id,
            'new_status' => $task->status,
        ],
    ]);
}

public function getEngineerNotification(Request $request)
{
    $user = Auth::user();


    // Fetch notifications for the engineer and select specific fields
    $notifications = \App\Models\EngineerNotification::where('engineer_id', $user->id)
        ->select('id', 'title', 'message', 'status','project_id','to_do_engineer_id')
        ->orderBy('created_at', 'desc')
        ->get();
        
    $data = $notifications->map(function ($notification) {
        $cleanMessage = str_replace(["\n", "\r"], ', ', $notification->message);
    $cleanMessage = rtrim($cleanMessage, ", \t"); // Remove trailing comma, space, tab
    $cleanMessage .= '. '; // Add final punctuation
    return [
        'id' => $notification->id,
        'title' => $notification->title,
        'message' => str_replace(["\n", "\r"], ', ', $notification->message), // Replace newlines
        'status' => $notification->status,
        'project_id' => $notification->project_id !== null ? (int) $notification->project_id : null,
        'to_do_engineer_id' => $notification->to_do_engineer_id !== null ? (int) $notification->to_do_engineer_id : null,
    
    ];
});


    return response()->json([
        'status' => 1,
        'data' => $data,
    ]);
}

public function EngineerNotificationmarkAsRead(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|exists:engineer_notifications,id',
        ]);

        $notification = \App\Models\EngineerNotification::find($request->notification_id);

        if ($notification->status === 'Read') {
            return response()->json([
                'status' => 1,
                'message' => 'Notification already marked as read.',
            ]);
        }

        $notification->status = 'Read';
        $notification->save();

        return response()->json([
            'status' => 1,
            'message' => 'Notification marked as read successfully.',
        ]);
    }
    
    public function deleteNotification(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|exists:engineer_notifications,id',
        ]);

        $notification = \App\Models\EngineerNotification::find($request->notification_id);

        // Optional: check that the logged-in user is the owner
         if ($notification->engineer_id !== $request->user()->id) {
             return response()->json([
                 'status' => 0,
                 'message' => 'Unauthorized to delete this notification.',
            ], 403);
         }

        $notification->delete();

        return response()->json([
            'status' => 1,
            'message' => 'Notification deleted successfully.',
        ]);
    }
    
    public function getAutoDexByProject(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
    ]);

    $user = Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }

    $auto_desk = \App\Models\AutoDex::where('project_id', $request->project_id)
        ->orderBy('created_at', 'desc')
        ->get(['id', 'name'])
        ->map(function ($item) {
                        $attachmentCount = \App\Models\AutoDexAttachments::where('auto_dexes_id', $item->id)->count();

            return [
                'id' => $item->id,
                'auto_dex_folder' => $item->name,
                'attachments_count' => $attachmentCount,
            ];
        });

    return response()->json([
        'status' => 1,
        'data' => $auto_desk,
    ]);
}

 public function AutoDeskFolderstore(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'auto_dex_folder_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        // Optional: Check if user is allowed to create in this project
        $userProjectIds = is_array($user->project_id)
            ? $user->project_id
            : json_decode($user->project_id, true);

        if (!in_array($request->project_id, $userProjectIds)) {
            return response()->json([
                'status' => 0,
                'message' => 'Access denied for this project.'
            ], 403);
        }

        // Create ToDoEngineer record
        $auto_desk = \App\Models\AutoDex::create([
            'project_id' => $request->project_id,
            'name' => $request->auto_dex_folder_name,
            'created_by' => $user->id,
            'created_user' => "Engineer",
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Auto Dex created successfully.',
            'data' => [
                'id' => $auto_desk->id,
                'auto_dex_folder_name' => $auto_desk->name,
            ],
        ]);
    }
    
    public function getAutoDeskAttachment(Request $request)
{
    $request->validate([
        'auto_dex_id' => 'required|integer|exists:auto_dexes,id',
    ]);

    $user = Auth::user();

    $attachments = \App\Models\AutoDexAttachments::with('autodex')
        ->where('auto_dexes_id', $request->auto_dex_id)
        ->orderBy('created_at', 'desc')
        ->get(['id', 'auto_dexes_id', 'files', 'view_url']);

    $data = $attachments->map(function ($item) {
        return [
            'id' => $item->id,
            'files' => $item->files,
            'view_url' => $item->view_url,
            'auto_dex_name' => $item->autodex ? $item->autodex->name : null,
        ];
    });

    return response()->json([
        'status' => 1,
        'data' => $data,
    ]);
}

 public function AutoDexAttachmentUpload(Request $request)
 {
      $request->validate([
        'auto_dex_id' => 'required|exists:auto_dexes,id',
        'file' => 'required|file',
      ]);

    try {
        $file = $request->file('file');

        // Prepare file for HTTP upload
        $response = Http::attach(
            'modelFile',                // key expected by external API
            file_get_contents($file),
            $file->getClientOriginalName()
        )->post('https://autocad-file-backend.onrender.com/api/models');

        if ($response->successful()) {
            $res = $response->json();

            // Save file locally (optional)
            $originalName = $file->getClientOriginalName();
            $fileName = $request->auto_dex_id . '_' . $originalName;
            $path = 'autodex_attachment';

            // Upload to storage/app/public/autodex_attachment
            $storedPath = $file->storeAs($path, $fileName, 'local');

            // Send file to external API (using absolute path)
            $filePath = storage_path($storedPath);

            // Save to database
             $attachment = \App\Models\AutoDexAttachments::create([
                'auto_dexes_id' => $request->auto_dex_id,
                'files' => $fileName,
                'created_by' => \Auth::user()->id,
                'view_url' => $res['viewerUrl'] ?? null,
                'created_user' => "Engineer",
            ]);

            return response()->json([
                'status' => 1,
                'message' => 'File uploaded successfully.',
                'data' => [
                    'file' => $attachment->files,
                    'view_url' => $attachment->view_url,
                ]
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Upload failed',
                'error' => $response->body()
            ], 500);
        }

    } catch (\Exception $e) {
        return response()->json([
            'status' => 0,
            'message' => 'Server error: ' . $e->getMessage(),
        ], 500);
    }
}

public function engineerAttendancestore(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
        'action' => 'required|in:check_in,check_out',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
    ]);

    $engineerId = \Auth::id();
    $now = Carbon::now('Asia/Kolkata');
    $today = Carbon::now('Asia/Kolkata')->toDateString();

    // Get project and its attendance times
    $project = \App\Models\Project::findOrFail($request->project_id);
    $attendanceStart = Carbon::parse($project->attendance_start_time, 'Asia/Kolkata');
    $attendanceEnd = Carbon::parse($project->attendance_end_time, 'Asia/Kolkata');

    // Find any active (open) attendance record
    $attendance = EngineerAttendances::where('engineer_id', $engineerId)
        ->where('project_id', $request->project_id)
        ->whereNull('check_out')
        ->latest('id')
        ->first();

    // -------------------- CHECK-IN --------------------
    if ($request->action === 'check_in') {
        if ($attendance && $attendance->check_in && !$attendance->check_out) {
            return response()->json([
                'success' => false,
                'message' => 'You have already checked in for this project and not checked out yet.'
            ], 400);
        }

        // Calculate Late
        $lateMinutes = 0;
        $lateFormatted = '00h 00m';
        if ($now->gt($attendanceStart)) {
            $lateMinutes = $attendanceStart->diffInMinutes($now);
            $lateHours = floor($lateMinutes / 60);
            $lateMins = $lateMinutes % 60;
            $lateFormatted = sprintf('%02dh %02dm', $lateHours, $lateMins);
        }

        $attendance = EngineerAttendances::create([
            'engineer_id' => $engineerId,
            'project_id' => $request->project_id,
            'check_in' => $now,
            'check_in_latitude' => $request->latitude,
            'check_in_longitude' => $request->longitude,
            'late' => $lateFormatted, // <-- Store formatted late time
            'date' => $today,
        ]);
        
          // ✅ Option 1: Keep status as 'P'
        \App\Models\User::where('id', $engineerId)->update(['attendance_status' => 'P']);

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful.',
            'data' => $attendance,
        ]);
    }

    // -------------------- CHECK-OUT --------------------
    if ($request->action === 'check_out') {
        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'No active check-in found for this project.'
            ], 400);
        }

        $checkIn = Carbon::parse($attendance->check_in, 'Asia/Kolkata');

        // Duration
        $diffInMinutes = $checkIn->diffInMinutes($now);
        $hours = floor($diffInMinutes / 60);
        $minutes = $diffInMinutes % 60;
        $durationFormatted = sprintf('%02dh %02dm', $hours, $minutes);
        $durationMinutes = $checkIn->diffInMinutes($now);

$expectedMinutes = $attendanceStart->diffInMinutes($attendanceEnd);

// ✅ Format expected as "07h 00m"
$expectedFormatted = sprintf('%02dh %02dm', floor($expectedMinutes / 60), $expectedMinutes % 60);

// --- Compare Actual vs Expected ---
$lateFormatted = null;
$overtimeFormatted = null;

if ($durationMinutes > $expectedMinutes) {
    // Employee worked more than expected -> overtime
    $overtime = $durationMinutes - $expectedMinutes;
    $overtimeFormatted = sprintf('%02dh %02dm', floor($overtime / 60), $overtime % 60);
} elseif ($durationMinutes < $expectedMinutes) {
    // Employee worked less -> late/short duration
    $late = $expectedMinutes - $durationMinutes;
    $lateFormatted = sprintf('%02dh %02dm', floor($late / 60), $late % 60);
}

        // Update record
        $attendance->update([
            'check_out' => $now,
            'check_out_latitude' => $request->latitude,
            'check_out_longitude' => $request->longitude,
            'attendance_type' => 'P',
            'duration' => $durationFormatted,
             'overtime' => $overtimeFormatted,
    'late' => $lateFormatted,
        ]);
         // ✅ Option 1: Keep status as 'P'
        \App\Models\User::where('id', $engineerId)->update(['attendance_status' => 'P']);

        return response()->json([
            'success' => true,
            'message' => 'Check-out successful.',
            'data' => [
                'attendance' => $attendance,
                'duration' => $durationFormatted,
                'late' => $attendance->late ?? '00h 00m',
                'overtime' => $overtimeFormatted,
            ],
        ]);
    }
}

public function getLatestEngineerAttendance(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
    ]);

    $engineerId = \Auth::id();
    $projectId = $request->project_id;

    $latestAttendance = \App\Models\EngineerAttendances::where('engineer_id', $engineerId)
        ->where('project_id', $projectId)
        ->latest('id')
        ->with('project')
        ->first();

    if (!$latestAttendance) {
        return response()->json([
            'success' => false,
            'message' => 'No attendance record found for this project.',
        ], 404);
    }

    $project = $latestAttendance->project;

    return response()->json([
        'success' => true,
        'message' => 'Latest attendance record retrieved successfully.',
        'data' => [
            'id' => $latestAttendance->id,
            'project_id' => $latestAttendance->project_id,
            'project_name' => $project->project_name ?? null,
            'site_address' => $project->site_address ?? null,
            'check_in' => $latestAttendance->check_in
                ? \Carbon\Carbon::parse($latestAttendance->check_in)->format('Y-m-d H:i:s')
                : 'N/A',
            'check_out' => $latestAttendance->check_out
                ? \Carbon\Carbon::parse($latestAttendance->check_out)->format('Y-m-d H:i:s')
                : 'N/A',
            'check_in_latitude' => $latestAttendance->check_in_latitude,
            'check_in_longitude' => $latestAttendance->check_in_longitude,
            'check_out_latitude' => $latestAttendance->check_out_latitude,
            'check_out_longitude' => $latestAttendance->check_out_longitude,
            'duration' => $latestAttendance->duration ?? '00h 00m',
            'late' => $latestAttendance->late ?? '00h 00m',
            'overtime' => $latestAttendance->overtime ?? '00h 00m',
            'attendance_type' => $latestAttendance->attendance_type ?? 'N/A',
        ],
    ]);
}

public function storeSiteReport(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
        'name_of_work' => 'required|string',
        'work_description' => 'nullable|string',
        'work_address' => 'nullable|string',
        'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx',
    ]);

    // create site report
    $siteReport = \App\Models\SiteReport::create([
        'project_id' => $request->project_id,
        'name_of_work' => $request->name_of_work,
        'work_description' => $request->work_description,
        'work_address' => $request->work_address,
        'date' => Carbon::today()->toDateString(),
        'created_by' => auth()->id(),
    ]);

    // save attachments with ORIGINAL NAME
    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {

            // avoid overwrite (optional but recommended)
            $filename = $file->getClientOriginalName();

            $path = $file->storeAs('site-reports', $filename, 'local');

            \App\Models\SiteReportAttachments::create([
                'site_reports_id' => $siteReport->id,
                'files' => $path,
            ]);
        }
    }

    return response()->json([
        'status' => 1,
        'message' => 'Site Report saved successfully'
    ]);
}

public function getSiteReportByProject(Request $request)
{
    $request->validate([
        'project_id' => 'required|integer',
        'date'       => 'nullable|date',
    ]);

    $user = Auth::user();

    $userProjectIds = is_array($user->project_id)
        ? $user->project_id
        : json_decode($user->project_id, true);

    if (!in_array($request->project_id, $userProjectIds)) {
        return response()->json([
            'status' => 0,
            'error' => 'Access denied for this project.'
        ], 403);
    }
    
   // ✅ USE ONE QUERY
    $query = \App\Models\SiteReport::with(['attachments', 'user'])
        ->where('project_id', $request->project_id);

    // ✅ Single date filter (using date column)
    if ($request->filled('date')) {
        $query->whereDate('date', $request->date);
    }

    $sitereport = $query->orderByDesc('id')->get()->map(function ($report) {
            return [
                 'id' => $report->id,
                'project_id' => $report->project_id,
                'name_of_work' => $report->name_of_work,
                'work_description' => $report->work_description,
                'work_address' => $report->work_address,
                'date' => $report->date
                ? \Carbon\Carbon::parse($report->date)->format('Y-m-d')
                : null,
                'uploaded_by' => $report->user->name ?? null,
                 'pdf_file_url'    => url("api/site-report/pdf/{$report->id}"),
                'attachments' => $report->attachments->map(function ($file) {
                    return [
                        'file' => asset(Storage::url($file->files))
                    ];
                }),
            ];
        });

    return response()->json([
        'status' => 1,
        'data' => $sitereport
    ]);
}



}
