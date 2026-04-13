@extends('layouts.admin')
@section('page-title')
{{__('Smart Dashboard')}}
@endsection
@push('script-page')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
  document.addEventListener("DOMContentLoaded", function () {

    /* ---------------- EQUIPMENT CHART ---------------- */

  const equipmentData = @json($home_data['equipment_chart'] ?? []);
const equipCanvas = document.getElementById('equipmentChart');

if (equipCanvas && equipmentData.length) {

    Chart.register(ChartDataLabels);

    new Chart(equipCanvas.getContext('2d'), {

        type: 'bar',

       data: {
           labels: equipmentData.map(e => e.name),

           datasets: [{

               data: equipmentData.map(e => e.total_amount),

               backgroundColor: [
                    '#3aa7dc', '#64a7fa', '#6fd943', '#3a4454', '#95ceef',
                   '#b9c4d1', '#0d589f', '#55EFC4', '#FFEAA7', '#636E72',
                   '#B2BEC3', '#D63031', '#E67E22', '#FF6F61', '#6AB04C',
                   '#4834D4', '#22A6B3', '#BE2EDD', '#130F40', '#535C68',
                   '#CAD3C8', '#F8EFBA', '#82589F', '#B33771', '#00CEC9',
                   '#F8A5C2', '#F97F51', '#1B9CFC', '#58B19F', '#F3A683'
               ],

                borderRadius: 6,
                barThickness: 18
           }]
       },

       options: {
           responsive: true,
           maintainAspectRatio: false,

            /** ✅ Horizontal Graph **/
            indexAxis: 'y',

           plugins: {
               legend: {

                    display: false
               },



               tooltip: {
                   callbacks: {
                       label: function(context) {
                            const value = context.parsed.x || 0;
                            return `Total Amount: ₹ ${value.toLocaleString()}`;
                       }
                   }
                },

                /** ✅ TEXT INSIDE BAR ⭐ **/
                datalabels: {
                    anchor: 'center',
                    align: 'center',

                    formatter: function(value) {
                        return '₹ ' + value.toLocaleString();
                    },

                    color: '#fff',              // white text inside bar
                    font: {
                        weight: 'bold',
                        size: 11
                    },

                    clamp: true                // text bar ni bahar na jai
               }
           },

           scales: {

                /** ✅ X Axis TOP 😎 **/
                x: {
                    position: 'top',   // ⭐⭐⭐ IMPORTANT ⭐⭐⭐
                   beginAtZero: true,

                   title: {
                       display: true,
                       text: 'TOTAL AMOUNT',
                        font: { size: 14, weight: 'bold' }
                   },

                   ticks: {
                        callback: value => '₹ ' + value.toLocaleString(),
                       font: { size: 14 },
                       color: '#000'
                   },

                   grid: {
                        color: '#eef2f7'
                   }
               },

                /** ✅ Equipment Names LEFT **/
                y: {
                   title: {
                       display: true,
                       text: 'EQUIPMENT NAME',
                        font: { size: 14, weight: 'bold' }
                   },

                   ticks: {
                        font: { size: 14, weight: 'bold' },
                        color: '#000'
                   },

                   grid: {
                       display: false
                   }
               }
           }
       }
   });
}

    /* ---------------- BUDGET CHART ---------------- */

   const budgetData = @json($home_data['budget_chart'] ?? []);
    const budgetCanvas = document.getElementById('budgetChart');

    if (budgetCanvas && budgetData.budget !== undefined) {

        new Chart(budgetCanvas.getContext('2d'), {
       type: 'doughnut',

       data: {
           labels: ['Project Budget', 'Used Amount'],

           datasets: [{
               data: [budgetData.budget, budgetData.used],
               backgroundColor: ['#3aa7dc', '#0d589f'],
               borderWidth: 1
           }]
       },

           options: {
               responsive: true,
               maintainAspectRatio: false,

               plugins: {
                   legend: {
                        position: 'bottom'
                       }
                   }
               }
       });
    }

   });
</script>
<style>
    .days-number {
        font-size: 42px;
        font-weight: 700;
        color: #004466;
        /* Bootstrap Primary */
        line-height: 1;
    }

    .days-label {
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 1px;
        color: #6c757d;
        /* Muted */
        text-transform: uppercase;
        padding-bottom: 6px;
        /* Align with number baseline */
    }

    .stat-card {
       background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1)  !important;
        border: 1px solid #e1e1e1  !important;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
   
    
    

    .stat-title {
        font-size: 15px;
        font-weight: 600;
        letter-spacing: 1px;
        color: #000000;
    }

    .stat-value {
        font-weight: 700;
        margin-bottom: 4px;
    }

    /* Icon Box */
    .stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    /* Soft Background Variants */
    .bg-soft-primary {
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    .bg-soft-danger {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    /* Progress Bar Polish */
    .stat-card .progress {
        height: 6px;
        border-radius: 10px;
        background-color: #edf2f7;
    }

    .stat-card .progress-bar {
        border-radius: 10px;
    }

    .material-table th {
        font-size: 12px;
        text-transform: uppercase;
        color: #9aa4b2;
        font-weight: 600;
    }

    .table thead th {
        border-bottom: 1px solid #f1f1f1;
        font-size: 14px;
        color: #94a3b8;
        background-color: #f8fafc !important;
        text-transform: uppercase;
        font-weight: bold;
    }

    .material-table td {
        font-size: 14px;
        font-weight: bold;
        border-top: 1px solid #f1f3f7;
    }

    .material-table tbody tr:first-child td {
        border-top: none;
    }

    /* Soft badge colors */
    .bg-soft-success {
        background: rgba(40, 167, 69, 0.12);
    }

    .bg-soft-danger {
        background: rgba(220, 53, 69, 0.12);
    }

    .bg-soft-primary {
        background: rgba(13, 110, 253, 0.12);
    }

    /* Progress bar styling */
    .progress {
        height: 6px;
        border-radius: 10px;
        background: #eef2f7;
    }

    .progress-bar {
        border-radius: 10px;
        background: #0d6efd;
    }

    /* Dashed Button */
    .dashed-btn {
        border: 1px dashed #d6dbe4;
        background: #f9fbfd;
        font-weight: 600;
        font-size: 13px;
        color: #7b8794;
    }

    .dashed-btn:hover {
        background: #f1f5f9;
    }

    .material-scroll {
    max-height: 260px;     /* ✅ approx 4 rows height */
    overflow-y: auto;
}

/* Clean scrollbar 😎 */
.material-scroll::-webkit-scrollbar {
    width: 6px;
}

.material-scroll::-webkit-scrollbar-thumb {
    background: rgba(0,0,0,0.15);
    border-radius: 10px;
}

.material-scroll::-webkit-scrollbar-track {
    background: transparent;
}

.material-table thead th {
    position: sticky;
    top: 0;
    background: white;
    z-index: 2;
}

.inventory-scroll {
    max-height: 260px;   /* Same as material table 😎 */
    overflow-y: auto;
    padding-right: 4px;
}

/* Smooth scrollbar */
.inventory-scroll::-webkit-scrollbar {
    width: 6px;
}

.inventory-scroll::-webkit-scrollbar-thumb {
    background: rgba(0,0,0,0.15);
    border-radius: 10px;
}

.inventory-scroll::-webkit-scrollbar-track {
    background: transparent;
}

    .legend {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }

    .legend.planned {
        background: #E9ECEF;
    }

    .legend.actual {
        background: #0D3B66;
    }

    .utilization-bar {
        height: 10px;
        background: #E9ECEF;
        /* Planned */
        border-radius: 10px;
        overflow: hidden;
    }

    .actual-bar {
        background: #0D3B66;
        /* Actual */
        border-radius: 10px;
    }

</style>
@endpush
@section('breadcrumb')
 <li class="breadcrumb-item"><a href="{{route('graph.dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Smart Dashboard')}}</a></li>
@endsection
@section('content')
<div class="row">

    <!-- Time Completion Card -->
    <div class="col-md-12 mb-3">
        <div class="card stat-card shadow-sm border-0">
            <div class="card-body py-3">

                <div class="row align-items-center">

                    <!-- Start Date -->
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="me-2 text-primary">
                                <i class="ti ti-calendar-event fs-3"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">PROJECT START DATE</small>
                                <strong>{{ $home_data['time_completion']['start_date'] }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Days Left -->
                    <div class="col-md-4 text-center">
                        <small class="text-muted d-block mb-1">TIME TO COMPLETION</small>

                        <div class="d-flex justify-content-center align-items-end">
                            <span class="days-number {{ $home_data['time_completion']['days_left'] < 0 ? 'text-danger' : '' }}">
                                {{ $home_data['time_completion']['days_left'] }}
                            </span>
                            <span class="days-label ms-2">
                                DAYS LEFT
                            </span>
                        </div>

                        <div class="progress mt-2">
                            <div class="progress-bar" style="width: {{ $home_data['time_completion']['progress'] }}%">
                            </div>
                        </div>
                    </div>

                    <!-- End Date -->
                    <div class="col-md-4">
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="text-end">
                                <small class="text-muted d-block">PROJECT END DATE</small>
                                <strong>{{ $home_data['time_completion']['end_date'] }}</strong>
                            </div>
                            <div class="ms-2 text-primary">
                                <i class="fas fa-calendar-check fs-3"></i>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Stat Cards Section ✅ -->
    <div class="col-md-12 mb-3">
        <div class="row g-3">

            <div class="col-md-3">
                <div class="card stat-card shadow-sm border-0 h-100">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <small class="stat-title">PROJECT COST</small>

                            <div class="stat-icon bg-soft-primary">
                                <i class="ti ti-wallet"></i>
                            </div>
                        </div>

                        <h3 class="stat-value text-primary">
                            ₹{{ number_format($home_data['budget_chart']['budget']) }}
                        </h3>

                        <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
                            <small class="text-muted">Total Spent</small>

                            @php
                            $budget = $home_data['budget_chart']['budget'] ?? 0;
                            $used = $home_data['budget_chart']['used'] ?? 0;

                            $percent = $budget > 0 ? ($used / $budget) * 100 : 0;
                            @endphp

                            <small style="font-size:14px; font-weight: 500;">
                                ₹{{ number_format($used) }}
                                ({{ round($percent) }}%)
                            </small>
                        </div>

                        <div class="progress">
                            <div class="progress-bar" style="width: {{ round($percent) }}%">
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card stat-card shadow-sm border-0 h-100">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <small class="stat-title">COST OVERRUN</small>

                            <div class="stat-icon bg-soft-danger">
                                <i class="ti ti-alert-triangle"></i>
                            </div>
                        </div>

                        <h3 class="stat-value text-danger">
                            +₹{{ number_format($home_data['cost_overrun']['amount']) }}
                        </h3>

                        @php
                        $overrun = $home_data['cost_overrun']['amount'];
                        @endphp

                        <small class="text-muted d-block mt-2">
                            @if($overrun > 0)
                            Budget exceeded due to rising operational costs.
                            @else
                            Spending remains within allocated budget.
                            @endif
                        </small>


                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card stat-card shadow-sm border-0 h-100">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <small class="stat-title">WORK PROGRESS</small>

                            <div class="stat-icon bg-soft-primary">
                                <i class="ti ti-chart-bar"></i>
                            </div>
                        </div>

                        <h3 class="stat-value">
                            {{ $home_data['work_progress'] }}%
                        </h3>

                        <div class="d-flex align-items-center mt-2">

                            <span class="badge
        {{ $home_data['weekly_change'] >= 0 ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">

                                {{ $home_data['weekly_change'] >= 0 ? '+' : '' }}
                                {{ $home_data['weekly_change'] }}%
                            </span>

                            <small class="text-muted ms-2">
                                from last week
                            </small>

                        </div>

                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card stat-card shadow-sm border-0 h-100">
                    <div class="card-body">

                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <small class="stat-title">CURRENT STATUS</small>

                            <div class="stat-icon bg-soft-{{ $home_data['project_status']['color'] }}">
                                <i class="{{ $home_data['project_status']['icon'] }}"></i>
                            </div>
                        </div>

                        <!-- Status Label -->
                        <h3 class="stat-value text-{{ $home_data['project_status']['color'] }}">
                            {{ $home_data['project_status']['label'] }}
                        </h3>

                        <!-- Optional Sub Info (Nice Touch 😎) -->
                        <small class="text-muted d-block mt-1">
                            Project performance indicator
                        </small>

                    </div>
                </div>
            </div>


        </div>
    </div>

    <div class="col-md-12 mt-3 mb-3">

    <!-- ================= FIRST ROW ================= -->
    <div class="row g-3 align-items-stretch">

            <!-- MATERIAL ANALYSIS -->
            <div class="col-md-7">
                <div class="card stat-card shadow-sm border-0 h-100">

                    <div class="card-header d-flex justify-content-between align-items-center py-2">
    <h6 class="mb-0">
        {{ __('MATERIAL ANALYSIS') }}
    </h6>

<form method="GET" action="{{ route('graph.dashboard') }}">
    
    {{-- ✅ Preserve Work Filter --}}
    <input type="hidden" name="main_category_id" value="{{ request('main_category_id') }}">

    <select name="material_category_id"
            class="form-select form-select-sm"
            onchange="this.form.submit()"
            style="width: 180px;">

        <option value="">All Categories</option>

        @foreach($materialCategories as $category)
            <option value="{{ $category->id }}"
                {{ request('material_category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach

    </select>
</form>
</div>

                    <div class="card-body">

                       <div class="table-responsive material-scroll">
                            <table class="table align-middle mb-0 material-table">

                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Total Incoming</th>
                                        <th>Used</th>
                                        <th>Available Stock</th>
                                        <th>Stock Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($home_data['materials'] as $material)
                                    <tr>
                                        <td>{{ $material['name'] }}
                                         @if(!empty($material['db_status']))
        ( {{ ucfirst($material['db_status']) }} )
    @endif
                                        </td>
                                        <td>{{ $material['incoming'] }}</td>
                                        <td>{{ $material['used'] }}</td>
                                        <td>
    {{ $material['remaining'] }}   {{-- ✅ Available --}}
</td>
                                        <td>
                                            <span class="badge bg-soft-{{ $material['color'] }} text-{{ $material['color'] }}">
                                                {{ strtoupper($material['status']) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No materials available
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <!-- INVENTORY HEALTH -->
            <div class="col-md-5">
                <div class="card stat-card shadow-sm border-0 h-100">
                    <div class="card-body">

                        <h6 class="mb-3">INVENTORY HEALTH</h6>

                        <div class="inventory-scroll">

@forelse($home_data['inventory_health'] as $item)

    <div class="mb-3">
        <div class="d-flex justify-content-between">
            <small style="font-size:14px; font-weight: bold;">{{ $item['name'] }}</small>

            <small class="text-{{ $item['color'] }}" style="font-size:14px; font-weight: 500;">
                {{ $item['percent'] }}% Remaining
            </small>
        </div>

        <div class="progress mt-1">
            <div class="progress-bar bg-{{ $item['color'] }}"
                 style="width: {{ $item['percent'] }}%">
            </div>
        </div>
    </div>

@empty

    <div class="text-center text-muted">
        No inventory data available
    </div>

@endforelse

</div>

                </div>
            </div>
        </div>

    </div>

    <!-- ================= SECOND ROW ================= -->
    <div class="row g-3 align-items-stretch mt-3">

        <!-- MATERIAL UTILIZATION -->
        <div class="col-md-7">
            <div class="card stat-card shadow-sm border-0 h-100">

                <div class="card-header py-2">
                    <h6 class="mb-0">
                        {{ __('MATERIAL UTILIZATION') }}
                    </h6>
                </div>

                <div class="card-body material-scroll">

                    @forelse($home_data['materials'] as $material)

                        @php
                            $usedAmount = $material['used_amount'];
                            $totalAmount = $material['total_amount'];

                            $percent = $totalAmount > 0
                                ? ($usedAmount / $totalAmount) * 100
                                : 0;
                                
                        

                            $colors = [
                                 '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7',
                        '#DDA0DD', '#FF9F43', '#54A0FF', '#5F27CD', '#00D2D3',
                        '#FF9FF3', '#FECA57', '#48DBFB', '#1DD1A1', '#F368E0',
                        '#EE5253', '#0ABDE3', '#10AC84', '#F79F1F', '#B33771',
                        '#3B3B98', '#FD7272', '#9AECDB', '#BDC581', '#F8EFBA',
                        '#EAB543', '#6D214F', '#182C61', '#FC427B', '#82589F',
                        '#2C3A47', '#F97F51', '#1B9CFC', '#55E6C1', '#CAD3C8',
                        '#F3A683', '#778BEB', '#E77F67', '#CF6A87', '#786FA6',
                        '#63CDD7', '#EA8685', '#596275', '#574B90', '#F8A5C2',
                        '#3DC1D3', '#E15F41', '#C44569', '#303952', '#F7D794'
                            ];

                            $barColor = $colors[array_rand($colors)];
                        @endphp

                        <div class="mb-4">

                            <div class="d-flex justify-content-between mb-1">

                                <small style="font-size:14px; font-weight: bold;">
                                    {{ $material['name'] }} @if(!empty($material['db_status']))
        ( {{ ucfirst($material['db_status']) }} )
    @endif
     
                                </small>

                                <!--<small class="text-muted">-->
                                <!--    ₹ {{ number_format($usedAmount) }} {{ $material['attribute'] }}-->
                                <!--    /-->
                                <!--    ₹ {{ number_format($totalAmount) }} {{ $material['attribute'] }}-->
                                <!--</small>-->
                                 <small style="font-size:14px; font-weight: 500;">
                        
                                    ₹ {{ number_format($totalAmount) }}
                                </small>

                            </div>

                            <div class="progress utilization-bar">
                                <div class="progress-bar"
                                     style="width: {{ min(100, $percent) }}%;
                                            background-color: {{ $barColor }};">
                                </div>
                            </div>

                        </div>

                    @empty
                        <div class="text-center text-muted">
                            No utilization data available
                        </div>
                    @endforelse

                </div>
            </div>
        </div>

        <!-- EQUIPMENT ANALYSIS -->
        <div class="col-md-5">
            <div class="card stat-card shadow-sm border-0 h-100">

                <div class="card-header py-2">
                    <h6 class="mb-0">
                        {{ __('EQUIPMENT ANALYSIS') }}
                    </h6>
                </div>

                <div class="card-body material-scroll">

                    <div style="height: 280px;">
                        <canvas id="equipmentChart"></canvas>
                    </div>

                </div>
            </div>
        </div>

    </div>

    </div>

<!-- Work Details Table -->
    <div class="col-md-12 mt-3">
        {{-- ✅ FILTER --}}

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
                <h6 class="mb-0">{{ __('Work Details') }}</h6>
               <form method="GET" action="{{ route('graph.dashboard') }}">

    {{-- ✅ Preserve Material Filter --}}
    <input type="hidden" name="material_category_id" value="{{ request('material_category_id') }}">

    <select name="main_category_id"
            class="form-select form-select-sm"
            onchange="this.form.submit()"
            style="width: 180px;">

        <option value="">{{ __('-- Select Types Of Works --') }}</option>

        @foreach($mainCategories as $category)
            <option value="{{ $category->id }}"
                {{ request('main_category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach

    </select>
</form>
            </div>



            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0" style="font-size: 12px;">
                        <thead class="table-light">
                            <tr>
                                <th style="font-size: 14px;">{{ __('Name of Work') }}</th>
                                <th style="font-size: 14px;">{{ __('Work Done') }}</th>
                                <th style="font-size: 14px;">{{ __('Total Quantity') }}</th>
                                <th style="font-size: 14px;">{{ __('Man Power') }}</th>
                                <th style="font-size: 14px;">{{ __('Material Used') }}</th>
                                <th style="font-size: 14px;">{{ __('Equipment') }}</th>
                                <th style="font-size: 14px;">{{ __('Progress') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailyreport as $nameOfWorkId => $reports)
                            @php
                            $usedMeasurement = 0;
                            foreach ($reports as $report) {
                            foreach ($report->measurements as $m) {
                            $usedMeasurement += (float) ($m->mesurements_value ?? 0);
                            }
                            }
                            $totalMeasurement = $reports[0]->nameOfWork->total_mesurement ?? 0;
                            $statusPercent = $totalMeasurement > 0 ? number_format(($usedMeasurement / $totalMeasurement) * 100, 2) : 0;

                            $manpowerTotals = [];
                            foreach ($reports as $report) {
                            foreach ($report->manpowers as $mp) {
                            $id = $mp->manPower->id ?? null;
                            if ($id) {
                            if (!isset($manpowerTotals[$id])) {
                            $manpowerTotals[$id] = 0;
                            }
                            $manpowerTotals[$id] += $mp->total_person ?? 0;
                            }
                            }
                            }

                            $equipmentTotals = [];
                            foreach ($reports as $report) {
                            foreach ($report->equipments as $eq) {
                            $id = $eq->equipment->id ?? null;
                            if ($id) {
                            if (!isset($equipmentTotals[$id])) {
                            $equipmentTotals[$id] = 0;
                            }
                            $equipmentTotals[$id] += $eq->total_hours ?? 0;
                            }
                            }
                            }

                            $materialCategoryTotals = [];
                            foreach ($reports as $report) {
                            foreach ($report->materials as $mat) {
                            $category = $mat->subCategory->category ?? null;
                            $categoryId = $category->id ?? null;
                            if ($categoryId) {
                            $unit = $mat->subCategory->attribute->name ?? '';
                            $categoryName = $category->name ?? '';
                            if (!isset($materialCategoryTotals[$categoryId])) {
                            $materialCategoryTotals[$categoryId] = [
                            'total' => 0,
                            'unit' => $unit,
                            'name' => $categoryName,
                            ];
                            }
                            $materialCategoryTotals[$categoryId]['total'] += (float) ($mat->used_stock ?? 0);
                            }
                            }
                            }
                            @endphp
                            <tr>
                                <td style="font-size: 14px; font-weight:bold;">{{ $reports[0]->nameOfWork->name ?? '-' }}</td>
                                <td style="font-weight:bold;">{{ $usedMeasurement ?? '-' }} {{ $reports[0]->nameOfWork->mesurementsubAttribute->name ?? '-' }}</td>
                                <td style="font-size: 14px; font-weight:bold;">{{ $totalMeasurement }} {{ $reports[0]->nameOfWork->mesurementsubAttribute->name ?? '-' }}</td>
                                <td>
                                    <ul class="list-unstyled mb-0" style="font-size: 11px; font-weight:bold;">
                                        @foreach($manpowerTotals as $mpId => $totalPerson)
                                        @php
                                        $manpowerName = null;
                                        foreach ($reports as $report) {
                                        foreach ($report->manpowers as $mp) {
                                        if (($mp->manPower->id ?? null) == $mpId) {
                                        $manpowerName = $mp->manPower->name;
                                        break 2;
                                        }
                                        }
                                        }
                                        @endphp
                                        <li>{{ $manpowerName ?? 'N/A' }} ({{ $totalPerson }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0" style="font-size: 11px; font-weight:bold;">
                                        @foreach($materialCategoryTotals as $cat)
                                        <li>{{ $cat['total'] }} {{ $cat['unit'] }} ({{ $cat['name'] }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0" style="font-size: 11px; font-weight:bold;">
                                        @foreach($equipmentTotals as $eqId => $totalHours)
                                        @php
                                        $equipmentName = null;
                                        foreach ($reports as $report) {
                                        foreach ($report->equipments as $eq) {
                                        if (($eq->equipment->id ?? null) == $eqId) {
                                        $equipmentName = $eq->equipment->name;
                                        break 2;
                                        }
                                        }
                                        }
                                        $hours = floor($totalHours);
                                        $minutes = round(($totalHours - $hours) * 60);
                                        @endphp
                                        <li>{{ $equipmentName ?? 'N/A' }} ({{ $hours }}h {{ $minutes }}m)</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress w-100" style="height: 10px;">
                                            <div class="progress-bar
                                    @if($statusPercent >= 80) bg-success
                                    @elseif($statusPercent >= 50) bg-warning
                                    @else bg-danger @endif" role="progressbar" style="width: {{ $statusPercent }}%;" aria-valuenow="{{ $statusPercent }}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="ms-2 small fw-bold" style="font-size: 11px;">{{ $statusPercent }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



</div>

@endsection
