<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Project;
use App\Models\Utility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event as GoogleEvent;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class HolidayController extends Controller
{

    public function index(Request $request)
    {
        if(\Auth::user()->can('manage holiday'))
        {
            $user = \Auth::user();
            $holidays = Holiday::where('project_id', $user->project_assign_id);
            if(!empty($request->start_date))
            {
                $holidays->where('date', '>=', $request->start_date);
            }
            if(!empty($request->end_date))
            {
                $holidays->where('date', '<=', $request->end_date);
            }
            $holidays = $holidays->get();

            return view('holiday.index', compact('holidays'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function create()
    {
        if(\Auth::user()->can('create holiday'))
        {
            $settings = Utility::settings();
            return view('holiday.create',compact('settings'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function store(Request $request)
    {
    if (\Auth::user()->can('create holiday')) {
            $validator = \Validator::make(
            $request->all(),
            [
                                   'date' => 'required',
                                   'occasion' => 'required',
                                   'end_date' => 'required',
                               ]
            );

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->getMessageBag()->first());
        }

        // Ensure user has a project assigned
        $project_id = \Auth::user()->project_assign_id;

        if (!$project_id) {
            return redirect()->back()->with('error', __('Please assign a project before creating a holiday.'));
            }

        $holiday = new Holiday();
        $holiday->date = $request->date;
        $holiday->end_date = $request->end_date;
        $holiday->occasion = $request->occasion;
        $holiday->project_id = $project_id; // ✅ save the project id
        $holiday->created_by = \Auth::user()->id;
            $holiday->save();

        // Optional: Sync with Google Calendar
        if ($request->get('synchronize_type') == 'google_calender') {
            $type = 'holiday';
            $calendarRequest = new Holiday();
            $calendarRequest->title = $request->occasion;
            $calendarRequest->start_date = $request->date;
            $calendarRequest->end_date = $request->end_date;
            Utility::addCalendarData($calendarRequest, $type);
            }

        // Optional: Webhook or Notification (uncomment if needed)
        // $module = 'New Holiday';
        // $webhook = Utility::webhookSetting($module);

            return redirect()->route('holiday.index')->with('success', 'Holiday successfully created.');
        }

            return redirect()->back()->with('error', __('Permission denied.'));
        }
        
    //         public function store(Request $request)
    // {
    //     if(\Auth::user()->can('create holiday'))
    //     {
    //         $validator = \Validator::make(
    //             $request->all(), [
    //                               'date' => 'required',
    //                               'occasion' => 'required',
    //                               'end_date' => 'required',
    //                           ]
    //         );

    //         if($validator->fails())
    //         {
    //             $messages = $validator->getMessageBag();

    //             return redirect()->back()->with('error', $messages->first());
    //         }

    //         $holiday             = new Holiday();
    //         $holiday->date       = $request->date;
    //         $holiday->end_date     = $request->end_date;
    //         $holiday->occasion   = $request->occasion;
    //         $holiday->created_by = \Auth::user()->creatorId();
    //         $holiday->save();

    //         //For Notification
    //         $setting  = Utility::settings(\Auth::user()->creatorId());
    //         $holidayNotificationArr = [
    //             'holiday_title' => $request->occasion,
    //             'holiday_date' => $request->date,
    //         ];
    //         //Slack Notification
    //         if(isset($setting['holiday_notification']) && $setting['holiday_notification'] ==1)
    //         {
    //             Utility::send_slack_msg('new_holiday', $holidayNotificationArr);
    //         }
    //         //Telegram Notification
    //         if(isset($setting['telegram_holiday_notification']) && $setting['telegram_holiday_notification'] ==1)
    //         {
    //             Utility::send_telegram_msg('new_holiday', $holidayNotificationArr);
    //         }

    //         //For Google Calendar
    //         if($request->get('synchronize_type')  == 'google_calender')
    //         {

    //             $type ='holiday';
    //             $request1=new Holiday();
    //             $request1->title=$request->occasion;
    //             $request1->start_date=$request->date;
    //             $request1->end_date=$request->end_date;

    //             Utility::addCalendarData($request1 , $type);

    //         }

    //         //webhook
    //         $module ='New Holiday';
    //         $webhook =  Utility::webhookSetting($module);
    //         if($webhook)
    //         {
    //             $parameter = json_encode($holiday);
    //             $status = Utility::WebhookCall($webhook['url'],$parameter,$webhook['method']);

    //             if($status == true)
    //             {
    //                 return redirect()->route('holiday.index')->with('success', 'Holiday successfully created.');
    //             }
    //             else
    //             {
    //                 return redirect()->back()->with('error', __('Holiday successfully created, Webhook call failed.'));
    //             }
    //         }

    //         return redirect()->route('holiday.index')->with('success', 'Holiday successfully created.');
    //     }
    //     else
    //     {
    //         return redirect()->back()->with('error', __('Permission denied.'));
    //     }

    // }



    public function show(Holiday $holiday)
    {
        //
    }


    public function edit(Holiday $holiday)
    {
        if(\Auth::user()->can('edit holiday'))
        {
            return view('holiday.edit', compact('holiday'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function update(Request $request, Holiday $holiday)
    {
        if(\Auth::user()->can('edit holiday'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'occasion' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $holiday->date     = $request->date;
            $holiday->end_date       = $request->end_date;
            $holiday->occasion = $request->occasion;
            $holiday->save();

            return redirect()->route('holiday.index')->with('success', 'Holiday successfully updated.');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }
    
        public function importFile()
{
    if (\Auth::user()->can('create holiday')) {
        return view('holiday.import');
    } else {
        return redirect()->back()->with('error', __('Permission denied.'));
    }
}

public function import(Request $request)
{
    $rules = [
        'file' => 'required|mimes:xlsx,xls,csv',
    ];

    $validator = \Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $messages = $validator->getMessageBag();
        return redirect()->back()->with('error', $messages->first());
    }

    $user = Auth::user();
    $userId = $user->id;
    $assignedProjectId = $user->project_assign_id; // Single project ID

    $holidayData = (new \App\Imports\HolidayImport)->toArray($request->file('file'))[0];
    $totalRows = count($holidayData) - 1;
    $successCount = 0;
    $skippedRows = []; // store skipped rows

    foreach ($holidayData as $key => $row) {
        if ($key === 0) continue; // Skip header row

        $date = $this->parseExcelDate($row[0] ?? null);
        $endDate = $this->parseExcelDate($row[1] ?? null);
        $occasion = trim($row[2] ?? '');
        $projectName = trim($row[3] ?? '');

        $skipReason = null;

        // Validate essential fields
        if (empty($date) || empty($occasion) || empty($projectName)) {
            $skipReason = "Missing required fields";
        }

        // Find project
        $project = Project::where('project_name', $projectName)->first();
        if (!$project) {
            $skipReason = "Project '{$projectName}' not found";
        } elseif ($project->id != $assignedProjectId) {
            $skipReason = "Project '{$projectName}' is not assigned to you";
        }

        if ($skipReason) {
            $skippedRows[] = [
                'row' => $key + 1,
                'name' => $occasion,
                'project' => $projectName,
                'reason' => $skipReason,
            ];
            continue; // skip this row
        }

        // Create Holiday
        $holiday = Holiday::create([
            'date' => $date,
            'end_date' => $endDate,
            'occasion' => $occasion,
            'project_id' => $project->id,
            'created_by' => $userId,
        ]);

        $successCount++;

        // Google Calendar sync
        if ($request->get('synchronize_type') == 'google_calender') {
            $type = 'holiday';
            $calendarRequest = new Holiday();
            $calendarRequest->title = $occasion;
            $calendarRequest->start_date = $date;
            $calendarRequest->end_date = $endDate;
            Utility::addCalendarData($calendarRequest, $type);
        }
    }

    $message = "$successCount record(s) successfully imported.";

    // Pass skipped rows to session for Blade
    if (!empty($skippedRows)) {
        return redirect()->route('holiday.index')
            ->with('error', $message)
            ->with('skippedRows', $skippedRows);
    }

    return redirect()->route('holiday.index')->with('success', $message);
}

private function parseExcelDate($value)
{
    if (empty($value)) return null;

    try {
        // If numeric (Excel date serial number)
        if (is_numeric($value)) {
            return Carbon::instance(ExcelDate::excelToDateTimeObject($value))->format('Y-m-d');
        }

        // Try to parse string date directly
        return Carbon::parse($value)->format('Y-m-d');
    } catch (\Exception $e) {
        // If Carbon fails, try manual replacement
        $try = date_create_from_format('d/m/Y', str_replace(['.', '-'], '/', $value));
        if ($try) {
            return Carbon::instance($try)->format('Y-m-d');
        }

        return null; // Invalid or unrecognized format
    }
}


    public function destroy(Holiday $holiday)
    {
        if(\Auth::user()->can('delete holiday'))
        {
            $holiday->delete();

            return redirect()->route('holiday.index')->with('success', 'Holiday successfully deleted.');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }
    
    

    public function calender(Request $request)
    {

        if(\Auth::user()->can('manage holiday'))
        {
            $transdate = date('Y-m-d', time());
           $user = \Auth::user();
            $holidays = Holiday::where('project_id', $user->project_assign_id);

            if(!empty($request->start_date))
            {
                $holidays->where('date', '>=', $request->start_date);
            }
            if(!empty($request->end_date))
            {
                $holidays->where('date', '<=', $request->end_date);
            }

            $holidays = $holidays->get();

            $arrHolidays = [];

            foreach($holidays as $holiday)
            {
                $arr['id']        = $holiday['id'];
                $arr['title']     = $holiday['occasion'];
                $arr['start']     = $holiday['date'];
                $arr['end']       = $holiday['end_date'];
                $arr['className'] = 'event-primary';
                $arr['url']       = route('holiday.edit', $holiday['id']);
                $arrHolidays[]    = $arr;
            }
            $arrHolidays = str_replace('"[', '[', str_replace(']"', ']', json_encode($arrHolidays)));

            return view('holiday.calender', compact('arrHolidays','transdate','holidays'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }

    //for Google Calendar
    public function get_holiday_data(Request $request)
    {

        if($request->get('calender_type') == 'goggle_calender')
        {
            $type ='holiday';
            $arrayJson =  Utility::getCalendarData($type);
        }
        else
        {
             $user = \Auth::user();
            $data = Holiday::where('project_id', $user->project_assign_id)->where('created_by', '=', \Auth::user()->creatorId())->get();
            // $data =Holiday::where('created_by', '=', \Auth::user()->creatorId())->get();


            $arrayJson = [];
            foreach($data as $val)
            {
//                dd($val);

                $end_date=date_create($val->end_date);
                date_add($end_date,date_interval_create_from_date_string("1 days"));
                $arrayJson[] = [
                    "id"=> $val->id,
                    "title" => $val->occasion,
                    "start" => $val->date,
                    "end" => date_format($end_date,"Y-m-d H:i:s"),
                    "className" => 'event-primary',
                    "textColor" => '#51459d',
                    'url'      => route('holiday.edit', $val->id),
                    "allDay" => true,
                ];
            }
        }

        return $arrayJson;
    }

}
