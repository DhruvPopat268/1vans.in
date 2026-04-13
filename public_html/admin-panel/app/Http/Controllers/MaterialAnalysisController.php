<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaterialAnalysisController extends Controller
{
    public function index()
    {
                         $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage material record', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage material record'))
    );

    if ($hasPermission) {
            $user = \Auth::user();

            $materialcategory = \App\Models\MaterialCategory::withCount(['purchaseOrders','materialIncomings'])->where('project_id', $user->project_assign_id)->get();

            return view('material.analysis.index', compact('materialcategory'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    
        public function show($id)
{
    if (\Auth::user()->can('manage material incoming')) {
        $category = \App\Models\MaterialCategory::findOrFail($id);

        $query = \App\Models\MaterialIncoming::where('category_id', $category->id);

        if (request('from_date')) {
            $query->whereDate('date', '>=', request('from_date'));
        }

        if (request('to_date')) {
            $query->whereDate('date', '<=', request('to_date'));
        }
        
        $query->orderBy('date', 'desc');

        $materialincoming = $query->get();

        return view('material.analysis.show', compact('materialincoming','category'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

    public function materialIncomingshow($id)
    {
        if (\Auth::user()->can('show material incoming')) {
        $materialincoming = \App\Models\MaterialIncoming::with(['stocks.subCategory.category', 'user',
        'materialIncomingImage'
        ])->findOrFail($id);
        return view('material.analysis.incoming_show', compact('materialincoming'));
        } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
    }

public function downloadMaterialPdf($id)
    {
        if (\Auth::user()->can('download material incoming')) {
        // Fetch the material report with user and stocks (with subCategory and category)
        $materialincoming = \App\Models\MaterialIncoming::with(['user', 'stocks.subCategory.category','materialIncomingImage'])->findOrFail($id);

        // Signature
        $signaturePath = storage_path(($materialincoming->signature ?? 'abcd.png'));
        $signatureImg = file_exists($signaturePath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($signaturePath))
            : '';

        // User avatar
 $user = $materialincoming->user; // Fallback when Auth::user() is unavailable

// Get pdf_logo directly from logged-in user
$project = $materialincoming->project; // not nameOfWork if this is correct relation
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


       $incomingImages = [];
foreach ($materialincoming->materialIncomingImage as $image) {
    $imagePath = storage_path($image->image_path);
    if (file_exists($imagePath)) {
        $incomingImages[] = 'data:image/png;base64,' . base64_encode(file_get_contents($imagePath));
    }
}

        // Royalty Slip File (image preview)
$royaltySlipImg = '';
if (!empty($materialincoming->royalty_slip_file)) {
    $royaltySlipPath = storage_path($materialincoming->royalty_slip_file);
    $extension = strtolower(pathinfo($royaltySlipPath, PATHINFO_EXTENSION));

    if (in_array($extension, ['jpg', 'jpeg', 'png']) && file_exists($royaltySlipPath)) {
        $royaltySlipImg = 'data:image/' . $extension . ';base64,' . base64_encode(file_get_contents($royaltySlipPath));
    }
}

$ewayBillImg = '';
if (!empty($materialincoming->eway_bill_file)) {
    $ewayBillPath = storage_path($materialincoming->eway_bill_file);
    $extension = strtolower(pathinfo($ewayBillPath, PATHINFO_EXTENSION));

    if (in_array($extension, ['jpg', 'jpeg', 'png']) && file_exists($ewayBillPath)) {
        $ewayBillImg = 'data:image/' . $extension . ';base64,' . base64_encode(file_get_contents($ewayBillPath));
    }
}


        // Load view
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('material.analysis.pdf', compact('materialincoming', 'signatureImg', 'profileImg','incomingImages','royaltySlipImg','ewayBillImg'));
        return $pdf->download('Material Incoming Report.pdf');
        } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
    }
    
    public function downloadMaterialPdfApplication($id)
    {
       
        // Fetch the material report with user and stocks (with subCategory and category)
        $materialincoming = \App\Models\MaterialIncoming::with(['user', 'stocks.subCategory.category','materialIncomingImage'])->findOrFail($id);

        // Signature
        $signaturePath = storage_path(($materialincoming->signature ?? 'abcd.png'));
        $signatureImg = file_exists($signaturePath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($signaturePath))
            : '';

        // User avatar
 $user = $materialincoming->user; // Fallback when Auth::user() is unavailable

// Get pdf_logo directly from logged-in user
$project = $materialincoming->project; // not nameOfWork if this is correct relation
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


       $incomingImages = [];
foreach ($materialincoming->materialIncomingImage as $image) {
    $imagePath = storage_path($image->image_path);
    if (file_exists($imagePath)) {
        $incomingImages[] = 'data:image/png;base64,' . base64_encode(file_get_contents($imagePath));
    }
}

        // Royalty Slip File (image preview)
$royaltySlipImg = '';
if (!empty($materialincoming->royalty_slip_file)) {
    $royaltySlipPath = storage_path($materialincoming->royalty_slip_file);
    $extension = strtolower(pathinfo($royaltySlipPath, PATHINFO_EXTENSION));

    if (in_array($extension, ['jpg', 'jpeg', 'png']) && file_exists($royaltySlipPath)) {
        $royaltySlipImg = 'data:image/' . $extension . ';base64,' . base64_encode(file_get_contents($royaltySlipPath));
    }
}

$ewayBillImg = '';
if (!empty($materialincoming->eway_bill_file)) {
    $ewayBillPath = storage_path($materialincoming->eway_bill_file);
    $extension = strtolower(pathinfo($ewayBillPath, PATHINFO_EXTENSION));

    if (in_array($extension, ['jpg', 'jpeg', 'png']) && file_exists($ewayBillPath)) {
        $ewayBillImg = 'data:image/' . $extension . ';base64,' . base64_encode(file_get_contents($ewayBillPath));
    }
}


        // Load view
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('material.analysis.pdf', compact('materialincoming', 'signatureImg', 'profileImg','incomingImages','royaltySlipImg','ewayBillImg'));
        return $pdf->download('Material Incoming Report.pdf');
        
    }

    public function materialIncomingcreate($categoryId)
    {
        if (\Auth::user()->can('create material incoming')) {
            $user = \Auth::user();
            $projectId = $user->project_assign_id;

            // Ensure category belongs to the user's project
            $category = \App\Models\MaterialCategory::where('id', $categoryId)
                            ->where('project_id', $projectId)
                            ->firstOrFail();

            $subcategories = \App\Models\MaterialSubCategory::where('category_id', $category->id)->get();

            return view('material.analysis.create', [
                'category' => $category,
                'subcategories' => $subcategories,
            ]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }




      public function materialIncomingstore(Request $request)
    {
        if (!\Illuminate\Support\Facades\Auth::user()->can('create material incoming')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $request->validate([
            // 'project_id' => 'nullable|integer', // removed from validation
            'challan_number' => 'required|string',
            'location' => 'required|string',
            'bill_number' => 'nullable|string',
            'vehicle_number' => 'required|string',
            'description' => 'nullable|string',
            'vendor_name' => 'nullable|string',
            'gst_number' => 'nullable|string',
            'batch_number' => 'nullable|string',
            'eway_bill_no' => 'nullable|string',
            'eway_bill_file' => 'nullable|mimes:jpeg,png,jpg,pdf,doc,docx|max:10240',
            'royalty_slip_no' => 'nullable|string',
            'royalty_slip_file' => 'nullable|mimes:jpeg,png,jpg,pdf,doc,docx|max:10240',
            'remark' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'images' => 'nullable|array',
            'materials' => 'nullable|array',
            'materials.*.sub_category_id' => 'nullable|integer|exists:material_sub_categories,id',
            'materials.*.stock' => 'required|numeric|min:0',
                        'issue_status' => 'nullable|string',
            'comment' => 'nullable|string',
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();

        // Always use the logged-in user's assigned project ID
        $projectId = $user->project_assign_id;

        // Optional: Validate access
        $userProjectIds = [];

        if (!empty($user->project_id)) {
            $userProjectIds = is_array($user->project_id)
                ? $user->project_id
                : json_decode($user->project_id, true);
        }

        // Upload images
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('material/incoming_images', 'local');
            }
        }

        $ewayBillPath = null;
        if ($request->hasFile('eway_bill_file')) {
            $ewayBillPath = $request->file('eway_bill_file')->store('material/eway_bills', 'local');
        }

        $royaltySlipPath = null;
        if ($request->hasFile('royalty_slip_file')) {
            $royaltySlipPath = $request->file('royalty_slip_file')->store('material/royalty_slips', 'local');
        }


        // Create material report
        $categoryId = null;

        // Get category from the first material entry, if available
        if ($request->has('materials') && !empty($request->materials)) {
            $firstMaterial = $request->materials[0];
            if (!empty($firstMaterial['sub_category_id'])) {
                $subCategory = \App\Models\MaterialSubCategory::with('category')->find($firstMaterial['sub_category_id']);
                if ($subCategory) {
                    $categoryId = $subCategory->category_id;
                }
            }
        }

        $form = \App\Models\MaterialIncoming::create([
            'project_id' => $projectId,
            'challan_number' => $request->challan_number,
            'location' => $request->location,
            'vendor_name' => $request->vendor_name,
            'bill_number' => $request->bill_number,
            'vehicle_number' => $request->vehicle_number,
            'description' => $request->description,
            'remark' => $request->remark,
            'gst_number' => $request->gst_number,
            'batch_number' => $request->batch_number,
            'eway_bill_no' => $request->eway_bill_no,
            'eway_bill_file' => $ewayBillPath,
            'royalty_slip_no' => $request->royalty_slip_no,
            'royalty_slip_file' => $royaltySlipPath,

'issue_status' => $request->issue_status,
'comment' => $request->comment,
            'created_by' => $user->id,
            'date' => \Carbon\Carbon::today()->toDateString(),
            'category_id' => $categoryId,
        ]);

        // Save images
        foreach ($imagePaths as $path) {
            \App\Models\MaterialIncomingImages::create([
                'material_incomings_id' => $form->id,
                'image_path' => $path,
            ]);
        }

        // Save stock materials if present
        if ($request->has('materials')) {
            foreach ($request->materials as $material) {
                \App\Models\MaterialIncomingStock::create([
                    'material_incomings_id' => $form->id,
                    'sub_category_id' => $material['sub_category_id'],
                    'stock' => $material['stock'],
                ]);

                // Update stock
                $subcategory = \App\Models\MaterialSubCategory::find($material['sub_category_id']);
                $subcategory->total_stock += $material['stock'];
                $subcategory->save();
            }
        }

        return redirect()->back()->with('success', __('Material Incoming created successfully.'));
    }
    
        public function materialIncomingedit(\App\Models\MaterialIncoming $material_incoming)
    {
        if(\Auth::user()->can('edit material incoming'))
        {

            return view('material.analysis.edit',compact('material_incoming'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function materialIncomingupdate(Request $request, $id)
    {
        if (\Auth::user()->can('edit material incoming')) {
            $material_incoming = \App\Models\MaterialIncoming::find($id);

            if (!$material_incoming) {
                return redirect()->back()->with('error', 'Material Incoming not found.');
            }

            // Validate input
            $request->validate([
                'issue_status' => 'required|string|max:255',
                'comment' => 'nullable|string|max:255',
            ]);

            // Get user's assigned project
            $project_id = \Auth::user()->project_assign_id;

            if (!$project_id) {
                return redirect()->back()->with('error', 'No project assigned to the user.');
            }

            // Update fields
            $material_incoming->project_id = $project_id;
            $material_incoming->issue_status = $request->issue_status;

            // Set comment based on issue_status
            if ($request->issue_status === 'Yes') {
                $material_incoming->comment = $request->comment;
            } else {
                $material_incoming->comment = null;
            }

            $material_incoming->save();

            return redirect()->back()->with('success', __('Status Updated Successfully'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }
    
public function materialPurchaseOrderindex($id)
{
    if (\Auth::user()->can('manage material order')) {
        $category = \App\Models\MaterialCategory::findOrFail($id);

        $query = \App\Models\MaterialPurchaseOrder::where('category_id', $category->id);

        if (request('from_date')) {
            $query->whereDate('date', '>=', request('from_date'));
        }

        if (request('to_date')) {
            $query->whereDate('date', '<=', request('to_date'));
        }
        
        $query->orderBy('date', 'desc');

        $materialpurchase = $query->get();

        return view('material.analysis.purchase.index', compact('materialpurchase', 'category'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}
    public function materialPurchaseOrdershow($id)
    {
        if (\Auth::user()->can('show material order')) {
        $materialpurchase = \App\Models\MaterialPurchaseOrder::with(['stocks.subCategory.category', 'user'
        ])->findOrFail($id);
        return view('material.analysis.purchase.show', compact('materialpurchase'));
        } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
    }


        public function materialPurchaseOrdercreate($categoryId)
    {
        if (\Auth::user()->can('create material order')) {
            $user = \Auth::user();
            $projectId = $user->project_assign_id;

            // Ensure category belongs to the user's project
            $category = \App\Models\MaterialCategory::where('id', $categoryId)
                            ->where('project_id', $projectId)
                            ->firstOrFail();

            $subcategories = \App\Models\MaterialSubCategory::where('category_id', $category->id)->get();

            return view('material.analysis.purchase.create', [
                'category' => $category,
                'subcategories' => $subcategories,
            ]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }




      public function materialPurchaseOrderstore(Request $request)
    {
        if (!\Illuminate\Support\Facades\Auth::user()->can('create material order')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $request->validate([
            // 'project_id' => 'nullable|integer', // removed from validation
            'location' => 'required|string',
            'description' => 'nullable|string',
            'vendor_name' => 'nullable|string',
            'materials' => 'nullable|array',
            'materials.*.sub_category_id' => 'nullable|integer|exists:material_sub_categories,id',
            'materials.*.stock' => 'required|numeric|min:0',

        ]);

        $user = \Illuminate\Support\Facades\Auth::user();

        // Always use the logged-in user's assigned project ID
        $projectId = $user->project_assign_id;

        // Optional: Validate access
        $userProjectIds = [];

        if (!empty($user->project_id)) {
            $userProjectIds = is_array($user->project_id)
                ? $user->project_id
                : json_decode($user->project_id, true);
        }


        // Create material report
        $categoryId = null;

        // Get category from the first material entry, if available
        if ($request->has('materials') && !empty($request->materials)) {
            $firstMaterial = $request->materials[0];
            if (!empty($firstMaterial['sub_category_id'])) {
                $subCategory = \App\Models\MaterialSubCategory::with('category')->find($firstMaterial['sub_category_id']);
                if ($subCategory) {
                    $categoryId = $subCategory->category_id;
                }
            }
        }

        $form = \App\Models\MaterialPurchaseOrder::create([
            'project_id' => $projectId,
            'location' => $request->location,
            'vendor_name' => $request->vendor_name,
            'description' => $request->description,
            'status' => 'Pending',
            'created_by' => $user->id,
            'date' => \Carbon\Carbon::today()->toDateString(),
            'category_id' => $categoryId,
        ]);

        // Save stock materials if present
        if ($request->has('materials')) {
            foreach ($request->materials as $material) {
                \App\Models\MaterialPurchaseOrderStock::create([
                    'material_purchase_orders_id' => $form->id,
                    'sub_category_id' => $material['sub_category_id'],
                    'stock' => $material['stock'],
                ]);
            }
        }

        return redirect()->back()->with('success', __('Material Order created successfully.'));
    }

        public function materialPurchaseOrderedit(\App\Models\MaterialPurchaseOrder $purchase_order)
    {
        if(\Auth::user()->can('edit material order'))
        {

            return view('material.analysis.purchase.edit',compact('purchase_order'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function materialPurchaseOrderupdate(Request $request, $id)
    {
        if (\Auth::user()->can('edit material order')) {
            $purchase_order = \App\Models\MaterialPurchaseOrder::find($id);

            if (!$purchase_order) {
                return redirect()->back()->with('error', 'Material Order not found.');
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

            // Update fields
            $purchase_order->project_id = $project_id;
            $purchase_order->status = $request->status;


            $purchase_order->save();

            return redirect()->back()->with('success', __('Status Updated Successfully'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

public function downloadMaterialPurchaseOrderPdf($id)
    {
        if (\Auth::user()->can('download material order')) {
        // Fetch the material report with user and stocks (with subCategory and category)
        $materialpurchase = \App\Models\MaterialPurchaseOrder::with(['user', 'stocks.subCategory.category'])->findOrFail($id);

        // Signature
        $signaturePath = storage_path(($materialpurchase->signature ?? 'abcd.png'));
        $signatureImg = file_exists($signaturePath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($signaturePath))
            : '';

        // // User avatar
        // $user = \Illuminate\Support\Facades\Auth::user();
        // $avatarUrl = $user->avatar ?? null;

        // $profilePath = $avatarUrl
        //     ? storage_path('uploads/avatar/' . str_replace('/storage/', '', parse_url($avatarUrl, PHP_URL_PATH)))
        //     : storage_path('uploads/avatar/abcd.png');

        // $profileImg = file_exists($profilePath)
        //     ? 'data:image/png;base64,' . base64_encode(file_get_contents($profilePath))
        //     : '';
        
 $user = $materialpurchase->user; // Fallback when Auth::user() is unavailable

// Get pdf_logo directly from logged-in user
$project = $materialpurchase->project; // not nameOfWork if this is correct relation
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




        // Load view
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('material.analysis.purchase.pdf', compact('materialpurchase', 'signatureImg', 'profileImg'));
        return $pdf->download('Material Order Report.pdf');
        } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
    }
    
    public function downloadMaterialPurchaseOrderPdfApplication($id)
    {
       
        // Fetch the material report with user and stocks (with subCategory and category)
        $materialpurchase = \App\Models\MaterialPurchaseOrder::with(['user', 'stocks.subCategory.category'])->findOrFail($id);

        // Signature
        $signaturePath = storage_path(($materialpurchase->signature ?? 'abcd.png'));
        $signatureImg = file_exists($signaturePath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($signaturePath))
            : '';

        // // User avatar
        // $user = \Illuminate\Support\Facades\Auth::user();
        // $avatarUrl = $user->avatar ?? null;

        // $profilePath = $avatarUrl
        //     ? storage_path('uploads/avatar/' . str_replace('/storage/', '', parse_url($avatarUrl, PHP_URL_PATH)))
        //     : storage_path('uploads/avatar/abcd.png');

        // $profileImg = file_exists($profilePath)
        //     ? 'data:image/png;base64,' . base64_encode(file_get_contents($profilePath))
        //     : '';
        
 $user = $materialpurchase->user; // Fallback when Auth::user() is unavailable

// Get pdf_logo directly from logged-in user
$project = $materialpurchase->project; // not nameOfWork if this is correct relation
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




        // Load view
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('material.analysis.purchase.pdf', compact('materialpurchase', 'signatureImg', 'profileImg'));
        return $pdf->download('Material Order Report.pdf');
       
    }
    
}
