<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EngineerAttendances;
use App\Exports\EngineerAttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Holiday;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
//       public function index(Request $request)
// {
//      $user = \Auth::user();
//         $webAccess = is_array($user->web_access)
//         ? $user->web_access
//         : json_decode($user->web_access ?? '[]', true);

//     // Permission check for both company & other roles
//     $hasPermission = (
//         ($user->type === 'company' && in_array('manage engineer attendance', $webAccess)) ||
//         ($user->type !== 'company' && $user->can('manage engineer attendance'))
//     );

//     if ($hasPermission) {



//         // ✅ Fetch only App Users instead of Employees
//         $users = User::select('id', 'name')
//             ->where('type', 'app user') // Only app users
//             ->where('created_by', \Auth::user()->creatorId());

//         // Filter by specific user IDs if provided
//         if (!empty($request->user_id) && $request->user_id[0] != 0) {
//             $users->whereIn('id', $request->user_id);
//         }

//         $users = $users->get()->pluck('name', 'id');

//         // Month/year handling
//         if (!empty($request->month)) {
//             $currentdate = strtotime($request->month);
//             $month = date('m', $currentdate);
//             $year = date('Y', $currentdate);
//             $curMonth = date('M-Y', strtotime($request->month));
//         } else {
//             $month = date('m');
//             $year = date('Y');
//             $curMonth = date('M-Y', strtotime($year . '-' . $month));
//         }

//         $num_of_days = date('t', mktime(0, 0, 0, $month, 1, $year));
//         $dates = [];
//         for ($i = 1; $i <= $num_of_days; $i++) {
//             $dates[] = str_pad($i, 2, '0', STR_PAD_LEFT);
//         }

//         // Attendance logic
//         $usersAttendance = [];
//         $totalPresent = $totalLeave = $totalEarlyLeave = 0;
//         $ovetimeHours = $overtimeMins = $earlyleaveHours = $earlyleaveMins = $lateHours = $lateMins = 0;

//         foreach ($users as $id => $user) {
//             $attendances['name'] = $user;
//             $attendanceStatus = [];

//             foreach ($dates as $date) {
//                 $dateFormat = $year . '-' . $month . '-' . $date;

//                 // if ($dateFormat <= date('Y-m-d')) {
//                 //     // ✅ Change this model to whatever table stores app user attendance
//                 //     $userAttendance = AttendanceUser::where('user_id', $id)
//                 //         ->where('date', $dateFormat)
//                 //         ->first();

//                 //     if (!empty($userAttendance) && $userAttendance->status == 'Present') {
//                 //         $attendanceStatus[$date] = 'P';
//                 //         $totalPresent++;

//                 //         if ($userAttendance->overtime > 0) {
//                 //             $ovetimeHours += date('h', strtotime($userAttendance->overtime));
//                 //             $overtimeMins += date('i', strtotime($userAttendance->overtime));
//                 //         }

//                 //         if ($userAttendance->early_leaving > 0) {
//                 //             $earlyleaveHours += date('h', strtotime($userAttendance->early_leaving));
//                 //             $earlyleaveMins += date('i', strtotime($userAttendance->early_leaving));
//                 //         }

//                 //         if ($userAttendance->late > 0) {
//                 //             $lateHours += date('h', strtotime($userAttendance->late));
//                 //             $lateMins += date('i', strtotime($userAttendance->late));
//                 //         }
//                 //     } elseif (!empty($userAttendance) && $userAttendance->status == 'Leave') {
//                 //         $attendanceStatus[$date] = 'A';
//                 //         $totalLeave++;
//                 //     } else {
//                 //         $attendanceStatus[$date] = '';
//                 //     }
//                 // } else {
//                 //     $attendanceStatus[$date] = '';
//                 // }
//             }

//             $attendances['status'] = $attendanceStatus;
//             $usersAttendance[] = $attendances;
//         }

//         // Totals
//         $totalOverTime = $ovetimeHours + ($overtimeMins / 60);
//         $totalEarlyleave = $earlyleaveHours + ($earlyleaveMins / 60);
//         $totalLate = $lateHours + ($lateMins / 60);

//         $data['totalOvertime'] = $totalOverTime;
//         $data['totalEarlyLeave'] = $totalEarlyleave;
//         $data['totalLate'] = $totalLate;
//         $data['totalPresent'] = $totalPresent;
//         $data['totalLeave'] = $totalLeave;
//         $data['curMonth'] = $curMonth;

//         return view('engineer_attendance.index', compact('usersAttendance',  'dates', 'data'));
//     } else {
//         return redirect()->back()->with('error', __('Permission denied.'));
//     }
// }

public function index(Request $request)
{
    $user = \Auth::user();
    $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage engineer attendance', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage engineer attendance'))
    );

    if (!$hasPermission) {
        return redirect()->back()->with('error', __('Permission denied.'));
    }

    // ✅ Fetch only “app user” type users created by the logged-in user
    $users = User::select('id', 'name')
        ->where('type', 'app user')
        ->where('created_by', $user->creatorId());

    // Optional filter
    if (!empty($request->user_id) && $request->user_id[0] != 0) {
        $users->whereIn('id', $request->user_id);
    }

    $users = $users->get();

    // Month/year setup
    if (!empty($request->month)) {
        $currentdate = strtotime($request->month);
        $month = date('m', $currentdate);
        $year = date('Y', $currentdate);
        $curMonth = date('M-Y', strtotime($request->month));
    } else {
        $month = date('m');
        $year = date('Y');
        $curMonth = date('M-Y', strtotime($year . '-' . $month));
    }

    $num_of_days = date('t', mktime(0, 0, 0, $month, 1, $year));
    $dates = [];
    for ($i = 1; $i <= $num_of_days; $i++) {
        $dates[] = str_pad($i, 2, '0', STR_PAD_LEFT);
    }

    // Prepare data for view (no attendance logic yet)
    return view('engineer_attendance.index', compact('users', 'dates', 'curMonth'));
}

public function show($id, Request $request)
{
    $user = \Auth::user();
    $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    $hasPermission = (
        ($user->type === 'company' && in_array('manage engineer attendance', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage engineer attendance'))
    );

    if (!$hasPermission) {
        return redirect()->back()->with('error', __('Permission denied.'));
    }

    // ✅ Find the selected engineer
    $engineer = User::where('id', $id)
        ->where('type', 'app user')
        ->firstOrFail();

    // ✅ Month handling
    if (!empty($request->month)) {
        $currentdate = strtotime($request->month);
        $month = date('m', $currentdate);
        $year = date('Y', $currentdate);
        $curMonth = date('M-Y', strtotime($request->month));
    } else {
        $month = date('m');
        $year = date('Y');
        $curMonth = date('M-Y', strtotime($year . '-' . $month));
    }

    // ✅ Fetch attendance records for this engineer
    $attendanceRecords = \App\Models\EngineerAttendances::with('project')
        ->where('engineer_id', $id)->where('project_id', $user->project_assign_id)
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->orderBy('date', 'desc')
        ->get();
        
         // Helper functions
function timeStringToMinutes($timeStr) {
    if (!$timeStr || $timeStr === '-') return 0;
    preg_match('/(\d+)h\s*(\d+)m/', $timeStr, $matches);
    return isset($matches[1], $matches[2]) ? ($matches[1]*60 + $matches[2]) : 0;
}

function minutesToTimeString($minutes) {
    $h = floor($minutes / 60);
    $m = $minutes % 60;
    return sprintf('%02dh %02dm', $h, $m);
}

// Calculate totals **only for this engineer and project_assign_id**
$totalDuration = $attendanceRecords->sum(function($record) {
    return timeStringToMinutes($record->duration);
});
$totalLate = $attendanceRecords->sum(function($record) {
    return timeStringToMinutes($record->late);
});
$totalOvertime = $attendanceRecords->sum(function($record) {
    return timeStringToMinutes($record->overtime);
});

// Convert back to "HHh MMm"
$totalDurationFormatted = minutesToTimeString($totalDuration);
$totalLateFormatted = minutesToTimeString($totalLate);
$totalOvertimeFormatted = minutesToTimeString($totalOvertime);
$totalTimeMinutes = $totalDuration  + $totalOvertime - $totalLate;
$totalTimeFormatted = minutesToTimeString($totalTimeMinutes);

    // ✅ Prepare formatted list
    $attendanceList = $attendanceRecords->map(function ($record) {
        return [
            'id'             => $record->id ?? '-',
            'date'             => $record->date ?? '-',
            'project'          => $record->project->project_name ?? '-',
            'check_in'         => $record->check_in ? date('h:i A', strtotime($record->check_in)) : '-',
            'check_out'        => $record->check_out ? date('h:i A', strtotime($record->check_out)) : '-',
            'duration'         => $record->duration ?? '-',
            'attendance_type'  => $record->attendance_type ?? '-',
            'late'             => $record->late ?? '-',
            'overtime'         => $record->overtime ?? '-',
            'check_in_lat'     => $record->check_in_latitude ?? '-',
            'check_in_long'    => $record->check_in_longitude ?? '-',
            'check_out_lat'    => $record->check_out_latitude ?? '-',
            'check_out_long'   => $record->check_out_longitude ?? '-',
        ];
    });
    
        $presentCount = $attendanceList->where('attendance_type', 'P')->count();
$absentCount  = $attendanceList->where('attendance_type', 'A')->count();
$holidayCount = $attendanceList->where('attendance_type', 'H')->count();

    return view('engineer_attendance.show', compact('engineer', 'attendanceList', 'curMonth', 'totalDurationFormatted',
    'totalLateFormatted',
    'totalOvertimeFormatted','presentCount','absentCount','holidayCount','totalTimeFormatted'));
}

public function exportEngineerAttendance(Request $request, $engineer_id )
{
    $engineer = User::where('id', $engineer_id)
                    ->where('type', 'app user')
                    ->firstOrFail();

    $month = $request->month ? date('m', strtotime($request->month)) : date('m');
    $year  = $request->month ? date('Y', strtotime($request->month)) : date('Y');

    $monthName = date('M-Y', strtotime($year . '-' . $month));

    // Use engineer's name for file (replace spaces with _)
    $engineerName = str_replace(' ', '_', $engineer->name);

    $fileName = $engineerName . '_' . $monthName . '_Attendance_Report.xlsx';

    return Excel::download(new EngineerAttendanceExport($month, $year,$engineer_id), $fileName);
}

public function exportEngineerAttendancePDF(Request $request, $engineer_id)
{
    $engineer = User::where('id', $engineer_id)
                    ->where('type', 'app user')
                    ->firstOrFail();

    $user = \Auth::user();

    // Month/year filter
    $month = $request->month ? date('m', strtotime($request->month)) : date('m');
    $year  = $request->month ? date('Y', strtotime($request->month)) : date('Y');
    $monthName = date('M-Y', strtotime($year . '-' . $month));

    // Engineer's assigned project
    $projectId = $user->project_assign_id;
    $project = Project::find($projectId);

    // Attendance records filtered by engineer and project
    $attendances = EngineerAttendances::with('project')
                    ->where('engineer_id', $engineer_id)
                    ->where('project_id', $projectId)
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->get();

    // Logo logic
    $pdfLogo = $project->pdf_logo ?? null;
    if ($pdfLogo) {
        $logoPath = storage_path('uploads/pdf_logo/' . $pdfLogo);
    } else {
        $company_logo = \App\Models\Utility::getValByName('company_logo');
        $fallbackLogo = !empty($company_logo) ? $company_logo : 'logo-dark.png';
        $logoPath = storage_path('uploads/logo/' . $fallbackLogo);
    }

    if (file_exists($logoPath)) {
        $imageData = base64_encode(file_get_contents($logoPath));
        $profileImg = 'data:image/png;base64,' . $imageData;
    } else {
        $profileImg = '';
    }

 // === Count attendance types
$presentCount = $attendances->where('attendance_type', 'P')->count();
$absentCount  = $attendances->where('attendance_type', 'A')->count();
$holidayCount = $attendances->where('attendance_type', 'H')->count();

$total = max($presentCount + $absentCount + $holidayCount, 1); // avoid /0

// === Calculate percentages
$presentPercent = round(($presentCount / $total) * 100, 1);
$absentPercent  = round(($absentCount / $total) * 100, 1);
$holidayPercent = round(($holidayCount / $total) * 100, 1);

// Create image
$width  = 300;
$height = 300;
$chart  = imagecreatetruecolor($width, $height);

// Enable anti-aliasing (for smoother arcs)
if (function_exists('imageantialias')) {
    imageantialias($chart, true);
}

// Colors
$white  = imagecolorallocate($chart, 255, 255, 255);
$green  = imagecolorallocate($chart, 34, 197, 94);   // Present (#22c55e)
$red    = imagecolorallocate($chart, 239, 68, 68);   // Absent (#ef4444)
$gray   = imagecolorallocate($chart, 107, 114, 128); // Holiday (#6b7280)
$black  = imagecolorallocate($chart, 0, 0, 0);

// Background
imagefilledrectangle($chart, 0, 0, $width, $height, $white);

// Geometry
$centerX = $width / 2;
$centerY = $height / 2;
$outerRadius = 120;
$innerRadius = 65;

// Angles (proportional)
$angles = [
    'P' => ($presentCount / $total) * 360,
    'A' => ($absentCount / $total) * 360,
    'H' => ($holidayCount / $total) * 360,
];

// Adjust rounding error so total = 360
$angleSum = array_sum($angles);
if ($angleSum !== 360) {
    $diff = 360 - $angleSum;
    $largestKey = array_keys($angles, max($angles))[0];
    $angles[$largestKey] += $diff;
}

// Draw slices
$start = 0;
foreach (['P' => $green, 'A' => $red, 'H' => $gray] as $key => $color) {
    $end = $start + $angles[$key];
    if ($angles[$key] > 0) {
        imagefilledarc($chart, $centerX, $centerY, $outerRadius * 2, $outerRadius * 2, $start, $end, $color, IMG_ARC_PIE);

        // Midpoint label
        $midAngle = deg2rad(($start + $end) / 2);
        $labelRadius = ($outerRadius + $innerRadius) / 2;
        $labelX = $centerX + cos($midAngle) * $labelRadius;
        $labelY = $centerY + sin($midAngle) * $labelRadius;

        $percentText = match ($key) {
            'P' => "{$presentPercent}%",
            'A' => "{$absentPercent}%",
            'H' => "{$holidayPercent}%",
        };

        imagestring($chart, 3, (int)($labelX - 10), (int)($labelY - 6), $percentText, $black);
    }
    $start = $end;
}

// Center white hole
imagefilledellipse($chart, $centerX, $centerY, $innerRadius * 2, $innerRadius * 2, $white);

// Text in center
$totalText = "Total Days";
$totalValue = (string)$total;
$textWidth = imagefontwidth(5) * strlen($totalValue);
imagestring($chart, 5, (int)($centerX - $textWidth / 2), (int)($centerY - 10), $totalValue, $black);

$textWidth2 = imagefontwidth(3) * strlen($totalText);
imagestring($chart, 3, (int)($centerX - $textWidth2 / 2), (int)($centerY + 10), $totalText, $black);

ob_start();
imagepng($chart);
$chartData = ob_get_clean();
imagedestroy($chart);

$chartBase64 = 'data:image/png;base64,' . base64_encode($chartData);


// === SECOND CHART: Total Time Summary ===

// Convert formatted time (e.g., "12h 30m") to minutes
function timeToMinutes($timeStr) {
    if (!$timeStr || $timeStr === '-') return 0;
    preg_match('/(\d+)h\s*(\d+)m/', $timeStr, $matches);
    return isset($matches[1], $matches[2]) ? ($matches[1] * 60 + $matches[2]) : 0;
}

function minutesToTime($mins) {
    $h = floor($mins / 60);
    $m = $mins % 60;
    return sprintf("%dh %dm", $h, $m);
}



$totalDurationMins = $attendances->sum(fn($r) => timeToMinutes($r->duration));
$totalLateMins     = $attendances->sum(fn($r) => timeToMinutes($r->late));
$totalOvertimeMins = $attendances->sum(fn($r) => timeToMinutes($r->overtime));

// Calculate total work time

$totalTimeMinutes = $totalDurationMins  + $totalOvertimeMins - $totalLateMins;
$totalWorkFormatted = minutesToTime($totalTimeMinutes);
$timeTotal = max($totalDurationMins + $totalLateMins + $totalOvertimeMins, 1);

$durationPercent = round(($totalDurationMins / $timeTotal) * 100, 1);
$latePercent     = round(($totalLateMins / $timeTotal) * 100, 1);
$overtimePercent = round(($totalOvertimeMins / $timeTotal) * 100, 1);

// Create chart
$width2  = 300;
$height2 = 300;
$chart2  = imagecreatetruecolor($width2, $height2);

// Colors
$white  = imagecolorallocate($chart2, 255, 255, 255);
$blue   = imagecolorallocate($chart2, 59, 130, 246);
$orange = imagecolorallocate($chart2, 249, 115, 22);
$red    = imagecolorallocate($chart2, 239, 68, 68);
$black  = imagecolorallocate($chart2, 0, 0, 0);

imagefilledrectangle($chart2, 0, 0, $width2, $height2, $white);

$centerX2 = $width2 / 2;
$centerY2 = $height2 / 2;
$outerRadius2 = 120;
$innerRadius2 = 65;

$angles2 = [
    'D' => ($totalDurationMins / $timeTotal) * 360,
    'L' => ($totalLateMins / $timeTotal) * 360,
    'O' => ($totalOvertimeMins / $timeTotal) * 360,
];

$sumAngles2 = array_sum($angles2);
if ($sumAngles2 > 0 && abs($sumAngles2 - 360) > 0.001) {
    $maxKey2 = array_keys($angles2, max($angles2))[0];
    $angles2[$maxKey2] += (360 - $sumAngles2);
}

$start2 = 0.0;
foreach (['D' => $blue, 'L' => $orange, 'O' => $red] as $key2 => $color2) {
    $end2 = $start2 + $angles2[$key2];
    imagefilledarc($chart2, $centerX2, $centerY2, $outerRadius2 * 2, $outerRadius2 * 2, $start2, $end2, $color2, IMG_ARC_PIE);

    $mid2 = deg2rad(($start2 + $end2) / 2);
    $labelRadius2 = ($outerRadius2 + $innerRadius2) / 2;
    $x2 = $centerX2 + cos($mid2) * $labelRadius2;
    $y2 = $centerY2 + sin($mid2) * $labelRadius2;

    $percentText2 = match ($key2) {
        'D' => $durationPercent . '%',
        'L' => $latePercent . '%',
        'O' => $overtimePercent . '%',
    };

    imagestring($chart2, 3, $x2 - 12, $y2 - 6, $percentText2, $black);
    $start2 = $end2;
}

// Cut center hole
imagefilledellipse($chart2, $centerX2, $centerY2, $innerRadius2 * 2, $innerRadius2 * 2, $white);

// Center label (two lines: title + value)
$title = "Total Time";
$value = $totalWorkFormatted;

imagestring($chart2, 4, $centerX2 - (strlen($title) * 3), $centerY2 - 15, $title, $black);
imagestring($chart2, 5, $centerX2 - (strlen($value) * 4), $centerY2 + 5, $value, $black);

ob_start();
imagepng($chart2);
$chartData2 = ob_get_clean();
imagedestroy($chart2);

$timeChartBase64 = 'data:image/png;base64,' . base64_encode($chartData2);



    // === Totals
function timeStringToMinutes($timeStr) {
    if (!$timeStr || $timeStr === '-') return 0;
    preg_match('/(\d+)h\s*(\d+)m/', $timeStr, $matches);
    return isset($matches[1], $matches[2]) ? ($matches[1]*60 + $matches[2]) : 0;
}

function minutesToTimeString($minutes) {
    $h = floor($minutes / 60);
    $m = $minutes % 60;
    return sprintf('%02dh %02dm', $h, $m);
}

    // Calculate totals **only for this engineer and project_assign_id**
$totalDuration = $attendances->sum(function($record) {
    return timeStringToMinutes($record->duration);
});
$totalLate = $attendances->sum(function($record) {
    return timeStringToMinutes($record->late);
});
$totalOvertime = $attendances->sum(function($record) {
    return timeStringToMinutes($record->overtime);
});

// Convert back to "HHh MMm"
$totalDurationFormatted = minutesToTimeString($totalDuration);
$totalLateFormatted = minutesToTimeString($totalLate);
$totalOvertimeFormatted = minutesToTimeString($totalOvertime);

    $fileName = str_replace(' ', '_', $engineer->name) . "_{$monthName}_Attendance_Report.pdf";

    return Pdf::loadView('engineer_attendance.pdf', compact('attendances', 'engineer', 'project', 'monthName', 'profileImg','totalDurationFormatted','totalLateFormatted','totalOvertimeFormatted','chartBase64', 'presentCount', 'absentCount', 'holidayCount','timeChartBase64'))
              ->download($fileName);
}

public function getEngineerAttendance()
{
    $today = Carbon::now('Asia/Kolkata')->toDateString();

    // Get app users with attendance_status NULL or 'H'
    $users = User::where('type', 'app user')
                ->where(function($query) {
                    $query->whereNull('attendance_status')
                          ->orWhere('attendance_status', 'H');
                })
                ->get();

    $createdAttendances = [];

    foreach ($users as $user) {
        // Check if user has "Attendance" in user_access
        $userAccess = is_array($user->user_access) ? $user->user_access : json_decode($user->user_access, true);

        if (!$userAccess || !in_array('Attendance', $userAccess)) {
            continue; // Skip user if no Attendance access
        }

        $attendanceType = is_null($user->attendance_status) ? 'A' : 'H';

        // Convert project_id to array if stored as JSON
        $projectIds = is_array($user->project_id) ? $user->project_id : json_decode($user->project_id, true);

        if ($projectIds && count($projectIds) > 0) {
            foreach ($projectIds as $projectId) {

                // Check if attendance already exists today for this user + project
                $exists = EngineerAttendances::where('engineer_id', $user->id)
                            ->where('project_id', $projectId)
                            ->whereDate('date', $today)
                            ->exists();

                if (!$exists) {
                    $attendance = EngineerAttendances::create([
                        'engineer_id' => $user->id,
                        'project_id' => $projectId,
                        'attendance_status' => $user->attendance_status,
                        'attendance_type' => $attendanceType,
                        'date' => $today, // make sure 'date' field exists in EngineerAttendances
                    ]);

                    $createdAttendances[] = $attendance;
                }
            }
        }
    }

    // ✅ After successful creation, reset attendance_status for all app users to NULL
    User::where('type', 'app user')->update(['attendance_status' => null]);

    return response()->json([
        'success' => true,
        'message' => 'Engineer attendances created successfully per project for users with Attendance access. All app users attendance_status reset to NULL.',
        'data' => $createdAttendances
    ]);
}


public function updateAttendanceForHoliday()
{
    $today = Carbon::now('Asia/Kolkata')->toDateString();

    // Get holidays where today's date falls between start and end date
    $holidays = Holiday::whereDate('date', '<=', $today)
                    ->whereDate('end_date', '>=', $today)
                    ->get();

    if ($holidays->isEmpty()) {
        return response()->json([
            'success' => true,
            'message' => 'No active holidays found for today.'
        ]);
    }

    $updatedUsers = [];

    // Loop through each holiday
    foreach ($holidays as $holiday) {
        $holidayProjectId = $holiday->project_id;

        // Get all app users
        $users = User::where('type', 'app user')->get();

        foreach ($users as $user) {
            // Decode user's project_id
            $userProjects = is_array($user->project_id) ? $user->project_id : json_decode($user->project_id, true);

            // Skip if no project_id data
            if (!$userProjects || !is_array($userProjects)) {
                continue;
            }

            // If user's project includes the holiday project
            if (in_array($holidayProjectId, $userProjects)) {
                // Update attendance_status to 'H'
                $user->update(['attendance_status' => 'H']);
                $updatedUsers[] = $user->id;
            }
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Attendance status updated to H for users in active holiday projects.',
        'updated_users' => $updatedUsers,
    ]);
}

public function edit(EngineerAttendances $engineer_attendance)
{
    $user = \Auth::user();
    $webAccess = is_array($user->web_access) ? $user->web_access : json_decode($user->web_access ?? '[]', true);

    $hasPermission = (
        ($user->type === 'company' && in_array('manage engineer attendance', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage engineer attendance'))
    );

    if (!$hasPermission) {
        return redirect()->back()->with('error', __('Permission denied.'));
    }

    return view('engineer_attendance.edit', compact('engineer_attendance'));
}


 public function update(Request $request, EngineerAttendances $engineer_attendance)
{
    $user = \Auth::user();
    $webAccess = is_array($user->web_access) ? $user->web_access : json_decode($user->web_access ?? '[]', true);

    $hasPermission = (
        ($user->type === 'company' && in_array('manage engineer attendance', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage engineer attendance'))
    );

    if (!$hasPermission) {
        return redirect()->back()->with('error', __('Permission denied.'));
    }

        $validator = \Validator::make($request->all(), [
            'check_out' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->getMessageBag()->first());
        }

        // Current time zone context
        $timezone = 'Asia/Kolkata';
        $checkIn = Carbon::parse($engineer_attendance->check_in, $timezone);
        $checkOut = Carbon::parse($request->check_out, $timezone);

        // Prevent invalid check-out before check-in
        if ($checkOut->lt($checkIn)) {
            return redirect()->back()->with('error', 'Check-out time cannot be before check-in time.');
        }

        // Get project attendance settings
        $project = \App\Models\Project::find($engineer_attendance->project_id);
        $attendanceStart = Carbon::parse($project->attendance_start_time, $timezone);
        $attendanceEnd = Carbon::parse($project->attendance_end_time, $timezone);

        // --- Duration ---
        $durationMinutes = $checkIn->diffInMinutes($checkOut);
        $durationFormatted = sprintf('%02dh %02dm', floor($durationMinutes / 60), $durationMinutes % 60);

        // --- Expected Work Duration ---
        $expectedMinutes = $attendanceStart->diffInMinutes($attendanceEnd);
        $expectedFormatted = sprintf('%02dh %02dm', floor($expectedMinutes / 60), $expectedMinutes % 60);

        // --- Compare actual vs expected ---
        $lateFormatted = null;
        $overtimeFormatted = null;

        if ($durationMinutes > $expectedMinutes) {
            $overtime = $durationMinutes - $expectedMinutes;
            $overtimeFormatted = sprintf('%02dh %02dm', floor($overtime / 60), $overtime % 60);
        } elseif ($durationMinutes < $expectedMinutes) {
            $late = $expectedMinutes - $durationMinutes;
            $lateFormatted = sprintf('%02dh %02dm', floor($late / 60), $late % 60);
        }

        // --- Update record ---
        $engineer_attendance->update([
            'check_out' => $checkOut,
            'duration' => $durationFormatted,
            'overtime' => $overtimeFormatted,
            'late' => $lateFormatted,
            'attendance_type' => 'P',
            'updated_by' => \Auth::user()->id,
        ]);

        // Update user’s overall attendance status (optional)
        \App\Models\User::where('id', $engineer_attendance->engineer_id)
            ->update(['attendance_status' => 'P']);

       return redirect()
    ->route('engineer-attendance.show', $engineer_attendance->engineer_id) // Pass engineer ID
    ->with('success', 'Attendance successfully updated with recalculated duration, late, and overtime.');


}


}
