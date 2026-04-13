<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AttendanceEmployee;
use App\Models\BankAccount;
use App\Models\Bill;
use App\Models\Bug;
use App\Models\BugStatus;
use App\Models\Contract;
use App\Models\Deal;
use App\Models\DealTask;
use App\Models\Employee;
use App\Models\Event;
use App\Models\Expense;
use App\Models\Goal;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\Lead;
use App\Models\LeadStage;
use App\Models\Meeting;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Pos;
use App\Models\ProductServiceCategory;
use App\Models\ProductServiceUnit;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\Purchase;
use App\Models\Revenue;
use App\Models\Stage;
use App\Models\Tax;
use App\Models\Timesheet;
use App\Models\TimeTracker;
use App\Models\Trainer;
use App\Models\Training;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;


class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }


    public function landingPage()
    {
        if (!file_exists(storage_path() . "/installed")) {
            header('location:install');
            die;
        }

        $adminSettings = Utility::settings();
        if ($adminSettings['display_landing_page'] == 'on' && \Schema::hasTable('landing_page_settings')) {

            return view('landingpage::layouts.landingpage' , compact('adminSettings'));

        } else {
            return redirect('login');
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function account_dashboard_index()
    {

        if (Auth::check()) {

            if (Auth::user()->type == 'super admin') {
                return redirect()->route('client.dashboard.view');
            } elseif (Auth::user()->type == 'client') {
                return redirect()->route('client.dashboard.view');
            } else {
                if (\Auth::user()->can('show account dashboard')) {
                    $data['latestIncome'] = Revenue::with(['customer'])->where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();
                    $data['latestExpense'] = Payment::with(['vender'])->where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();
                    $currentYer = date('Y');

                    $incomeCategory = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())
                        ->where('type', '=', 'income')->get();

                    $inColor = array();
                    $inCategory = array();
                    $inAmount = array();
                    for ($i = 0; $i < count($incomeCategory); $i++) {
                        $inColor[] = '#' . $incomeCategory[$i]->color;
                        $inCategory[] = $incomeCategory[$i]->name;
                        $inAmount[] = $incomeCategory[$i]->incomeCategoryRevenueAmount();
                    }

                    $data['incomeCategoryColor'] = $inColor;
                    $data['incomeCategory'] = $inCategory;
                    $data['incomeCatAmount'] = $inAmount;

                    $expenseCategory = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())
                        ->where('type', '=', 'expense')->get();
                    $exColor = array();
                    $exCategory = array();
                    $exAmount = array();
                    for ($i = 0; $i < count($expenseCategory); $i++) {
                        $exColor[] = '#' . $expenseCategory[$i]->color;
                        $exCategory[] = $expenseCategory[$i]->name;
                        $exAmount[] = $expenseCategory[$i]->expenseCategoryAmount();
                    }

                    $data['expenseCategoryColor'] = $exColor;
                    $data['expenseCategory'] = $exCategory;
                    $data['expenseCatAmount'] = $exAmount;

                    $data['incExpBarChartData'] = \Auth::user()->getincExpBarChartData();
                    //                dd( $data['incExpBarChartData']);
                    $data['incExpLineChartData'] = \Auth::user()->getIncExpLineChartDate();

                    $data['currentYear'] = date('Y');
                    $data['currentMonth'] = date('M');

                    $constant['taxes'] = Tax::where('created_by', \Auth::user()->creatorId())->count();
                    $constant['category'] = ProductServiceCategory::where('created_by', \Auth::user()->creatorId())->count();
                    $constant['units'] = ProductServiceUnit::where('created_by', \Auth::user()->creatorId())->count();
                    $constant['bankAccount'] = BankAccount::where('created_by', \Auth::user()->creatorId())->count();
                    $data['constant'] = $constant;
                    $data['bankAccountDetail'] = BankAccount::where('created_by', '=', \Auth::user()->creatorId())->limit(5)->get();
                    $data['recentInvoice'] = Invoice::join('customers', 'invoices.customer_id', '=', 'customers.id')
                        ->where('invoices.created_by', '=', \Auth::user()->creatorId())
                        ->orderBy('invoices.id', 'desc')
                        ->limit(5)
                        ->select('invoices.*', 'customers.name as customer_name')
                        ->get();

                    $data['weeklyInvoice'] = \Auth::user()->weeklyInvoice();
                    $data['monthlyInvoice'] = \Auth::user()->monthlyInvoice();
                    $data['recentBill'] = Bill::join('venders', 'bills.vender_id', '=', 'venders.id')
                    ->where('bills.created_by', '=', \Auth::user()->creatorId())
                    ->orderBy('bills.id', 'desc')
                    ->limit(5)
                    ->select('bills.*', 'venders.name as vender_name')
                    ->get();

                    $data['weeklyBill'] = \Auth::user()->weeklyBill();
                    $data['monthlyBill'] = \Auth::user()->monthlyBill();
                    $data['goals'] = Goal::where('created_by', '=', \Auth::user()->creatorId())->where('is_display', 1)->get();

                    //Storage limit
                    $data['users'] = User::find(\Auth::user()->creatorId());
                    $data['plan'] = Plan::getPlan(\Auth::user()->show_dashboard());
                    if ($data['plan']->storage_limit > 0) {
                        $data['storage_limit'] = ($data['users']->storage_limit / $data['plan']->storage_limit) * 100;
                    } else {
                        $data['storage_limit'] = 0;
                    }

                    // dd($data);
                    return view('dashboard.account-dashboard', $data);
                } else {

                    return $this->hrm_dashboard_index();
                }

            }
        } else {
                return redirect('login');

            }
        }


//     public function project_dashboard_index()
//     {
//         $user = Auth::user();

//         if (\Auth::user()->can('show project dashboard')) {
//             if ($user->type == 'admin') {
//                 return view('admin.dashboard');
//             } else {
//                 $home_data = [];
// //                dd($user->projects());

//                 $user_projects = $user->projects()->pluck('project_id')->toArray();

//                 $project_tasks = ProjectTask::whereIn('project_id', $user_projects)->get();
//                 $project_expense = Expense::whereIn('project_id', $user_projects)->get();
//                 $seven_days = Utility::getLastSevenDays();

//                 // Total Projects
//                 $complete_project = $user->projects()->where('status', 'LIKE', 'complete')->count();
//                 $home_data['total_project'] = [
//                     'total' => count($user_projects),
//                     'percentage' => Utility::getPercentage($complete_project, count($user_projects)),
//                 ];

//                 // Total Tasks
//                 $complete_task = ProjectTask::where('is_complete', '=', 1)->whereRaw("find_in_set('" . $user->id . "',assign_to)")->whereIn('project_id', $user_projects)->count();
//                 $home_data['total_task'] = [
//                     'total' => $project_tasks->count(),
//                     'percentage' => Utility::getPercentage($complete_task, $project_tasks->count()),
//                 ];

//                 // Total Expense
//                 $total_expense = 0;
//                 $total_project_amount = 0;
//                 foreach ($user->projects as $pr) {
//                     $total_project_amount += $pr->budget;
//                 }
//                 foreach ($project_expense as $expense) {
//                     $total_expense += $expense->amount;
//                 }
//                 $home_data['total_expense'] = [
//                     'total' => $project_expense->count(),
//                     'percentage' => Utility::getPercentage($total_expense, $total_project_amount),
//                 ];

//                 // Total Users
//                 $home_data['total_user'] = Auth::user()->contacts->count();

//                 // Tasks Overview Chart & Timesheet Log Chart
//                 $task_overview = [];
//                 $timesheet_logged = [];
//                 foreach ($seven_days as $date => $day) {
//                     // Task
//                     $task_overview[$day] = ProjectTask::where('is_complete', '=', 1)->where('marked_at', 'LIKE', $date)->whereIn('project_id', $user_projects)->count();

//                     // Timesheet
//                     $time = Timesheet::whereIn('project_id', $user_projects)->where('date', 'LIKE', $date)->pluck('time')->toArray();
//                     $timesheet_logged[$day] = str_replace(':', '.', Utility::calculateTimesheetHours($time));
//                 }

//                 $home_data['task_overview'] = $task_overview;
//                 $home_data['timesheet_logged'] = $timesheet_logged;

//                 // Project Status
//                 $total_project = count($user_projects);

//                 $project_status = [];
//                 foreach (Project::$project_status as $k => $v) {

//                     $project_status[$k]['total'] = $user->projects->where('status', 'LIKE', $k)->count();
// //                    dd($project_status[$k]['total']    );
//                     $project_status[$k]['percentage'] = Utility::getPercentage($project_status[$k]['total'], $total_project);
//                 }
//                 $home_data['project_status'] = $project_status;

//                 // Top Due Project
//                 $home_data['due_project'] = $user->projects()->orderBy('end_date', 'DESC')->limit(5)->get();

//                 // Top Due Tasks
//                 $home_data['due_tasks'] = ProjectTask::where('is_complete', '=', 0)->whereIn('project_id', $user_projects)->orderBy('end_date', 'DESC')->limit(5)->get();

//                 $home_data['last_tasks'] = ProjectTask::whereIn('project_id', $user_projects)->orderBy('end_date', 'DESC')->limit(5)->get();

//                 return view('dashboard.project-dashboard', compact('home_data'));
//             }
//         } else {

//             return $this->account_dashboard_index();
//         }
//     }

    public function project_dashboard_index(Request $request)
    {
        $user = Auth::user();

        if ($user->can('show project dashboard')) {
            if ($user->type == 'admin') {
                return view('admin.dashboard');
            } else {
                $home_data = [];

                // Convert assigned project IDs into array (assuming it's stored as JSON or CSV)
                $projectId = $user->project_assign_id;
                $project = \App\Models\Project::find($user->project_assign_id);
                $projectName = $project ? $project->project_name : 'No Selected project';

                // Get total projects
                $home_data['total_project'] = [
                    'total' => Project::where('created_by', $user->creatorId())->count(),
                ];
                $totalProjects = Project::where('created_by', $user->creatorId())->count();

                $home_data['total_project'] = [
                    'total' => $totalProjects,
                ];

                // If total project is 0, flash session to show modal
                if ($totalProjects == 0) {
                    session()->flash('showClientModal', true);
                }

                // Project status distribution
                $projectStatus = Project::where('created_by', $user->creatorId())
                    ->select('status')
                    ->get()
                    ->groupBy('status');

                $home_data['project_status'] = [];
                $totalProjects = max($home_data['total_project']['total'], 1); // Prevent division by zero

                foreach ($projectStatus as $status => $projects) {
                    $count = count($projects);
                    $home_data['project_status'][$status] = [
                        'total' => $count,
                        'percentage' => round(($count / $totalProjects) * 100, 2),
                    ];
                }

                // Get total working drawings (assuming 'type' column = 'working' in Drawings model)
                $home_data['total_working_drawings'] = \App\Models\Drawings::where('project_id', $projectId)
                    ->count();

                $home_data['total_project_documents'] = \App\Models\ProjectDocument::where('project_id', $projectId)
                    ->count();

                $home_data['total_material_testing_reports'] = \App\Models\MaterialTestingReports::where('project_id', $projectId)
                    ->count();

                $home_data['total_bill_of_quantity'] = \App\Models\BillOfQuantity::where('project_id', $projectId)
                    ->count();

                //    $home_data['total_equipment_form'] = \App\Models\EquipmentFormItem::whereHas('equipment', function ($query) use ($user) {
                //          $query->where('project_id', $user->project_assign_id);
                //      })->distinct('equipment_form_id')->count('equipment_form_id');

                $home_data['total_equipment_form'] = \App\Models\EquipmentForm::where('project_id', $projectId)
                    ->count();

                $home_data['total_material_analysis'] = \App\Models\MaterialCategory::where('project_id', $projectId)
                    ->count();

                $home_data['total_work_issue'] = \App\Models\WorkIssue::where('project_id', $projectId)
                    ->count();

                $selectedYear = request('year', date('Y'));
                $selectedType = request('work_type');
                $home_data['work_types'] = \App\Models\DailyReportMainCategory::where('project_id', $projectId)->get();

                $worksQuery = \App\Models\NameOfWork::with('mainCategory')
                    ->where('project_id', $projectId);

                if (! empty($selectedType)) {
                    $worksQuery->where('daily_report_main_category_id', $selectedType);
                }

                $works = $worksQuery->get();

                $ganttData = [];

                foreach ($works as $work) {

                    $today = \Carbon\Carbon::today();
                    $start = \Carbon\Carbon::parse($work->start_date);
                    $end = \Carbon\Carbon::parse($work->end_date);

                    $yearStart = \Carbon\Carbon::create($selectedYear, 1, 1);
                    $yearEnd = \Carbon\Carbon::create($selectedYear, 12, 31);

                    // ✅ Skip જો work આ year માં નથી
                    if ($end < $yearStart || $start > $yearEnd) {
                        continue;
                    }

                    // ✅ Clamp dates અંદર selected year
                    $visibleStart = $start < $yearStart ? $yearStart : $start;
                    $visibleEnd = $end > $yearEnd ? $yearEnd : $end;

                    $startMonth = $visibleStart->month;
                    $endMonth = $visibleEnd->month;

                    $span = max(($endMonth - $startMonth) + 1, 1);

                    // Progress logic (same as before)
                    $reports = \App\Models\DailyReport::with('measurements')
                        ->where('name_of_work_id', $work->id)
                        ->get();

                    $usedMeasurement = 0;

                    foreach ($reports as $report) {
                        foreach ($report->measurements as $m) {
                            $usedMeasurement += (float) ($m->mesurements_value ?? 0);
                        }
                    }

                    $totalMeasurement = (float) ($work->total_mesurement ?? 0);

                    $progressPercent = $totalMeasurement > 0
                        ? min(($usedMeasurement / $totalMeasurement) * 100, 100)
                        : 0;

                    $work->progress_percent = round($progressPercent, 1);

                    $plannedDays = (int) $start->diffInDays($end) + 1;

                    /* ✅ Actual Days = Remaining Days */
                    if ($work->progress_percent >= 100) {

                        $actualDays = 0;

                    } else {

                        if ($today <= $end) {

                            $actualDays = (int) $today->diffInDays($end);

                        } else {

                            $actualDays = 0;
                        }
                    }

                    /* ✅ Delay Days */
                    if ($work->progress_percent >= 100) {

                        $delayDays = 0;

                    } else {

                        if ($today > $end) {

                            $delayDays = (int) $end->diffInDays($today);

                        } else {

                            $delayDays = 0;
                        }
                    }

                    $work->planned_days = $plannedDays;
                    $work->actual_days = $actualDays;
                    $work->delay_days = $delayDays;
                    $work->startMonth = $startMonth;
                    $work->span = $span;

                    $categoryId = $work->daily_report_main_category_id;

                    $ganttData[$categoryId]['category'] = optional($work->mainCategory)->name ?? 'Uncategorized';
                    $ganttData[$categoryId]['works'][] = $work;
                }

                $home_data['gantt'] = $ganttData;
                $home_data['selected_year'] = $selectedYear;

                $home_data['total_man_power'] = \App\Models\ManPower::where('project_id', $projectId)
                    ->count();

                $home_data['total_daily_report'] = \App\Models\DailyReport::where('project_id', $projectId)
                    ->count();

                $home_data['total_daily_report_name_of_work'] = \App\Models\DailyReport::where('project_id', $user->project_assign_id)
                    ->distinct('name_of_work_id')
                    ->count('name_of_work_id');

                $home_data['total_engineer'] = \App\Models\User::where('created_by', $user->creatorId())->where('type', 'app user')
                    ->count();

                $home_data['weather'] = [];

                if ($request->filled('lat') && $request->filled('lon')) {
                    $location = "{$request->lat},{$request->lon}";

                    try {
                        // Real-time current weather
                        $currentResponse = Http::get('http://api.weatherapi.com/v1/current.json', [
                            'key' => env('WEATHER_API_KEY'),
                            'q' => $location,
                            'aqi' => 'no',
                        ]);

                        // Forecast for 4 days
                        $forecastResponse = Http::get('http://api.weatherapi.com/v1/forecast.json', [
                            'key' => env('WEATHER_API_KEY'),
                            'q' => $location,
                            'days' => 4,
                            'aqi' => 'no',
                            'alerts' => 'no',
                        ]);

                        if ($currentResponse->successful()) {
                            $home_data['weather']['current'] = $currentResponse->json()['current'];
                        }

                        if ($forecastResponse->successful()) {
                            $forecast = $forecastResponse->json()['forecast']['forecastday'];
                            $home_data['weather'] += [
                                'day_1' => $forecast[0] ?? null,
                                'day_2' => $forecast[1] ?? null,
                                'day_3' => $forecast[2] ?? null,
                                'day_4' => $forecast[3] ?? null,
                            ];
                        }

                    } catch (\Exception $e) {
                        // silently fail
                    }
                }

                // Add project status logic here if needed...
                
                 $sliderImages = \App\Models\DailyReportImages::whereHas('dailyReport', function ($q) use ($projectId) {
        $q->where('project_id', $projectId);
    })
    ->orderBy('id', 'desc')
    ->take(10)
    ->get()
    ->map(function ($img) {
        return asset('storage/' . $img->image_path);
    });

                return view('dashboard.project-dashboard', compact('home_data', 'projectName', 'project','sliderImages'));
            }
        } else {
            return $this->account_dashboard_index();
        }
    }
    public function markAsRead($id)
{
    $notification = \App\Models\WebNotification::find($id);

    if ($notification) {
        $notification->status = 'Read';
        $notification->save();

        // Redirect based on key
        switch ($notification->key) {
            case '1':
                return redirect()->route('todo.task.index', ['id' => $notification->report_id]);
            case '2':
                return redirect()->route('daily-report.details', ['id' => $notification->report_id]);
            case '3':
                return redirect()->route('equipment.report.show', ['id' => $notification->report_id]);
            case '4':
                return redirect()->route('work-issue.show', $notification->report_id);
            case '5':
                return redirect()->route('material.purchase.order.show', ['id' => $notification->report_id]); // ✅ Corrected
            case '6':
    return redirect()->route('material.incoming.show', ['id' => $notification->report_id]); 
            // Add more cases here as needed
            default:
                return redirect($notification->link ?? '/');
        }
    }

    return redirect('/'); // fallback
}

public function destroyWebNotification($id)
{
    $notification = \App\Models\WebNotification::find($id);

    if ($notification) {
        $notification->delete();
        return back()->with('success', 'Notification deleted successfully.');
    }

    return back()->with('error', 'Notification not found.');
}

public function deleteAllNotifications()
{
    $user = auth()->user();

    \App\Models\WebNotification::where('project_id', $user->project_assign_id)->delete();

    return back()->with('success', 'All notifications deleted successfully.');
}


//   public function graph_dashboard_index(Request $request)
// {
//     $user = Auth::user();

//     if ($user->can('show project dashboard')) {
//         if ($user->type == 'admin') {
//             return view('admin.dashboard');
//         } else {
//             $home_data = [];

//             $projectId = $user->project_assign_id;
//             $mainCategoryId = $request->get('main_category_id');

//             /** ---------------- CHARTS ---------------- **/
//             $categories = \App\Models\MaterialCategory::where('project_id', $projectId)->get();

//             $materialUsedChart = [];
//             foreach ($categories as $category) {
//                 $usedStock = \App\Models\MaterialSubCategory::where('category_id', $category->id)->sum('used_stock');
//                 $totalStock = \App\Models\MaterialSubCategory::where('category_id', $category->id)->sum('total_stock');

//                 $materialUsedChart[] = [
//                     'name' => $category->name,
//                     'used_stock' => $usedStock,
//                     'total_stock' => $totalStock,
//                 ];
//             }
//             $home_data['material_used_chart'] = $materialUsedChart;

//             $equipmentAmounts = \App\Models\Equipment::where('project_id', $projectId)
//                 ->withSum(['equipmentFormItems' => function ($query) use ($projectId) {
//                     $query->where('project_id', $projectId);
//                 }], 'total_amount')
//                 ->get();

//             $equipmentChart = $equipmentAmounts->map(function ($equipment) {
//                 return [
//                     'name' => $equipment->name,
//                     'total_amount' => $equipment->equipment_form_items_sum_total_amount ?? 0,
//                 ];
//             });
//             $home_data['equipment_chart'] = $equipmentChart;

//             $manpowerData = \App\Models\ManPower::where('project_id', $projectId)
//                 ->select('name', 'price', 'total_person')
//                 ->get()
//                 ->map(function ($mp) {
//                     $totalAmount = ($mp->price ?? 0) * ($mp->total_person ?? 0);
//                     return [
//                         'name' => $mp->name,
//                         'price' => $mp->price ?? 0,
//                         'total_person' => $mp->total_person ?? 0,
//                         'total_amount' => $totalAmount,
//                     ];
//                 });
//             $home_data['manpower_chart'] = $manpowerData;

//             $project = \App\Models\Project::find($projectId);
//             $projectBudget = $project->budget ?? 0;

//             // Material Amount
//             $materialAmount = \App\Models\MaterialSubCategory::join('material_categories', 'material_sub_categories.category_id', '=', 'material_categories.id')
//                 ->where('material_categories.project_id', $projectId)
//                 ->selectRaw('SUM(material_sub_categories.used_stock * material_sub_categories.price) as total')
//                 ->value('total') ?? 0;

//             // Equipment Amount
//             $equipmentAmount = \App\Models\EquipmentFormItem::join('equipment', 'equipment_form_items.equipment_id', '=', 'equipment.id')
//                 ->where('equipment.project_id', $projectId)
//                 ->sum('equipment_form_items.total_amount');

//             // ManPower Amount
//             $manpowerAmount = \App\Models\ManPower::where('project_id', $projectId)
//                 ->selectRaw('SUM(price * total_person) as total')
//                 ->value('total') ?? 0;

//             $totalUsedAmount = $materialAmount + $equipmentAmount + $manpowerAmount;

//             $home_data['budget_chart'] = [
//                 'budget' => $projectBudget,
//                 'used' => $totalUsedAmount,
//                 'material_amount' => $materialAmount,
//                 'equipment_amount' => $equipmentAmount,
//                 'manpower_amount' => $manpowerAmount,
//             ];

//             /** ---------------- SUMMARY COUNTS ---------------- **/
//             $dailyreportQuery = \App\Models\DailyReport::with([
//                 'nameOfWork',
//                 'manpowers.manPower',
//                 'materials.subCategory.category',
//                 'equipments.equipment',
//                 'measurements'
//             ])->where('project_id', $user->project_assign_id);

//             // Apply filter if selected
//             if (!empty($mainCategoryId)) {
//                 $dailyreportQuery->whereHas('nameOfWork.mainCategory', function ($q) use ($mainCategoryId) {
//                     $q->where('id', $mainCategoryId);
//                 });
//             }

//             $dailyreport = $dailyreportQuery->get()->groupBy('name_of_work_id');

//             $mainCategories = \App\Models\DailyReportMainCategory::where('project_id', $user->project_assign_id)->get();

//             // Work counters
//             $totalWorks = 0;
//             $pendingWorks = 0;
//             $completedWorks = 0;

//             foreach ($dailyreport as $nameOfWorkId => $reports) {
//                 $usedMeasurement = 0;
//                 foreach ($reports as $report) {
//                     foreach ($report->measurements as $m) {
//                         $usedMeasurement += (float) ($m->mesurements_value ?? 0);
//                     }
//                 }

//                 $totalMeasurement = $reports[0]->nameOfWork->total_mesurement ?? 0;

//                 if ($totalMeasurement > 0) {
//                     $totalWorks++;
//                     if ($usedMeasurement >= $totalMeasurement) {
//                         $completedWorks++;
//                     } else {
//                         $pendingWorks++;
//                     }
//                 }
//             }

//             $home_data['total_works'] = $totalWorks;
//             $home_data['total_pending'] = $pendingWorks;
//             $home_data['total_completed'] = $completedWorks;

//             return view('dashboard.graph-dashboard', compact(
//                 'home_data',
//                 'dailyreport',
//                 'mainCategories',
//                 'mainCategoryId'
//             ));
//         }
//     } else {
//         return $this->account_dashboard_index();
//     }
// }

    public function graph_dashboard_index(Request $request)
    {
        $user = Auth::user();

        if ($user->can('show project dashboard')) {
            if ($user->type == 'admin') {
                return view('admin.dashboard');
            } else {
                $home_data = [];

                $projectId = $user->project_assign_id;
                $mainCategoryId = $request->get('main_category_id');

                /** ---------------- CHARTS ---------------- **/
                $categories = \App\Models\MaterialCategory::where('project_id', $projectId)->get();

                $materialUsedChart = [];
                foreach ($categories as $category) {
                    $usedStock = \App\Models\MaterialSubCategory::where('category_id', $category->id)->sum('used_stock');
                    $totalStock = \App\Models\MaterialSubCategory::where('category_id', $category->id)->sum('total_stock');

                    $materialUsedChart[] = [
                        'name' => $category->name,
                        'used_stock' => $usedStock,
                        'total_stock' => $totalStock,
                    ];
                }
                $home_data['material_used_chart'] = $materialUsedChart;

                $equipmentAmounts = \App\Models\Equipment::where('project_id', $projectId)
                    ->withSum(['equipmentFormItems' => function ($query) use ($projectId) {
                        $query->where('project_id', $projectId);
                    }], 'total_amount')
                    ->get();

                $equipmentChart = $equipmentAmounts->map(function ($equipment) {
                    return [
                        'name' => $equipment->name,
                        'total_amount' => $equipment->equipment_form_items_sum_total_amount ?? 0,
                    ];
                });
                $home_data['equipment_chart'] = $equipmentChart;

                $manpowerData = \App\Models\ManPower::where('project_id', $projectId)
                    ->select('name', 'price', 'total_person')
                    ->get()
                    ->map(function ($mp) {
                        $totalAmount = ($mp->price ?? 0) * ($mp->total_person ?? 0);

                        return [
                            'name' => $mp->name,
                            'price' => $mp->price ?? 0,
                            'total_person' => $mp->total_person ?? 0,
                            'total_amount' => $totalAmount,
                        ];
                    });
                $home_data['manpower_chart'] = $manpowerData;

                $project = \App\Models\Project::find($projectId);

                $startDate = $project->start_date ? \Carbon\Carbon::parse($project->start_date) : null;
                $endDate = $project->end_date ? \Carbon\Carbon::parse($project->end_date) : null;

                $daysLeft = 0;
                $progressPercent = 0;

                if ($startDate && $endDate) {

                    $totalDays = $startDate->diffInDays($endDate);
                    $elapsedDays = $startDate->diffInDays(now());

                    $daysLeft = ceil(now()->diffInSeconds($endDate) / 86400);

                    if ($totalDays > 0) {
                        $progressPercent = min(100, ($elapsedDays / $totalDays) * 100);
                    }
                }

                $home_data['time_completion'] = [
                    'start_date' => $startDate ? $startDate->format('F d, Y') : '-',
                    'end_date' => $endDate ? $endDate->format('F d, Y') : '-',
                    'days_left' => $daysLeft,
                    'progress' => round($progressPercent),
                ];

                $projectBudget = $project->budget ?? 0;

                // Material Amount
                $materialAmount = \App\Models\MaterialSubCategory::join('material_categories', 'material_sub_categories.category_id', '=', 'material_categories.id')
                    ->where('material_categories.project_id', $projectId)
                    ->selectRaw('SUM(material_sub_categories.used_stock * material_sub_categories.price) as total')
                    ->value('total') ?? 0;

                // Equipment Amount
                $equipmentAmount = \App\Models\EquipmentFormItem::join('equipment', 'equipment_form_items.equipment_id', '=', 'equipment.id')
                    ->where('equipment.project_id', $projectId)
                    ->sum('equipment_form_items.total_amount');

                // ManPower Amount
                $manpowerAmount = \App\Models\ManPower::where('project_id', $projectId)
                    ->selectRaw('SUM(price * total_person) as total')
                    ->value('total') ?? 0;

                $totalUsedAmount = $materialAmount + $equipmentAmount + $manpowerAmount;

                $overrunAmount = max(0, $totalUsedAmount - $projectBudget);

                $home_data['cost_overrun'] = [
                    'amount' => $overrunAmount,
                ];

                $home_data['budget_chart'] = [
                    'budget' => $projectBudget,
                    'used' => $totalUsedAmount,
                    'material_amount' => $materialAmount,
                    'equipment_amount' => $equipmentAmount,
                    'manpower_amount' => $manpowerAmount,
                ];

                /** ---------------- SUMMARY COUNTS ---------------- **/
                $dailyreportQuery = \App\Models\DailyReport::with([
                    'nameOfWork',
                    'manpowers.manPower',
                    'materials.subCategory.category',
                    'equipments.equipment',
                    'measurements',
                ])->where('project_id', $user->project_assign_id);

                // Apply filter if selected
                if (! empty($mainCategoryId)) {
                    $dailyreportQuery->whereHas('nameOfWork.mainCategory', function ($q) use ($mainCategoryId) {
                        $q->where('id', $mainCategoryId);
                    });
                }

                $dailyreport = $dailyreportQuery->get()->groupBy('name_of_work_id');

                $mainCategories = \App\Models\DailyReportMainCategory::where('project_id', $user->project_assign_id)->get();

                // Work counters
                $totalWorks = 0;
                $pendingWorks = 0;
                $completedWorks = 0;

                foreach ($dailyreport as $nameOfWorkId => $reports) {

                    $usedMeasurement = 0;

                    foreach ($reports as $report) {
                        foreach ($report->measurements as $m) {
                            $usedMeasurement += (float) ($m->mesurements_value ?? 0);
                        }
                    }

                    $totalMeasurement = $reports[0]->nameOfWork->total_mesurement ?? 0;

                    if ($totalMeasurement > 0) {
                        $totalWorks++;

                        if ($usedMeasurement >= $totalMeasurement) {
                            $completedWorks++;
                        } else {
                            $pendingWorks++;
                        }
                    }
                }

                /** Existing Data **/
                $home_data['total_works'] = $totalWorks;
                $home_data['total_pending'] = $pendingWorks;
                $home_data['total_completed'] = $completedWorks;

                /** ---------------- CURRENT PROGRESS ---------------- **/
                $currentCompleted = $completedWorks;
                $currentTotal = $totalWorks;

                $currentProgress = $currentTotal > 0
                    ? ($currentCompleted / $currentTotal) * 100
                    : 0;

                /** ---------------- LAST WEEK PROGRESS ---------------- **/
                $lastWeekDate = \Carbon\Carbon::now()->subWeek();

                $lastWeekReports = \App\Models\DailyReport::with([
                    'measurements',
                    'nameOfWork',
                ])
                    ->where('project_id', $projectId)
                    ->whereDate('created_at', '<=', $lastWeekDate)   // ⭐ Key logic
                    ->get()
                    ->groupBy('name_of_work_id');

                $lastWeekTotalWorks = 0;
                $lastWeekCompletedWorks = 0;

                foreach ($lastWeekReports as $reports) {

                    $usedMeasurement = 0;

                    foreach ($reports as $report) {
                        foreach ($report->measurements as $m) {
                            $usedMeasurement += (float) ($m->mesurements_value ?? 0);
                        }
                    }

                    $totalMeasurement = $reports[0]->nameOfWork->total_mesurement ?? 0;

                    if ($totalMeasurement > 0) {
                        $lastWeekTotalWorks++;

                        if ($usedMeasurement >= $totalMeasurement) {
                            $lastWeekCompletedWorks++;
                        }
                    }
                }

                $lastWeekProgress = $lastWeekTotalWorks > 0
                    ? ($lastWeekCompletedWorks / $lastWeekTotalWorks) * 100
                    : 0;

                $weeklyChange = $currentProgress - $lastWeekProgress;
                $home_data['work_progress'] = round($currentProgress, 1);
                $home_data['weekly_change'] = round($weeklyChange, 1);

                /** ---------------- PROJECT STATUS (DB आधारित) ---------------- **/
                $statusLabel = 'In Progress';
                $statusColor = 'primary';
                $statusIcon = 'ti ti-loader';

                switch ($project->status) {

                    case 'complete':
                        $statusLabel = 'Complete';
                        $statusColor = 'success';
                        $statusIcon = 'ti ti-circle-check';
                        break;

                    case 'canceled':
                        $statusLabel = 'Canceled';
                        $statusColor = 'danger';
                        $statusIcon = 'ti ti-circle-x';
                        break;

                    case 'on_hold':
                        $statusLabel = 'On Hold';
                        $statusColor = 'warning';
                        $statusIcon = 'ti ti-player-pause';
                        break;

                    case 'in_progress':
                    default:
                        $statusLabel = 'In Progress';
                        $statusColor = 'primary';
                        $statusIcon = 'ti ti-loader';
                        break;
                }

                $home_data['project_status'] = [
                    'label' => $statusLabel,
                    'color' => $statusColor,
                    'icon' => $statusIcon,
                ];

                /** ---------------- MATERIAL ANALYSIS ---------------- **/
                $materialCategoryId = $request->get('material_category_id');

                $materials = \App\Models\MaterialSubCategory::with(['category', 'attribute'])
                    ->whereHas('category', function ($q) use ($projectId, $materialCategoryId) {

                        $q->where('project_id', $projectId);

                        if (! empty($materialCategoryId)) {
                            $q->where('id', $materialCategoryId);
                        }
                    })
                    ->get();

                $materialAnalysis = [];

                foreach ($materials as $material) {

                    $incoming = (float) ($material->total_stock ?? 0);
                    $used = (float) ($material->used_stock ?? 0);
                    $price = (float) ($material->price ?? 0);
                    $remaining = max(0, $incoming - $used);

                    $remainingPercent = 0;

                    if ($incoming > 0) {
                        $remainingPercent = max(0, (($incoming - $used) / $incoming) * 100);
                    }

                    /** ✅ YOUR RULES **/
                    if ($remainingPercent <= 10) {
                        $status = 'Low Stock';
                        $color = 'danger';
                    } elseif ($remainingPercent <= 50) {
                        $status = 'Optimal';
                        $color = 'success';
                    } else {
                        $status = 'Plentiful';
                        $color = 'primary';
                    }

                    $attribute = $material->attribute->name ?? '';

                    /** ✅ Amount Calculation **/
                    $usedAmount = $used * $price;
                    $totalAmount = $incoming * $price;
                    $materialAnalysis[] = [
                        'name' => $material->name,
                        'db_status' => $material->status ?? '',
                        'incoming_value' => $incoming,
                        'attribute' => $attribute,
                        'used_value' => $used,
                        'incoming' => number_format($incoming).' '.$attribute,
                        'used' => number_format($used).' '.$attribute,
                        'remaining' => number_format($remaining),
                        'used_amount' => $usedAmount,
                        'total_amount' => $totalAmount,
                        'status' => $status,
                        'color' => $color,
                    ];
                }

                $home_data['materials'] = $materialAnalysis;

                /** ---------------- INVENTORY HEALTH ---------------- **/
                $inventoryHealth = [];

                foreach ($materials as $material) {

                    $incoming = (float) ($material->total_stock ?? 0);
                    $used = (float) ($material->used_stock ?? 0);

                    $remainingPercent = 0;

                    if ($incoming > 0) {
                        $remainingPercent = max(0, (($incoming - $used) / $incoming) * 100);
                    }

                    /** Color Logic **/
                    if ($remainingPercent <= 10) {
                        $color = 'danger';
                    } elseif ($remainingPercent <= 50) {
                        $color = 'success';
                    } else {
                        $color = 'primary';
                    }

                    $inventoryHealth[] = [
                        'name' => $material->name,
                        'percent' => round($remainingPercent),
                        'color' => $color,
                    ];
                }

                $home_data['inventory_health'] = $inventoryHealth;
                $materialCategories = \App\Models\MaterialCategory::where('project_id', $projectId)->get();

                return view('dashboard.graph-dashboard', compact(
                    'home_data',
                    'dailyreport',
                    'mainCategories',
                    'mainCategoryId',
                    'materialCategories'
                ));
            }
        } else {
            return $this->account_dashboard_index();
        }
    }

    public function hrm_dashboard_index()
    {

        if (Auth::check()) {

            if (\Auth::user()->can('show hrm dashboard')) {

                $user = Auth::user();

                if ($user->type != 'client' && $user->type != 'company') {
                    $emp = Employee::where('user_id', '=', $user->id)->first();

                    $announcements = Announcement::orderBy('announcements.id', 'desc')->take(5)->leftjoin('announcement_employees', 'announcements.id', '=', 'announcement_employees.announcement_id')->where('announcement_employees.employee_id', '=', $emp->id)->orWhere(function ($q) use ($emp) {
                        $q->where('announcements.department_id', '["0"]')
                        ->where('announcements.employee_id', '["0"]')
                        ->where('announcement_employees.employee_id', $emp->id);
                    })->get();

                    $employees = Employee::get();
                    $meetings = Meeting::orderBy('meetings.id', 'desc')->take(5)->leftjoin('meeting_employees', 'meetings.id', '=', 'meeting_employees.meeting_id')->where('meeting_employees.employee_id', '=', $emp->id)->orWhere(function ($q) {
                        $q->where('meetings.department_id', '["0"]')->where('meetings.employee_id', '["0"]');
                    })->get();
                    $events = Event::leftjoin('event_employees', 'events.id', '=', 'event_employees.event_id')->where('event_employees.employee_id', '=', $emp->id)->orWhere(function ($q) {
                        $q->where('events.department_id', '["0"]')->where('events.employee_id', '["0"]');
                    })->get();

                    $arrEvents = [];
                    foreach ($events as $event) {

                        $arr['id'] = $event['id'];
                        $arr['title'] = $event['title'];
                        $arr['start'] = $event['start_date'];
                        $arr['end'] = $event['end_date'];
                        $arr['backgroundColor'] = $event['color'];
                        $arr['borderColor'] = "#fff";
                        $arr['textColor'] = "white";
                        $arrEvents[] = $arr;
                    }

                    $date = date("Y-m-d");
                    $time = date("H:i:s");
                    $employeeAttendance = AttendanceEmployee::orderBy('id', 'desc')->where('employee_id', '=', !empty(\Auth::user()->employee)?\Auth::user()->employee->id : 0)->where('date', '=', $date)->first();

                    $officeTime['startTime'] = Utility::getValByName('company_start_time');
                    $officeTime['endTime'] = Utility::getValByName('company_end_time');

                    return view('dashboard.dashboard', compact('arrEvents', 'announcements', 'employees', 'meetings', 'employeeAttendance', 'officeTime'));
                } else if ($user->type == 'super admin') {
                    $user = \Auth::user();
                    $user['total_user'] = $user->countCompany();
                    $user['total_paid_user'] = $user->countPaidCompany();
                    $user['total_orders'] = Order::total_orders();
                    $user['total_orders_price'] = Order::total_orders_price();
                    $user['total_plan'] = Plan::total_plan();
                    if(!empty(Plan::most_purchese_plan()))
                    {
                        $plan = Plan::find(Plan::most_purchese_plan()['plan']);
                        $user['most_purchese_plan'] = $plan->name;
                    }
                    else
                    {
                        $user['most_purchese_plan'] = '-';
                    }


                    $chartData = $this->getOrderChart(['duration' => 'week']);

                    return view('dashboard.super_admin', compact('user', 'chartData'));
                } else {
                    $events = Event::where('created_by', '=', \Auth::user()->creatorId())->get();
                    $arrEvents = [];

                    foreach ($events as $event) {
                        $arr['id'] = $event['id'];
                        $arr['title'] = $event['title'];
                        $arr['start'] = $event['start_date'];
                        $arr['end'] = $event['end_date'];

                        $arr['backgroundColor'] = $event['color'];
                        $arr['borderColor'] = "#fff";
                        $arr['textColor'] = "white";
                        $arr['url'] = route('event.edit', $event['id']);

                        $arrEvents[] = $arr;
                    }

                    $announcements = Announcement::orderBy('announcements.id', 'desc')->take(5)->where('created_by', '=', \Auth::user()->creatorId())->get();

                    // $emp           = User::where('type', '!=', 'client')->where('type', '!=', 'company')->where('created_by', '=', \Auth::user()->creatorId())->get();
                    // $countEmployee = count($emp);

                    $user = User::where('type', '!=', 'client')->where('type', '!=', 'company')->where('created_by', '=', \Auth::user()->creatorId())->get();
                    $countUser = count($user);

                    $countTrainer = Trainer::where('created_by', '=', \Auth::user()->creatorId())->count();
                    $onGoingTraining = Training::where('status', '=', 1)->where('created_by', '=', \Auth::user()->creatorId())->count();
                    $doneTraining = Training::where('status', '=', 2)->where('created_by', '=', \Auth::user()->creatorId())->count();

                    $currentDate = date('Y-m-d');

                    $employees = User::where('type', '=', 'client')->where('created_by', '=', \Auth::user()->creatorId())->get();
                    $countClient = count($employees);
                    $notClockIn = AttendanceEmployee::where('date', '=', $currentDate)->get()->pluck('employee_id');

                    $notClockIns = Employee::where('created_by', '=', \Auth::user()->creatorId())->whereNotIn('id', $notClockIn)->get();
                    $activeJob = Job::where('status', 'active')->where('created_by', '=', \Auth::user()->creatorId())->count();
                    $inActiveJOb = Job::where('status', 'in_active')->where('created_by', '=', \Auth::user()->creatorId())->count();

                    $meetings = Meeting::where('created_by', '=', \Auth::user()->creatorId())->limit(5)->get();

                    return view('dashboard.dashboard', compact('arrEvents', 'onGoingTraining', 'activeJob', 'inActiveJOb', 'doneTraining', 'announcements', 'employees', 'meetings', 'countTrainer', 'countClient', 'countUser', 'notClockIns'));
                }
            } else {

                return $this->project_dashboard_index();
            }
        } else {
            if (!file_exists(storage_path() . "/installed")) {
                header('location:install');
                die;
            } else {
                $settings = Utility::settings();
                if ($settings['display_landing_page'] == 'on') {
                    $plans = Plan::get();

                    return view('layouts.landing', compact('plans'));
                } else {
                    return redirect('login');
                }

            }
        }
    }

    public function crm_dashboard_index()
    {
        $user = Auth::user();
        if (\Auth::user()->can('show crm dashboard')) {
            if ($user->type == 'admin') {
                return view('admin.dashboard');
            } else {
                $crm_data = [];

                $leads = Lead::where('created_by', \Auth::user()->creatorId())->get();
                $deals = Deal::where('created_by', \Auth::user()->creatorId())->get();

                //count data
                $crm_data['total_leads'] = $total_leads = count($leads);
                $crm_data['total_deals'] = $total_deals = count($deals);
                $crm_data['total_contracts'] = Contract::where('created_by', \Auth::user()->creatorId())->count();

                //lead status
//                $user_leads   = $leads->pluck('lead_id')->toArray();
                $total_leads = count($leads);
                $lead_status = [];
                $status = LeadStage::select('lead_stages.*', 'pipelines.name as pipeline')
                    ->join('pipelines', 'pipelines.id', '=', 'lead_stages.pipeline_id')
                    ->where('pipelines.created_by', '=', \Auth::user()->creatorId())
                    ->where('lead_stages.created_by', '=', \Auth::user()->creatorId())
                    ->orderBy('lead_stages.pipeline_id')->get();

                foreach ($status as $k => $v) {
                    $lead_status[$k]['lead_stage'] = $v->name;
                    $lead_status[$k]['lead_total'] = count($v->lead());
                    $lead_status[$k]['lead_percentage'] = Utility::getCrmPercentage($lead_status[$k]['lead_total'], $total_leads);

                }

                $crm_data['lead_status'] = $lead_status;

                //deal status
//                $user_deal   = $deals->pluck('deal_id')->toArray();
                $total_deals = count($deals);
                $deal_status = [];
                $dealstatuss = Stage::select('stages.*', 'pipelines.name as pipeline')
                    ->join('pipelines', 'pipelines.id', '=', 'stages.pipeline_id')
                    ->where('pipelines.created_by', '=', \Auth::user()->creatorId())
                    ->where('stages.created_by', '=', \Auth::user()->creatorId())
                    ->orderBy('stages.pipeline_id')->get();
                foreach ($dealstatuss as $k => $v) {
                    $deal_status[$k]['deal_stage'] = $v->name;
                    $deal_status[$k]['deal_total'] = count($v->deals());
                    $deal_status[$k]['deal_percentage'] = Utility::getCrmPercentage($deal_status[$k]['deal_total'], $total_deals);
                }
                $crm_data['deal_status'] = $deal_status;

                $crm_data['latestContract'] = Contract::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->with(['clients', 'projects', 'types'])->get();

                return view('dashboard.crm-dashboard', compact('crm_data'));
            }
        } else {
            return $this->account_dashboard_index();
        }
    }

    public function pos_dashboard_index()
    {
        $user = Auth::user();
        if (\Auth::user()->can('show pos dashboard')) {
            if ($user->type == 'admin') {
                return view('admin.dashboard');
            } else {
                $pos_data = [];
                $pos_data['monthlyPosAmount'] = Pos::totalPosAmount(true);
                $pos_data['totalPosAmount'] = Pos::totalPosAmount();
                $pos_data['monthlyPurchaseAmount'] = Purchase::totalPurchaseAmount(true);
                $pos_data['totalPurchaseAmount'] = Purchase::totalPurchaseAmount();

                $purchasesArray = Purchase::getPurchaseReportChart();
                $posesArray = Pos::getPosReportChart();

                return view('dashboard.pos-dashboard', compact('pos_data', 'purchasesArray', 'posesArray'));
            }
        } else {
            return $this->account_dashboard_index();
        }
    }

    // Load Dashboard user's using ajax
    public function filterView(Request $request)
    {
        $usr = Auth::user();
        $users = User::where('id', '!=', $usr->id);

        if ($request->ajax()) {
            if (!empty($request->keyword)) {
                $users->where('name', 'LIKE', $request->keyword . '%')->orWhereRaw('FIND_IN_SET("' . $request->keyword . '",skills)');
            }

            $users = $users->get();
            $returnHTML = view('dashboard.view', compact('users'))->render();

            return response()->json([
                'success' => true,
                'html' => $returnHTML,
            ]);
        }
    }

    public function clientView()
    {

        if (Auth::check()) {
            if (Auth::user()->type == 'super admin') {
                $user = \Auth::user();
                $user['total_user'] = $user->countCompany();
                $user['total_paid_user'] = $user->countPaidCompany();
                $user['total_orders'] = Order::total_orders();
                $user['total_orders_price'] = Order::total_orders_price();
                $user['total_plan'] = Plan::total_plan();
                if(!empty(Plan::most_purchese_plan()))
                {
                    $plan = Plan::find(Plan::most_purchese_plan()['plan']);
                    $user['most_purchese_plan'] = $plan->name;
                }
                else
                {
                    $user['most_purchese_plan'] = '-';
                }

                $chartData = $this->getOrderChart(['duration' => 'week']);

                return view('dashboard.super_admin', compact('user', 'chartData'));

            } elseif (Auth::user()->type == 'client') {
                $transdate = date('Y-m-d', time());
                $currentYear = date('Y');

                $calenderTasks = [];
                $chartData = [];
                $arrCount = [];
                $arrErr = [];
                $m = date("m");
                $de = date("d");
                $y = date("Y");
                $format = 'Y-m-d';
                $user = \Auth::user();
                if (\Auth::user()->can('View Task')) {
                    $company_setting = Utility::settings();
                }
                $arrTemp = [];
                for ($i = 0; $i <= 7 - 1; $i++) {
                    $date = date($format, mktime(0, 0, 0, $m, ($de - $i), $y));
                    $arrTemp['date'][] = __(date('D', strtotime($date)));
                    $arrTemp['invoice'][] = 10;
                    $arrTemp['payment'][] = 20;
                }

                $chartData = $arrTemp;

                foreach ($user->clientDeals as $deal) {
                    foreach ($deal->tasks as $task) {
                        $calenderTasks[] = [
                            'title' => $task->name,
                            'start' => $task->date,
                            'url' => route('deals.tasks.show', [
                                $deal->id,
                                $task->id,
                            ]),
                            'className' => ($task->status) ? 'bg-primary border-primary' : 'bg-warning border-warning',
                        ];
                    }

                    $calenderTasks[] = [
                        'title' => $deal->name,
                        'start' => $deal->created_at->format('Y-m-d'),
                        'url' => route('deals.show', [$deal->id]),
                        'className' => 'deal bg-primary border-primary',
                    ];
                }
                $client_deal = $user->clientDeals->pluck('id');

                $arrCount['deal'] = !empty($user->clientDeals) ? $user->clientDeals->count() : 0;

                if (!empty($client_deal->first())) {

                    $arrCount['task'] = DealTask::whereIn('deal_id', [$client_deal->first()])->count();

                } else {
                    $arrCount['task'] = 0;
                }

                $project['projects'] = Project::where('client_id', '=', Auth::user()->id)->where('created_by', \Auth::user()->creatorId())->where('end_date', '>', date('Y-m-d'))->limit(5)->orderBy('end_date')->get();
                $project['projects_count'] = count($project['projects']);
                $user_projects = Project::where('client_id', \Auth::user()->id)->pluck('id', 'id')->toArray();
                $tasks = ProjectTask::whereIn('project_id', $user_projects)->where('created_by', \Auth::user()->creatorId())->get();
                $project['projects_tasks_count'] = count($tasks);
                $project['project_budget'] = Project::where('client_id', Auth::user()->id)->sum('budget');

                $project_last_stages = Auth::user()->last_projectstage();
                $project_last_stage = (!empty($project_last_stages) ? $project_last_stages->id : 0);
                $project['total_project'] = Auth::user()->user_project();
                $total_project_task = Auth::user()->created_total_project_task();
                $allProject = Project::where('client_id', \Auth::user()->id)->where('created_by', \Auth::user()->creatorId())->get();
                $allProjectCount = count($allProject);

                $bugs = Bug::whereIn('project_id', $user_projects)->where('created_by', \Auth::user()->creatorId())->get();
                $project['projects_bugs_count'] = count($bugs);
                $bug_last_stage = BugStatus::orderBy('order', 'DESC')->first();
                $completed_bugs = Bug::whereIn('project_id', $user_projects)->where('status', $bug_last_stage->id)->where('created_by', \Auth::user()->creatorId())->get();
                $allBugCount = count($bugs);
                $completedBugCount = count($completed_bugs);
                $project['project_bug_percentage'] = ($allBugCount != 0) ? intval(($completedBugCount / $allBugCount) * 100) : 0;
                $complete_task = Auth::user()->project_complete_task($project_last_stage);
                $completed_project = Project::where('client_id', \Auth::user()->id)->where('status', 'complete')->where('created_by', \Auth::user()->creatorId())->get();
                $completed_project_count = count($completed_project);
                $project['project_percentage'] = ($allProjectCount != 0) ? intval(($completed_project_count / $allProjectCount) * 100) : 0;
                $project['project_task_percentage'] = ($total_project_task != 0) ? intval(($complete_task / $total_project_task) * 100) : 0;
                $invoice = [];
                $top_due_invoice = [];
                $invoice['total_invoice'] = 5;
                $complete_invoice = 0;
                $total_due_amount = 0;
                $top_due_invoice = array();
                $pay_amount = 0;

                if (Auth::user()->type == 'client') {
                    if (!empty($project['project_budget'])) {
                        $project['client_project_budget_due_per'] = intval(($pay_amount / $project['project_budget']) * 100);
                    } else {
                        $project['client_project_budget_due_per'] = 0;
                    }

                }

                $top_tasks = Auth::user()->created_top_due_task();
                $users['staff'] = User::where('created_by', '=', Auth::user()->creatorId())->count();
                $users['user'] = User::where('created_by', '=', Auth::user()->creatorId())->where('type', '!=', 'client')->count();
                $users['client'] = User::where('created_by', '=', Auth::user()->creatorId())->where('type', '=', 'client')->count();
                $project_status = array_values(Project::$project_status);
                $projectData = \App\Models\Project::getProjectStatus();

                $taskData = \App\Models\TaskStage::getChartData();

                return view('dashboard.clientView', compact('calenderTasks', 'arrErr', 'arrCount', 'chartData', 'project', 'invoice', 'top_tasks', 'top_due_invoice', 'users', 'project_status', 'projectData', 'taskData', 'transdate', 'currentYear'));
            }
        }
    }

    public function getOrderChart($arrParam)
    {
        $arrDuration = [];
        if ($arrParam['duration']) {
            if ($arrParam['duration'] == 'week') {
                $previous_week = strtotime("-2 week +1 day");
                for ($i = 0; $i < 14; $i++) {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-M', $previous_week);
                    $previous_week = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }

        $arrTask = [];
        $arrTask['label'] = [];
        $arrTask['data'] = [];
        foreach ($arrDuration as $date => $label) {

            $data = Order::select(\DB::raw('count(*) as total'))->whereDate('created_at', '=', $date)->first();
            $arrTask['label'][] = $label;
            $arrTask['data'][] = $data->total;
        }

        return $arrTask;
    }

    public function stopTracker(Request $request)
    {
        if (Auth::user()->isClient()) {
            return Utility::error_res(__('Permission denied.'));
        }
        $validatorArray = [
            'name' => 'required|max:120',
            'project_id' => 'required|integer',
        ];
        $validator = Validator::make(
            $request->all(), $validatorArray
        );
        if ($validator->fails()) {
            return Utility::error_res($validator->errors()->first());
        }
        $tracker = TimeTracker::where('created_by', '=', Auth::user()->id)->where('is_active', '=', 1)->first();
        if ($tracker) {
            $tracker->end_time = $request->has('end_time') ? $request->input('end_time') : date("Y-m-d H:i:s");
            $tracker->is_active = 0;
            $tracker->total_time = Utility::diffance_to_time($tracker->start_time, $tracker->end_time);
            $tracker->save();

            return Utility::success_res(__('Add Time successfully.'));
        }

        return Utility::error_res('Tracker not found.');
    }

}
