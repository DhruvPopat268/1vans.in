@extends('layouts.admin')
@section('page-title')
{{__('Graph Dashboard')}}
@endsection
@push('script-page')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
   // Material Used Chart
 //const materialUsedData = @json($home_data['material_used_chart']);
   
//   const ctx = document.getElementById('materialUsedChart').getContext('2d');
//   const materialUsedChart = new Chart(ctx, {
//       type: 'bubble',
//       data: {
//           datasets: materialUsedData.map((d, index) => {
//               return {
//                   label: d.name,
//                   data: [{
//                       x: index + 1,
//                       y: d.used_stock,
//                       r: Math.max(4, Math.min(15, d.used_stock / 3))
//                   }],
//                   backgroundColor: [
//                       '#3aa7dc', '#64a7fa', '#eff3fa', '#3a4454', '#95ceef',
//                       '#b9c4d1', '#C9CBCF', '#F67019', '#00A950', '#FFA07A',
//                       '#8E44AD', '#2ECC71', '#3498DB', '#E74C3C', '#F1C40F',
//                       '#1ABC9C', '#9B59B6', '#E67E22', '#34495E', '#BDC3C7',
//                       '#7F8C8D', '#2C3E50', '#D35400', '#27AE60', '#2980B9',
//                       '#16A085', '#C0392B', '#95A5A6', '#F39C12', '#8E44AD'
//                   ][index % 30],
//                   borderWidth: 1
//               };
//           })
//       },
//       options: {
//           responsive: true,
//           maintainAspectRatio: false,
//           plugins: {
//               tooltip: {
//                   callbacks: {
//                       label: function(context) {
//                           const index = context.datasetIndex;
//                           const category = materialUsedData[index];
//                           const availableStock = category.total_stock - category.used_stock;
//                           return [
//                               `Material: ${category.name}`,
//                               `Used Stock: ${category.used_stock}`,
//                               `Total Stock: ${category.total_stock}`,
//                               `Available Stock: ${availableStock}`
//                           ];
//                       }
//                   }
//               },
//               legend: {
//                   display: false
//               }
//           },
//           scales: {
//               x: {
//                   title: {
//                       display: true,
//                       text: 'Material Index',
//                       font: { size: 11,weight: 'bold' }
//                   },
//                   ticks: {
//                       stepSize: 1,
//                       font: { size: 10 }
//                   }
//               },
//               y: {
//                   beginAtZero: true,
//                   title: {
//                       display: true,
//                       text: 'Used Stock',
//                       font: { size: 11 }
//                   },
//                   ticks: {
//                       font: { size: 10 }
//                   }
//               }
//           }
//       }
//   });


const materialUsedData = @json($home_data['material_used_chart']);

const labels = materialUsedData.map(item => item.name);
const usedStock = materialUsedData.map(item => item.used_stock);
const availableStock = materialUsedData.map(item => item.total_stock - item.used_stock);

const ctx = document.getElementById('materialUsedChart').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Used Stock',
                data: usedStock,
                backgroundColor: '#3aa7dc'
            },
            {
                label: 'Available Stock',
                data: availableStock,
                backgroundColor: '#0d589f'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                callbacks: {
                    afterBody: function(context) {
                        const index = context[0].dataIndex;
                        const total = materialUsedData[index].total_stock;
                        return `Total Stock: ${total}`;
                    }
                }
            },
            legend: {
                position: 'top'
            }
        },
        scales: {
            x: {
                stacked: true,
                title: {
                    display: true,
                    text: 'Materials',
                    font: { size: 12, weight: 'bold' }
                },
                ticks: {
                    maxRotation: 45,
                    minRotation: 45
                }
            },
            y: {
                stacked: true,
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Stock Quantity',
                    font: { size: 12, weight: 'bold' }
                }
            }
        }
    }
});


   // Equipment Chart
   const equipmentData = @json($home_data['equipment_chart'] ?? []);

   const ctxEquip = document.getElementById('equipmentChart').getContext('2d');
   const equipmentChart = new Chart(ctxEquip, {
       type: 'bar',
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
               borderRadius: 8,
               borderSkipped: false,
               barThickness: 20
           }]
       },
       options: {
           responsive: true,
           maintainAspectRatio: false,
           plugins: {
               legend: { 
                   position: 'top',
                   labels: { font: { size: 11 } }
               },
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
                       text: 'Total Amount',
                       font: { size: 11 }
                   },
                   ticks: {
                       callback: function(value) {
                           return value.toLocaleString();
                       },
                       font: { size: 10 }
                   },
                   grid: {
                       color: '#e9ecef'
                   }
               },
               x: {
                   title: {
                       display: true,
                       text: 'Equipment Name',
                       font: { size: 11,weight: 'bold' }
                   },
                   ticks: {
                       font: { size: 10,weight: 'bold' }
                   },
                   grid: {
                       display: false
                       
                   }
               }
           }
       }
   });

   // Manpower Chart
   const manpowerData = @json($home_data['manpower_chart'] ?? []);

   const ctxMan = document.getElementById('manpowerChart').getContext('2d');
   const manpowerChart = new Chart(ctxMan, {
       type: 'bar',
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
               ],
               borderColor: '#ffffff',
               borderWidth: 1,
               borderRadius: 6,
               barThickness: 20
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
                   display: true,
                   labels: { font: { size: 11 } }
               }
           },
           scales: {
               y: {
                   beginAtZero: true,
                   title: {
                       display: true,
                       text: 'Total Amount (₹)',
                       font: { size: 11 }
                   },
                   ticks: {
                       font: { size: 10 }
                   }
               },
               x: {
                   title: {
                       display: true,
                       text: 'Manpower Type',
                       font: { size: 11,weight: 'bold' }
                   },
                   ticks: {
                       font: { size: 10,weight: 'bold' }
                   }
               }
           }
       }
   });

   // Budget Chart
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

               ctx.font = 'bold 18px Arial';
               ctx.fillStyle = '#000';
               ctx.textAlign = 'center';
               ctx.textBaseline = 'middle';
               ctx.fillText(text, width / 2, height / 2 - 8);

               ctx.font = 'normal 12px Arial';
               ctx.fillText(subText, width / 2, height / 2 + 15);

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
           maintainAspectRatio: false,
           plugins: {
               legend: { 
                   position: 'bottom',
                   labels: { font: { size: 11 } }
               },
               tooltip: {
                   callbacks: {
                       label: function(context) {
                           return `${context.label}: ₹${Number(context.raw).toLocaleString()}`;
                       }
                   }
               },
               centerText: {}
           }
       },
       plugins: [centerTextPlugin]
   });

   // Completed Work Chart
   document.addEventListener("DOMContentLoaded", function () {
       const totalCompleted = {{ $home_data['total_completed'] }};
       const totalPending = {{ $home_data['total_pending'] }};
       const totalWork = {{ $home_data['total_works'] }};
       const percentageCompleted = totalWork > 0 ? (totalCompleted / totalWork) * 100 : 0;

       const ctx = document.getElementById('completedWorkChart').getContext('2d');

       const centerPercentagePlugin = {
           id: 'centerPercentage',
           beforeDraw(chart) {
               const { width, height, ctx } = chart;
               ctx.save();
               ctx.font = 'bold 20px Arial';
               ctx.fillStyle = '#000';
               ctx.textAlign = 'center';
               ctx.textBaseline = 'middle';
               ctx.fillText(percentageCompleted.toFixed(1) + '%', width / 2, height / 2 - 8);

               ctx.font = 'normal 13px Arial';
               ctx.fillText('Completed Work', width / 2, height / 2 + 16);

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
               maintainAspectRatio: false,
               plugins: {
                   legend: { 
                       position: 'bottom',
                       labels: { font: { size: 11 } }
                   },
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
           plugins: [centerPercentagePlugin]
       });
   });
</script>
@endpush
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Graph Dashboard')}}</a></li>
@endsection
@section('content')
<div class="row">
   <!-- FIRST ROW: 2 Charts (Budget + Work Progress) -->
   <div class="col-md-6 mb-3">
      <div class="card h-100">
         <div class="card-header py-2">
            <h6 class="mb-0">{{ __('Project Cost vs Amount Spent') }}</h6>
         </div>
         <div class="card-body p-3">
            <div class="row align-items-center">
               <div class="col-md-5">
                  <div style="height: 280px;">
                     <canvas id="budgetChart"></canvas>
                  </div>
               </div>
               <div class="col-md-7">
                  <div class="row g-2">
                     @php
                     $budget = $home_data['budget_chart'] ?? [];
                     $boxes = [
                        ['label' => 'Cost of Materials', 'color' => '#64a7fa', 'value' => $budget['material_amount'] ?? 0],
                        ['label' => 'Cost of Equipment', 'color' => '#3a4454', 'value' => $budget['equipment_amount'] ?? 0],
                        ['label' => 'Cost of Man Power', 'color' => '#b9c4d1', 'value' => $budget['manpower_amount'] ?? 0],
                        ['label' => 'Project Budget', 'color' => '#3aa7dc', 'value' => $budget['budget'] ?? 0],
                        ['label' => 'Total Amount Spent', 'color' => '#0d589f', 'value' => $budget['used'] ?? 0],
                     ];
                     @endphp
                     @foreach ($boxes as $box)
                     <div class="col-6">
                        <div class="p-2 rounded text-white text-center" style="background-color: {{ $box['color'] }};">
                           <small class="d-block fw-bold" style="font-size: 10px;">{{ __($box['label']) }}</small>
                           <strong style="font-size: 12px;">₹{{ number_format($box['value']) }}</strong>
                        </div>
                     </div>
                     @endforeach
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="col-md-6 mb-3">
      <div class="card h-100">
         <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h6 class="mb-0">{{ __('Work Progress') }}</h6>
            <form method="GET" action="{{ route('graph.dashboard') }}" class="d-flex gap-2">
               <select name="main_category_id" class="form-select form-select-sm" style="width: auto; font-size: 11px;">
                  <option value="">{{ __('-- Select Types Of Works --') }}</option>
                  @foreach($mainCategories as $category)
                  <option value="{{ $category->id }}" {{ $mainCategoryId == $category->id ? 'selected' : '' }}>
                     {{ $category->name }}
                  </option>
                  @endforeach
               </select>
               <button type="submit" class="btn btn-primary btn-sm" style="font-size: 11px;">{{ __('Filter') }}</button>
               <a href="{{ route('graph.dashboard') }}" class="btn btn-secondary btn-sm" style="font-size: 11px;">{{ __('Reset') }}</a>
            </form>
         </div>
         <div class="card-body p-3">
            <div class="row align-items-center">
               <div class="col-md-5">
                  <div style="height: 280px;">
                     <canvas id="completedWorkChart"></canvas>
                  </div>
               </div>
               <div class="col-md-7">
                  <div class="row g-2">
                     <div class="col-4">
                        <div class="p-2 rounded text-center" style="background-color:#eef2ff;">
                           <h6 class="mb-0">{{ $home_data['total_works'] }}</h6>
                           <small style="font-size: 10px;">Total Work</small>
                        </div>
                     </div>
                     <div class="col-4">
                        <div class="p-2 rounded text-center" style="background-color:#f0fdf4;">
                           <h6 class="mb-0">{{ $home_data['total_pending'] }}</h6>
                           <small style="font-size: 10px;">Pending</small>
                        </div>
                     </div>
                     <div class="col-4">
                        <div class="p-2 rounded text-center" style="background-color:#e5e7eb;">
                           <h6 class="mb-0">{{ $home_data['total_completed'] }}</h6>
                           <small style="font-size: 10px;">Completed</small>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- SECOND ROW: 3 Charts (Material, Equipment, Manpower) -->
   <div class="col-md-4 mb-3">
      <div class="card h-100">
         <div class="card-header py-2">
            <h6 class="mb-0">{{ __('Material Analysis') }}</h6>
         </div>
         <div class="card-body p-3">
            <div style="height: 240px;">
               <canvas id="materialUsedChart"></canvas>
            </div>
         </div>
      </div>
   </div>

   <div class="col-md-4 mb-3">
      <div class="card h-100">
         <div class="card-header py-2">
            <h6 class="mb-0">{{ __('Equipment And Machinery') }}</h6>
         </div>
         <div class="card-body p-3">
            <div style="height: 240px;">
               <canvas id="equipmentChart"></canvas>
            </div>
         </div>
      </div>
   </div>

   <div class="col-md-4 mb-3">
      <div class="card h-100">
         <div class="card-header py-2">
            <h6 class="mb-0">{{ __('Manpower Availability') }}</h6>
         </div>
         <div class="card-body p-3">
            <div style="height: 240px;">
               <canvas id="manpowerChart"></canvas>
            </div>
         </div>
      </div>
   </div>

   <!-- Work Details Table -->
   <div class="col-12">
      <div class="card">
         <div class="card-header py-2">
            <h6 class="mb-0">{{ __('Work Details') }}</h6>
         </div>
                <div class="card-body table-border-style">
            <div class="table-responsive">
               <table class="table table-sm align-middle mb-0" style="font-size: 12px;">
                  <thead class="table-light">
                     <tr>
                        <th style="font-size: 11px;">{{ __('Name of Work') }}</th>
                        <th style="font-size: 11px;">{{ __('Work Done') }}</th>
                        <th style="font-size: 11px;">{{ __('Total Quantity') }}</th>
                        <th style="font-size: 11px;">{{ __('Man Power') }}</th>
                        <th style="font-size: 11px;">{{ __('Material Used') }}</th>
                        <th style="font-size: 11px;">{{ __('Equipment') }}</th>
                        <th style="font-size: 11px;">{{ __('Progress') }}</th>
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
                        <td class="fw-bold">{{ $reports[0]->nameOfWork->name ?? '-' }}</td>
                        <td>{{ $usedMeasurement ?? '-' }} {{ $reports[0]->nameOfWork->mesurementsubAttribute->name ?? '-' }}</td>
                        <td class="fw-bold">{{ $totalMeasurement }} {{ $reports[0]->nameOfWork->mesurementsubAttribute->name ?? '-' }}</td>
                        <td>
                           <ul class="list-unstyled mb-0" style="font-size: 11px;">
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
                           <ul class="list-unstyled mb-0" style="font-size: 11px;">
                              @foreach($materialCategoryTotals as $cat)
                              <li>{{ $cat['total'] }} {{ $cat['unit'] }} ({{ $cat['name'] }})</li>
                              @endforeach
                           </ul>
                        </td>
                        <td>
                           <ul class="list-unstyled mb-0" style="font-size: 11px;">
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
                                    @else bg-danger @endif" 
                                    role="progressbar" 
                                    style="width: {{ $statusPercent }}%;" 
                                    aria-valuenow="{{ $statusPercent }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
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