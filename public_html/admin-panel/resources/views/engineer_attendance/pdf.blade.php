<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee Attendance Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
         th { background-color: #000000; color:white; }
        .header {
            text-align: left;
           position: fixed;
           top: 5px;
           width: 100%;
           background-color: transparent;
           border-bottom: 1px solid #ddd;
           z-index: 1000;
           margin-top: -25%;
           display: flex;
           justify-content: space-between;
           padding: 10px;
        }

            tbody tr:nth-child(odd) {
    background-color: #fbfafa;
}

tbody tr:nth-child(even) {
    background-color: #e9e9e9a1;
}

         .header-logo img {
    max-width: 80px;
    max-height: 60px;
    height: 70px;
    width: 60px;
    margin-top: 50px;
    object-fit: contain;
}

         .header-info-details {
    text-align: left;
    margin-top: -70px;
    margin-left: 80px;
}

.header-info-details h3,
.header-info-details p, {
    margin: 0;           /* Removes default margin */
    padding: 0;          /* Optional: also remove padding */
    line-height: 1.5;    /* Optional: tighten line spacing */
}
.header-info h2,
.header-info {
    margin: 0;           /* Removes default margin */
    padding: 0;          /* Optional: also remove padding */
    line-height: 1.2;    /* Optional: tighten line spacing */
}


        .header-info {
            text-align: center;
        margin-top: 0px;
         margin-bottom: 10px;
        }
        .content {
            margin-top: -40px;
            padding: 20px;
        }

        .walkaround-info-box {
            border: 1.5px solid #cacaca;
            background-color: transparent;
            padding: 15px;
            width: 100%;
            margin-bottom: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .signature {
            width: 100px; /* Adjust the size as needed */
            height: auto;
            margin-top: 20px; /* Add some space above the signature */
        }
        @page {
            margin-top: 15%;
        }
    </style>
</head>
<body>
     <div class="header">
        <div class="header-logo">
            @if (!empty($profileImg))
                <img src="{{ $profileImg }}" alt="Logo"/>
            @else
                <p>No Logo available</p>
            @endif
        </div>
         <div class="header-info-details">
    <h3>{{ $project->company_name ?? 'N/A' }}</h3>
    <p>{{ $project->project_name ?? 'N/A' }}</p>
    <p>{{ $project->project_number ?? 'N/A' }}</p>
    <p>
        {!! nl2br(e(wordwrap($project->site_address ?? 'N/A', 80, "\n", true))) !!}
    </p>
</div>


    </div>
        <div class="content">
         <div class="header-info">
            <h2>
                EMPLOYEE ATTENDANCE REPORT - {{ $engineer->name }} - {{ $monthName }}
            </h2>

        </div>
        
               <!-- Totals Summary in One Row -->
<!--<div style="display: flex; justify-content: space-between; margin-bottom: 15px; padding: 8px 12px; margin-right:-35px; background-color: #f1f1f1; border-radius: 5px; font-size: 14px;">-->
<!--    <div><strong>{{ __('Total Duration:') }}</strong> {{ $totalDurationFormatted ?? '-' }}</div>-->
<!--    <div><strong>{{ __('Total Late:') }}</strong> {{ $totalLateFormatted ?? '-' }}</div>-->
<!--    <div><strong>{{ __('Total Overtime:') }}</strong> {{ $totalOvertimeFormatted ?? '-' }}</div>-->
<!--</div>-->

<!--<div style="display: flex; justify-content: space-between; margin-bottom: 15px; padding: 8px 12px; margin-right:-35px; border: 1.5px solid #cacaca; font-size: 14px;">-->


    <!-- Left Side: Totals Summary -->
<!--    <div style="flex: 1; text-align: left;">-->
<!--        <p style="margin: 4px 0;"><strong>{{ __('Total Duration:') }}</strong> {{ $totalDurationFormatted ?? '-' }}</p>-->
<!--        <p style="margin: 4px 0;"><strong>{{ __('Total Late:') }}</strong> {{ $totalLateFormatted ?? '-' }}</p>-->
<!--        <p style="margin: 4px 0;"><strong>{{ __('Total Overtime:') }}</strong> {{ $totalOvertimeFormatted ?? '-' }}</p>-->
<!--    </div>-->

    <!-- Right Side: Attendance Pie Chart -->
<!--    <div style="flex: 0 0 160px; text-align: center; margin-top:-80px;margin-left:350px;">-->
<!--        <img src="{{ $chartBase64 }}" alt="Attendance Chart" style="width:120px; height:120px; display:block; margin:auto;background-color: #f1f1f1 ;">-->

<!--        <p style="font-size: 12px; margin-top: 8px;">-->
<!--            <span style="background-color: #28a745; color: #fff; padding: 3px 8px; border-radius: 4px; font-weight: bold;">-->
<!--                Present: {{ $presentCount }}-->
<!--            </span>-->
<!--            &nbsp;-->
<!--            <span style="background-color: #ffc107; color: #000; padding: 3px 8px; border-radius: 4px; font-weight: bold;">-->
<!--                Absent: {{ $absentCount }}-->
<!--            </span>-->
<!--            &nbsp;-->
<!--            <span style="background-color: #17a2b8; color: #fff; padding: 3px 8px; border-radius: 4px; font-weight: bold;">-->
<!--                Holiday: {{ $holidayCount }}-->
<!--            </span>-->
<!--        </p>-->
<!--    </div>-->

<!--</div>-->

<div style="display: flex; justify-content: space-between; gap: 15px; margin-bottom: 15px; font-size: 14px;">

    <!-- 🟢 Right: Attendance Summary Box -->
<div style="flex: 0 0 48%; border: 1.5px solid #cacaca; padding: 8px 12px; background-color: #fff; text-align: left; display: flex; align-items: left; justify-content: space-between;margin-right:335px;">

    <!-- Chart -->
    <div style="flex: 0 0 auto; text-align: right;">
        <h5 style="font-size: 13px; margin-bottom: 6px; text-align:right;">Attendance Summary</h5>
        <img src="{{ $chartBase64 }}" alt="Attendance Chart"
            style="width:130px; height:130px; background-color:#f9f9f9; border-radius:8px;">
    </div>

    <!-- Text beside chart -->
    <div style="flex: 1; margin-left: 15px; font-size: 12px; line-height: 1.6;margin-top:-100px;margin-bottom:50px;"">
        <p style="margin: 0 0 4px 0;">
            <span style="background-color: #22c55e; color: #fff; padding: 3px 8px; border-radius: 4px;">
                Present: {{ $presentCount }}
            </span>
        </p>
        <p style="margin: 0 0 4px 0;">
            <span style="background-color: #ef4444; color: #fff; padding: 3px 8px; border-radius: 4px;">
                Absent: {{ $absentCount }}
            </span>
        </p>
        <p style="margin: 0;">
            <span style="background-color: #6b7280; color: #fff; padding: 3px 8px; border-radius: 4px;">
                Holiday: {{ $holidayCount }}
            </span>
        </p>
    </div>

</div>

   <!-- 🟣 Left: Total Time Summary Box -->
<div style="flex: 0 0 48%; border: 1.5px solid #cacaca; padding: 8px 12px; background-color: #fff; text-align: left; display: flex; align-items: right; justify-content: space-between; margin-top:-215px;margin-left:345px;margin-right:-30px;">

    <!-- Chart -->
    <div style="flex: 0 0 auto; text-align: right;">
        <h5 style="font-size: 13px; margin-bottom: 6px; text-align:right;">Total Time Summary</h5>
        <img src="{{ $timeChartBase64 }}" alt="Total Time Chart"
            style="width:130px; height:130px; background-color:#f9f9f9; border-radius:8px;">
    </div>

    <!-- Text beside chart -->
    <div style="flex: 1; margin-left: 15px; font-size: 12px; line-height: 1.6; margin-top:-100px;margin-bottom:50px;">
        <p style="margin: 0 0 4px 0;">
            <span style="background-color: #3b82f6; color: #fff; padding: 3px 8px; border-radius: 4px;">
                Total Working Hours: {{ $totalDurationFormatted }}
            </span>
        </p>
        
        <p style="margin: 0 0 4px 0;">
            <span style="background-color: #f97316; color: #fff; padding: 3px 8px; border-radius: 4px;">
                Total Overtime: {{ $totalOvertimeFormatted }}
            </span>
        </p>
        <p style="margin: 0 0 4px 0;">
            <span style="background-color: #ef4444; color: #fff; padding: 3px 8px; border-radius: 4px;">
                Total Late: {{ $totalLateFormatted }}
            </span>
        </p>
    </div>

</div>


</div>

      <div class="walkaround-info-box">

        <table>
            <thead>

                <tr>

                    <th>Date</th>
                <th>Project</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Duration</th>
                <th>Attendance Type</th>
                <th>Late</th>
                <th>Overtime</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $attendance)
                                @php
            // Default row color
            $rowColor = '';

            // Attendance type
            if ($attendance->attendance_type === 'H') {
                $rowColor = 'background-color: #d4edda;'; // light green
            } elseif ($attendance->attendance_type === 'A') {
                $rowColor = 'background-color: #ffe5b4;'; // light orange
            }

            // Overtime > 10h 00m
            $overtime = $attendance->overtime ?? '00h 00m';
            preg_match('/(\d+)h\s*(\d+)m/', $overtime, $matches);
            $hours = isset($matches[1]) ? (int) $matches[1] : 0;
            $minutes = isset($matches[2]) ? (int) $matches[2] : 0;
            $totalMinutes = ($hours * 60) + $minutes;

            if ($totalMinutes > 600) { // 10 hours = 600 minutes
                $rowColor = 'background-color: #f8d7da;'; // light red
            }
        @endphp

        <tr style="{{ $rowColor }}">
                    <td>{{ \Carbon\Carbon::parse($attendance->check_in)->format('Y-m-d') }}</td>
                    <td>{{ $attendance->project->project_name ?? '-' }}</td>
                    <td>{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : '-' }}</td>

                   <td>{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') : '-' }}</td>

                    <td>{{ $attendance->duration ?? '-' }}</td>
                    <td>
    @if($attendance->attendance_type === 'P')
        {{ __('Present') }}
    @elseif($attendance->attendance_type === 'A')
        {{ __('Absent') }}
    @elseif($attendance->attendance_type === 'H')
        {{ __('Holiday') }}
    @else
        {{ $attendance->attendance_type ?? '-' }}
    @endif
</td>
                    <td>{{ $attendance->late ?? '-' }}</td>
                    <td>{{ $attendance->overtime ?? '-' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>


    </div>



    </div>

</body>
</html>
