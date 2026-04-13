@extends('layouts.admin')
@section('page-title')
{{__('Graph Dashboard')}}
@endsection
@push('script-page')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
   // Then use:
      const materialUsedData = @json($home_data['material_used_chart']);
   
       const ctx = document.getElementById('materialUsedChart').getContext('2d');
       const materialUsedChart = new Chart(ctx, {
       type: 'bubble',
           data: {
           datasets: materialUsedData.map((d, index) => {
               return {
                   label: d.name,
                   data: [{
                       x: index + 1, // Just to separate the points on X-axis
                       y: d.used_stock,
                       r: Math.max(5, Math.min(25, d.used_stock / 2)) // Adjust bubble size
                   }],
                   backgroundColor: [
                     '#3aa7dc', '#64a7fa', '#eff3fa', '#3a4454', '#95ceef',
                       '#b9c4d1', '#C9CBCF', '#F67019', '#00A950', '#FFA07A',
       '#8E44AD', '#2ECC71', '#3498DB', '#E74C3C', '#F1C40F',
       '#1ABC9C', '#9B59B6', '#E67E22', '#34495E', '#BDC3C7',
       '#7F8C8D', '#2C3E50', '#D35400', '#27AE60', '#2980B9',
       '#16A085', '#C0392B', '#95A5A6', '#F39C12', '#8E44AD'
                   ][index % 30],
                   borderWidth: 1
               };
           })
           },
           options: {
                 responsive: true,
           maintainAspectRatio: false,
               plugins: {
                   tooltip: {
                       callbacks: {
                           label: function(context) {
                           const index = context.datasetIndex;
                               const category = materialUsedData[index];
                               const availableStock = category.total_stock - category.used_stock;
                               return [
                               `Material: ${category.name}`,
                                   `Used Stock: ${category.used_stock}`,
                                   `Total Stock: ${category.total_stock}`,
                                    `Available Stock: ${availableStock}`
                               ];
                           }
                       }
               },
               legend: {
                   display: false
               }
           },
           scales: {
               x: {
                   title: {
                       display: true,
                       text: 'Material Index'
                   },
                   ticks: {
                       stepSize: 1
                   }
               },
               y: {
                   beginAtZero: true,
                   title: {
                       display: true,
                       text: 'Used Stock'
                   }
                   }
               }
           }
       });
   
       //
const equipmentData = @json($home_data['equipment_chart'] ?? []);

const ctxEquip = document.getElementById('equipmentChart').getContext('2d');
const equipmentChart = new Chart(ctxEquip, {
    type: 'bar', // column chart
    data: {
        labels: equipmentData.map(e => e.name),
        datasets: [{
            label: 'Total Amount',
            data: equipmentData.map(e => e.total_amount),
            backgroundColor: [
                '#3aa7dc', '#64a7fa', '#eff3fa', '#3a4454', '#95ceef',
                '#b9c4d1', '#0d589f', '#55EFC4', '#FFEAA7', '#636E72',
                '#B2BEC3', '#D63031', '#E67E22', '#FF6F61', '#6AB04C',
                '#4834D4', '#22A6B3', '#BE2EDD', '#130F40', '#535C68',
                '#CAD3C8', '#F8EFBA', '#82589F', '#B33771', '#00CEC9',
                '#F8A5C2', '#F97F51', '#1B9CFC', '#58B19F', '#F3A683'
            ],
            borderRadius: 10, // 🔹 Rounded corners
            borderSkipped: false, // ensures all corners are rounded
            barThickness: 25 // optional, controls width
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const amount = context.parsed.y || 0;
                        return `Total Amount: ${amount.toLocaleString()}`;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Total Amount'
                },
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString();
                    }
                },
                grid: {
                    color: '#e9ecef'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Equipment Name'
                },
                grid: {
                    display: false
                }
            }
        }
    }
});

   
          const manpowerData = @json($home_data['manpower_chart'] ?? []);
   
       const ctxMan = document.getElementById('manpowerChart').getContext('2d');
       const manpowerChart = new Chart(ctxMan, {
       type: 'bar', // Changed to 'bar' for pillar chart
           data: {
               labels: manpowerData.map(m => m.name),
               datasets: [{
                   label: 'Total Amount (Price × Persons)',
                   data: manpowerData.map(m => m.total_amount),
                backgroundColor: [
                   '#3aa7dc', '#64a7fa', '#eff3fa', '#3a4454', '#95ceef',
                       '#b9c4d1', '#E91E63', '#00BCD4', '#8BC34A', '#FF5722',
                   '#9C27B0', '#03A9F4', '#CDDC39', '#FFC107', '#795548',
                   '#607D8B', '#F44336', '#2196F3', '#673AB7', '#009688',
                   '#FFEB3B', '#E64A19', '#3F51B5', '#00E676', '#9E9E9E'
               ], // 25 different colors
               borderColor: '#ffffff',
               borderWidth: 1,
               borderRadius: 8 // Rounded top of pillars
               }]
           },
           options: {
               responsive: true,
           maintainAspectRatio: false,
               plugins: {
                   tooltip: {
                       callbacks: {
                           label: function(context) {
                               const manpower = manpowerData[context.dataIndex];
                               return [
                                   `Price: ₹${manpower.price}`,
                                   `Persons: ${manpower.total_person}`,
                                               `Amount: ₹${manpower.total_amount.toLocaleString()}`
                               ];
                           }
                       }
                   },
                   legend: {
                   display: true
                   }
               },
               scales: {
                   y: {
                       beginAtZero: true,
                       title: {
                           display: true,
                           text: 'Total Amount (₹)'
                       }
                   },
                   x: {
                       title: {
                           display: true,
                           text: 'Manpower Type'
                       }
                   }
               }
           }
       });
   
   
       const budgetData = @json($home_data['budget_chart'] ?? []);
   const ctxBudget = document.getElementById('budgetChart').getContext('2d');
   
   const centerTextPlugin = {
       id: 'centerText',
       beforeDraw: function(chart) {
           if (chart.config.type === 'doughnut') {
               const { width, height, ctx } = chart;
               ctx.restore();
   
               const budget = chart.data.datasets[0].data[0];
               const used = chart.data.datasets[0].data[1];
               const percentage = budget > 0 ? (used / budget) * 100 : 0;
               const text = `${percentage.toFixed(1)}%`;
               const subText = 'Used Amount';
   
               // Draw percentage
               ctx.font = 'bold 25px Arial';
               ctx.fillStyle = '#000';
               ctx.textAlign = 'center';
               ctx.textBaseline = 'middle';
               ctx.fillText(text, width / 2, height / 2 - 10);
   
               // Draw subtext
               ctx.font = 'normal 15px Arial';
               ctx.fillText(subText, width / 2, height / 2 + 25);
   
               ctx.save();
           }
       }
   };
   
   const budgetChart = new Chart(ctxBudget, {
       type: 'doughnut',
       data: {
           labels: ['Project Budget', 'Used Amount'],
           datasets: [{
               label: 'Budget vs Used',
               data: [budgetData.budget, budgetData.used],
   backgroundColor: ['#3aa7dc', '#0d589f'],
               borderWidth: 1
           }]
       },
       options: {
           responsive: true,
           plugins: {
               legend: { position: 'bottom' },
               tooltip: {
                   callbacks: {
                       label: function(context) {
                           return `${context.label}: ₹${Number(context.raw).toLocaleString()}`;
                       }
                   }
               },
               centerText: {} // attach plugin only to this chart
           }
       },
       plugins: [centerTextPlugin] // register plugin only for budgetChart
   });
   
   
   
   
</script>
<script>
   document.addEventListener("DOMContentLoaded", function () {
       const totalCompleted = {{ $home_data['total_completed'] }};
       const totalPending = {{ $home_data['total_pending'] }};
       const totalWork = {{ $home_data['total_works'] }};
       const percentageCompleted = totalWork > 0 ? (totalCompleted / totalWork) * 100 : 0;
       
   
       const ctx = document.getElementById('completedWorkChart').getContext('2d');
   
       // Local plugin for center text
       const centerPercentagePlugin = {
           id: 'centerPercentage',
           beforeDraw(chart) {
               const { width, height, ctx } = chart;
               ctx.save();
               ctx.font = 'bold 25px Arial';
               ctx.fillStyle = '#000';
               ctx.textAlign = 'center';
               ctx.textBaseline = 'middle';
               ctx.fillText(percentageCompleted.toFixed(1) + '%', width / 2, height / 2 - 10);
   
               // Draw subtext
               ctx.font = 'normal 16px Arial';
               ctx.fillText('Completed Work', width / 2, height / 2 + 20);
   
               ctx.restore();
           }
       };
   
       new Chart(ctx, {
           type: 'doughnut',
           data: {
               labels: ['Completed Work', 'Pending Work'],
               datasets: [{
                   data: [totalCompleted, totalPending],
                   backgroundColor: ['#3aa7dc', '#0d589f'],
                   borderWidth: 1
               }]
           },
           options: {
               responsive: true,
               plugins: {
                   legend: { position: 'bottom' },
                   tooltip: {
                       callbacks: {
                           label: function(context) {
                               const value = context.raw;
                               const label = context.label;
                               const percentage = totalWork > 0 ? ((value / totalWork) * 100).toFixed(1) : 0;
                               return `${label}: ${value} (${percentage}%)`;
                           }
                       }
                   }
               }
           },
           plugins: [centerPercentagePlugin] // Attach only to this chart
       });
   });
</script>
@endpush
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Graph Dashboard')}}</a></li>
@endsection
@section('content')
<div class="row">
<div class="col-12">
      <div class="card h-100">
         <div class="card-header">
            <h5>{{ __('Project Budget vs Used') }}</h5>
         </div>
         <div class="card-body">
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
               {{-- Left side: Graph --}}
               <div style="flex: 1 1 60%; max-width: 60%;">
                  <canvas id="budgetChart" style="max-width: 100%; max-height: 300px;"></canvas>
               </div>
               {{-- Right side: stacked boxes --}}
               @php
               $budget = $home_data['budget_chart'] ?? [];
               $rightBoxes = [
               ['label' => 'Material Amount', 'color' => '#64a7fa', 'value' => $budget['material_amount'] ?? 0],
               ['label' => 'Equipment And Machinery Amount', 'color' => '#3a4454', 'value' => $budget['equipment_amount'] ?? 0],
               ['label' => 'Man Power', 'color' => '#b9c4d1', 'value' => $budget['manpower_amount'] ?? 0],
               ];
               $bottomBoxes = [
               ['label' => 'Project Budget', 'color' => '#3aa7dc', 'value' => $budget['budget'] ?? 0],
               ['label' => 'Total Amount', 'color' => '#0d589f', 'value' => $budget['used'] ?? 0],
               ];
               @endphp
               <div style="flex: 1 1 35%; max-width: 35%; display: flex; flex-direction: column; gap: 10px;">
                  @foreach ($rightBoxes as $box)
                  <div style="padding: 8px; border-radius: 6px; color: white; font-weight: 600; background-color: {{ $box['color'] }}; font-size: 14px; text-align: center;">
                     <strong>{{ __($box['label']) }}</strong><br>
                     ₹{{ $box['value'] }}
                  </div>
                  @endforeach
               </div>
            </div>
            {{-- Bottom row with Project Budget and Total Amount --}}
            <div style="display: flex; justify-content: space-between; margin-top: 15px;">
               @foreach ($bottomBoxes as $box)
               <div style="flex: 1; padding: 8px; margin: 0 5px; border-radius: 6px; color: white; font-weight: 600; background-color: {{ $box['color'] }}; font-size: 14px; text-align: center;">
                  <strong>{{ __($box['label']) }}</strong><br>
                  ₹{{ $box['value'] }}
               </div>
               @endforeach
            </div>
         </div>
      </div>
   </div>
    <div class="col-12 mb-4" style="margin-top:25px;">
      <div class="card h-100 mb-0">
         <div class="card-header d-flex justify-content-between align-items-center">
            <h5>{{ __('Work Progress') }}</h5>
            <!-- 🔹 Filter Form -->
            <form method="GET" action="{{ route('graph.dashboard') }}" class="d-flex gap-2">
               <select name="main_category_id" class="form-select">
                  <option value="">{{ __('-- Select Types Of Works --') }}</option>
                  @foreach($mainCategories as $category)
                  <option value="{{ $category->id }}" {{ $mainCategoryId == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                  </option>
                  @endforeach
               </select>
               <!-- Filter & Reset Buttons -->
               <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
               <a href="{{ route('graph.dashboard') }}" class="btn btn-secondary">{{ __('Reset') }}</a>
            </form>
         </div>
         <div class="card-body">
            <!-- Row: Left chart, Right summary boxes -->
            <div class="row align-items-center">
               <!-- Left: Doughnut Chart -->
               <div class="col-md-8 d-flex justify-content-center">
                  <div style="width: 400px; height: 400px;">
                     <!-- Medium size -->
                     <canvas id="completedWorkChart"></canvas>
                  </div>
               </div>
               <!-- Right: Summary boxes -->
               <div class="col-md-4">
                  <div class="row mb-4">
                     <div class="col-12 mb-2">
                        <div class="p-3 rounded text-center" style="background-color:#eef2ff;">
                           <h3 class="mb-1">{{ $home_data['total_works'] }}</h3>
                           <small>Total Work</small>
                        </div>
                     </div>
                     <div class="col-12 mb-2">
                        <div class="p-3 rounded text-center" style="background-color:#f0fdf4;">
                           <h3 class="mb-1">{{ $home_data['total_pending'] }}</h3>
                           <small>Total Pending Work</small>
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="p-3 rounded text-center" style="background-color:#e5e7eb;">
                           <h3 class="mb-1">{{ $home_data['total_completed'] }}</h3>
                           <small>Total Completed Work</small>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- Table for Name of Work & Total Measurement -->
            <div class="table-responsive mt-4">
               <table class="table align-middle">
                  <thead>
                     <tr>
                        <th>{{ __('Name of Work') }}</th>
                        <th>{{ __('Work done quantity') }}</th>
                        <th>{{ __('Total Quantity Of Work') }}</th>
                        <th>{{ __('Man Power') }}</th>
                        <th>{{ __('Material Used') }}</th>
                        <th>{{ __('Equipment') }}</th>
                        <th>{{ __('Progress') }}</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($dailyreport as $nameOfWorkId => $reports)
                     @php
                     // 1. Used measurement (sum of mesurements_value)
                     $usedMeasurement = 0;
                     foreach ($reports as $report) {
                     foreach ($report->measurements as $m) {
                     $usedMeasurement += (float) ($m->mesurements_value ?? 0);
                     }
                     }
                     // 2. Total measurement (from name_of_work table or reports)
                     $totalMeasurement = $reports[0]->nameOfWork->total_mesurement ?? 0;
                     // 3. Calculate status percentage
                     $statusPercent = $totalMeasurement > 0
                     ? number_format(($usedMeasurement / $totalMeasurement) * 100, 2)
                     : 0;
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
                     // Aggregate equipment totals by equipment id, summing total_hours
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
                     // Aggregate materials grouped by MaterialCategory (via subCategory->category)
                     $materialCategoryTotals = [];
                     foreach ($reports as $report) {
                     foreach ($report->materials as $mat) {
                     $category = $mat->subCategory->category ?? null;
                     $categoryId = $category->id ?? null;
                     if ($categoryId) {
                     $unit = $mat->subCategory->attribute->name ?? ''; // Assumes all materials in same category use same unit
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
                        <td class="fw-bold">{{ $reports[0]->nameOfWork->name ?? '-' }}</td>
                        <td>{{ $usedMeasurement ?? '-' }} {{ $reports[0]->nameOfWork->mesurementsubAttribute->name ?? '-' }}</td>
                        <td>{{ $totalMeasurement  }} {{ $reports[0]->nameOfWork->mesurementsubAttribute->name ?? '-' }}</td>
                        <td>
                           <ul>
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
                           <ul>
                              @foreach($materialCategoryTotals as $cat)
                              <li>{{ $cat['total'] }} {{ $cat['unit'] }} ({{ $cat['name'] }})</li>
                              @endforeach
                           </ul>
                        </td>
                        <td>
                           <ul>
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
                              // Format hours and minutes from decimal total hours
                              $hours = floor($totalHours);
                              $minutes = round(($totalHours - $hours) * 60);
                              @endphp
                              <li>{{ $equipmentName ?? 'N/A' }} ({{ $hours }}h {{ $minutes }}m)</li>
                              @endforeach
                           </ul>
                        </td>
                        <td>
                           <div class="d-flex align-items-center">
                              <div class="progress w-100" style="height: 12px;">
                                 <div class="progress-bar
                                    @if($statusPercent >= 80) bg-success
                                    @elseif($statusPercent >= 50) bg-warning
                                    @else bg-danger @endif" role="progressbar" style="width: {{ $statusPercent }}%;" aria-valuenow="{{ $statusPercent }}" aria-valuemin="0" aria-valuemax="100">
                                 </div>
                              </div>
                              <span class="ms-2 small fw-bold">{{ $statusPercent }}%</span>
                           </div>
                        </td>
                     </tr>
                     @empty
                     <tr>
                        <td colspan="2" class="text-center">No data available</td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
<div class="row">
   <!-- 🧱 Material Analysis Chart -->
   <div class="col-md-6 col-sm-12 mb-4">
      <div class="card h-100">
         <div class="card-header">
            <h5>{{ __('Material Analysis') }}</h5>
         </div>
         <div class="card-body">
            <div class="d-flex justify-content-center align-items-center" style="height: 370px;">
               <canvas id="materialUsedChart" style="max-width: 100%; max-height: 100%;"></canvas>
            </div>
         </div>
      </div>
   </div>

   <!-- ⚙️ Equipment And Machinery Chart -->
   <div class="col-md-6 col-sm-12 mb-4">
      <div class="card h-100">
         <div class="card-header">
            <h5>{{ __('Equipment And Machinery') }}</h5>
         </div>
         <div class="card-body">
            <div class="d-flex justify-content-center align-items-center" style="height: 370px;">
               <canvas id="equipmentChart" style="max-width: 100%; max-height: 100%;"></canvas>
            </div>
         </div>
      </div>
   </div>
</div>

   <div class="col-12">
      <div class="card h-100 mb-0">
         <div class="card-header">
            <h5>{{ __('Manpower Availability') }}</h5>
         </div>
         <div class="card-body">
            <canvas id="manpowerChart" style="width: 100%; max-height: 400px;"></canvas>
         </div>
      </div>
   </div>
  
</div>

@endsection