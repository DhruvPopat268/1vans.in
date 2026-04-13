@extends('layouts.admin')

@section('page-title')
    {{ __('Employee Attendance - ') . $engineer->name }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('engineer-attendance.index') }}">{{ __('Employee Attendance') }}</a></li>
    <li class="breadcrumb-item">{{ $engineer->name }}</li>
@endsection
@section('action-btn')
    <div class="float-end">

 <a href="{{ route('engineer.attendance.export', ['engineer_id' => $engineer->id, 'month' => request('month')]) }}" data-bs-toggle="tooltip" title="{{__('Export Attendance')}}" class="btn btn-sm btn-primary">
            <i class="fa fa-download"></i> {{ __('Export Attendance') }}
        </a>
            <a href="{{ route('engineer.attendance.export.pdf', ['engineer_id' => $engineer->id, 'month' => request('month')]) }}" data-bs-toggle="tooltip" title="{{__('Export PDF')}}" class="btn btn-sm btn-primary">
            <i class="fa fa-file-pdf"></i> Export PDF
        </a>


    </div>
@endsection

@section('content')

<div class="row g-4">
    <!-- 🟩 Attendance Summary Box -->
    <div class="col-md-6">
        <div class="card p-4 h-100" style="background:#f9fafb; border:none; border-radius:12px;">
            <h4 class="fw-bold mb-1">Attendance Summary</h4>
            <p class="text-muted mb-4" style="font-size:14px;">Overview of employee attendance for {{ date('F Y', strtotime($curMonth)) }}.</p>


            <div class="p-3 bg-white rounded shadow-sm text-center mb-4">
    <div style="position:relative; display:inline-block;">
        <canvas id="attendanceSummaryChart" width="260" height="260"></canvas>
        <div id="centerText"
            style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); text-align:center;">
            <h4 id="totalDaysValue" style="font-weight:700; margin:0; font-size:22px;"></h4>
            <small style="color:#6c757d;">Total Days</small>
        </div>
    </div>
</div>


<div class="d-flex flex-wrap justify-content-between gap-4 mb-4">

    <!-- 🟢 Left: Legend Box -->
    <div class="bg-white p-4 rounded shadow-sm flex-grow-1" style="min-width:260px; max-width:48%;">
        <h6 class="fw-semibold mb-3">Legend</h6>
        <ul class="list-unstyled mb-0" style="font-size:14px;">
            <li class="d-flex justify-content-between align-items-center mb-2">
                <span>
                    <span style="display:inline-block;width:12px;height:12px;background:#22c55e;border-radius:3px;margin-right:8px;"></span>
                    Present
                </span>
                <span>{{ number_format(($presentCount / max(($presentCount + $absentCount + $holidayCount), 1)) * 100, 1) }}%</span>
            </li>
            <li class="d-flex justify-content-between align-items-center mb-2">
                <span>
                    <span style="display:inline-block;width:12px;height:12px;background:#ef4444;border-radius:3px;margin-right:8px;"></span>
                    Absent
                </span>
                <span>{{ number_format(($absentCount / max(($presentCount + $absentCount + $holidayCount), 1)) * 100, 1) }}%</span>
            </li>
            <li class="d-flex justify-content-between align-items-center">
                <span>
                    <span style="display:inline-block;width:12px;height:12px;background:#6b7280;border-radius:3px;margin-right:8px;"></span>
                    Holiday
                </span>
                <span>{{ number_format(($holidayCount / max(($presentCount + $absentCount + $holidayCount), 1)) * 100, 1) }}%</span>
            </li>
        </ul>
    </div>

    <!-- 🔵 Right: Totals Box -->
    <div class="d-flex flex-column justify-content-between gap-3 flex-grow-1" style="min-width:260px; max-width:48%;">
        <div class="p-3 border rounded bg-white shadow-sm d-flex justify-content-between align-items-center">
            <span class="text-muted">Total Present Days</span>
            <span class="fw-bold fs-5 text-success">{{ $presentCount }}</span>
        </div>

        <div class="p-3 border rounded bg-white shadow-sm d-flex justify-content-between align-items-center">
            <span class="text-muted">Total Absent Days</span>
            <span class="fw-bold fs-5 text-danger">{{ $absentCount }}</span>
        </div>

        <div class="p-3 border rounded bg-white shadow-sm d-flex justify-content-between align-items-center">
            <span class="text-muted">Total Holidays</span>
            <span class="fw-bold fs-5 text-secondary">{{ $holidayCount }}</span>
        </div>
    </div>

</div>

        </div>
    </div>

    <!-- 🟦 Time Summary Box -->
    <div class="col-md-6">
        <div class="card p-4 h-100" style="background:#f9fafb; border:none; border-radius:12px;">
            <h4 class="fw-bold mb-1">Total Time Summary</h4>
            <p class="text-muted mb-4" style="font-size:14px;">Breakdown of total hours worked, overtime, and late time.</p>

            <div class="p-3 bg-white rounded shadow-sm text-center mb-4">
                <div style="position:relative; display:inline-block;">
                    <canvas id="timeDonutChart" width="260" height="260"></canvas>
                    <div id="centerText"
                        style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); text-align:center;">
                        <h4 style="font-weight:700; margin:0;">{{ $totalTimeFormatted }}</h4>
                        <small style="color:#6c757d;">Total Work Time</small>
                    </div>
                </div>
            </div>

            <div class="p-3 border rounded bg-white shadow-sm">
                <h6 style="font-weight:600; color:#1c1c1e;">Time Breakdown</h6>
                <div style="display:flex; justify-content:space-between; margin-top:8px;">
                    <span><span style="display:inline-block;width:12px;height:12px;background:#3b82f6;border-radius:3px;margin-right:8px;"></span> Total Working Hours</span>
                    <strong>{{ $totalDurationFormatted }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:6px;">
                    <span><span style="display:inline-block;width:12px;height:12px;background:#f97316;border-radius:3px;margin-right:8px;"></span> Total Overtime</span>
                    <strong>{{ $totalOvertimeFormatted }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:6px;">
                    <span><span style="display:inline-block;width:12px;height:12px;background:#ef4444;border-radius:3px;margin-right:8px;"></span> Total Late</span>
                    <strong>{{ $totalLateFormatted }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row" style="margin-top: 20px;">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>{{ $engineer->name }} — Attendance ({{ $curMonth }})</h5>

                  <!-- Attendance Color Legend -->
                <div class="mb-3 d-flex gap-3 align-items-center flex-wrap">
                    <span class="px-3 py-1" style="background-color: #d4edda; color: #155724; border-radius: 4px;">
                        {{ __('Holiday') }}
                    </span>
                    <span class="px-3 py-1" style="background-color: #ffe5b4; color: #856404; border-radius: 4px;">
                        {{ __('Absent') }}
                    </span>
                    <span class="px-3 py-1" style="background-color: #f8d7da; color: #721c24; border-radius: 4px;">
                        {{ __('Overtime > 10h') }}
                    </span>
                </div>

               <div class="d-flex align-items-center mt-2 mt-md-0">
                <form method="GET" action="{{ route('engineer-attendance.show', $engineer->id) }}" class="d-flex align-items-center">
                    <input type="month" name="month" class="form-control form-control-sm me-2"
                           value="{{ request('month', date('Y-m')) }}">
                     <button type="submit" class="btn btn-sm btn-primary me-2">
                        {{ __('Filter') }}
                    </button>


                     <!-- ✅ Reset Button -->
                    <a href="{{ route('engineer-attendance.show', $engineer->id) }}" class="btn btn-sm btn-secondary">
                        {{ __('Reset') }}
                    </a>
                </form>
                <!-- Totals Summary -->
                <!-- <div class="ms-3 d-flex flex-column flex-sm-row align-items-sm-center gap-2">-->
                <!--   <span class="badge text-dark fs-5 fw-bold" style="background-color:#3ec9d6">{{ __('Total Duration: ') . ($totalDurationFormatted ?? '-') }}</span>-->
                <!--   <span class="badge text-dark fs-5 fw-bold" style="background-color:#ffa21d">{{ __('Total Late: ') . ($totalLateFormatted ?? '-') }}</span>-->
                <!--   <span class="badge text-dark fs-5 fw-bold" style="background-color:#6fd943">{{ __('Total Overtime: ') . ($totalOvertimeFormatted ?? '-') }}</span>-->
                <!-- </div>-->
                </div>
            </div>

            <div class="card-body table-border-style">


                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>{{ __('Action') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Project') }}</th>
                                <th>{{ __('Check In') }}</th>
                                <th>{{ __('Check Out') }}</th>
                                <th>{{ __('Duration') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Late') }}</th>
                                <th>{{ __('Overtime') }}</th>
                                {{-- <th>{{ __('Check-In Location') }}</th>
                                <th>{{ __('Check-Out Location') }}</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendanceList as $att)
                                 @php
                        // Default row color
                        $rowColor = '';

                        // 1️⃣ If attendance_type is H → Green
                        if ($att['attendance_type'] === 'H') {
                            $rowColor = 'background-color: #d4edda;'; // light green
                        }
                        // 2️⃣ If attendance_type is A → Orange
                        elseif ($att['attendance_type'] === 'A') {
                            $rowColor = 'background-color: #ffe5b4;'; // light orange
                        }

                       // 3️⃣ Parse overtime correctly (format "05h 09m")
                        $overtime = $att['overtime'] ?? '00h 00m';
                        preg_match('/(\d+)h\s*(\d+)m/', $overtime, $matches);

                        $hours = isset($matches[1]) ? (int) $matches[1] : 0;
                        $minutes = isset($matches[2]) ? (int) $matches[2] : 0;
                        $totalMinutes = ($hours * 60) + $minutes;

                        // 4️⃣ Red background if > 10 hours (600 minutes)
                        if ($totalMinutes > 600) {
                            $rowColor = 'background-color: #f8d7da;'; // light red
                        }
                    @endphp

                    <tr style="{{ $rowColor }}">
      <td>
    @if($totalMinutes > 600)
        <div class="action-btn me-2">
            <a href="#"
               class="mx-3 btn btn-sm align-items-center bg-info"
               data-url="{{ route('engineer-attendance.edit', $att['id']) }}"
               data-ajax-popup="true"
               data-size="md"
               data-bs-toggle="tooltip"
               title="{{ __('Edit') }}"
               data-title="{{ __('Edit Attendance') }}">
                <i class="ti ti-pencil text-white"></i>
            </a>
        </div>
    @endif
</td>
                                    <td>{{ \Carbon\Carbon::parse($att['date'])->format('d M Y') }}</td>
                                    <td>{{ $att['project'] }}</td>
                                    <td>{{ $att['check_in'] }}</td>
                                    <td>{{ $att['check_out'] }}</td>
                                    <td>{{ $att['duration'] }}</td>
                                    <td>
    @if($att['attendance_type'] === 'P')
        {{ __('Present') }}
    @elseif($att['attendance_type'] === 'A')
        {{ __('Absent') }}
    @elseif($att['attendance_type'] === 'H')
        {{ __('Holiday') }}
    @else
        {{ $att['attendance_type'] ?? '-' }}
    @endif
</td>
                                    <td>{{ $att['late'] }}</td>
                                    <td>{{ $att['overtime'] }}</td>
                                    {{-- <td>
                                        @if($att['check_in_lat'] != '-' && $att['check_in_long'] != '-')
                                            <a href="https://maps.google.com/?q={{ $att['check_in_lat'] }},{{ $att['check_in_long'] }}"
                                               target="_blank">View</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($att['check_out_lat'] != '-' && $att['check_out_long'] != '-')
                                            <a href="https://maps.google.com/?q={{ $att['check_out_lat'] }},{{ $att['check_out_long'] }}"
                                               target="_blank">View</a>
                                        @else
                                            -
                                        @endif
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">
                                                {{ __('No attendance data found for this month.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                        </div>
                    </div>
        </div>
    </div>
</div>

<!-- ✅ Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const present = {{ $presentCount }};
    const absent = {{ $absentCount }};
    const holiday = {{ $holidayCount }};
    const total = present + absent + holiday;

    // ✅ Set total days inside the center text div
    document.getElementById('totalDaysValue').textContent = total;

    const ctx = document.getElementById('attendanceSummaryChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Present', 'Absent', 'Holiday'],
            datasets: [{
                data: [present, absent, holiday],
                backgroundColor: ['#22c55e', '#ef4444', '#6b7280'],
                borderWidth: 0,
                cutout: '70%',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const value = tooltipItem.raw;
                            const percent = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${tooltipItem.label}: ${value} (${percent}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    function timeToMinutes(timeStr) {
        const matches = timeStr.match(/(\d+)h\s*(\d+)m/);
        return matches ? (parseInt(matches[1]) * 60 + parseInt(matches[2])) : 0;
    }

    const totalDuration = timeToMinutes("{{ $totalDurationFormatted }}");
    const totalLate = timeToMinutes("{{ $totalLateFormatted }}");
    const totalOvertime = timeToMinutes("{{ $totalOvertimeFormatted }}");

    const ctx = document.getElementById('timeDonutChart').getContext('2d');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Duration', 'Late', 'Overtime'],
            datasets: [{
                data: [totalDuration, totalLate, totalOvertime],
                backgroundColor: ['#3b82f6', '#f97316', '#ef4444'],
                borderColor: '#fff',
                borderWidth: 2,
                hoverOffset: 6,
                cutout: '75%' // makes it donut-style
            }]
        },
        options: {
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const total = totalDuration + totalLate + totalOvertime;
                            const value = tooltipItem.raw;
                            const percent = ((value / total) * 100).toFixed(1);
                            const h = Math.floor(value / 60);
                            const m = value % 60;
                            return `${tooltipItem.label}: ${h}h ${m}m (${percent}%)`;
                        }
                    }
                }
        },
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>

@endsection
