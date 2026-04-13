@extends('layouts.admin')
@section('page-title')
    {{__('Manage Work Reports')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Work Reports')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
    </div>
@endsection

@section('content')
<div class="row">
    <div class="row mb-3 mt-3">
            <div class="col-12">
                <!-- Filter Form -->
 <form method="GET" action="{{ route('daily-report.index') }}" class="row mb-4">
        <div class="col-md-3">
            <label>{{ __('Types Of Works') }}</label>
           <select name="main_category_id" class="form-control">
               
                    <option value="">{{ __('Select Types Of Works') }}</option>
                    @foreach($mainCategories as $category)
                        <option value="{{ $category->id }}" {{ $mainCategoryId == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
        </div>
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                    <a href="{{ route('daily-report.index') }}" class="btn btn-secondary ms-2">{{ __('Reset') }}</a>
         </div>
</form>
            </div>
        </div>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>{{ __('Action') }}</th>
                                <th>{{ __('Types Of Works') }}</th>
                                <th>{{ __('Name Of Work') }}</th>
                                                                <th>{{ __('Types Of Measurements') }}</th>
                                <th>{{ __('Man Power') }}</th>
                                <th>{{ __('Material Used') }}</th>
                                <th>{{ __('Equipment') }}</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyreport as $nameOfWorkId => $reports)
    @php
        // Aggregate manpower totals by manPower id
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

// Sum measurement values for the current name_of_work
$measurementTotal = 0;
foreach ($reports as $report) {
    foreach ($report->measurements as $m) {
        $measurementTotal += (float) ($m->mesurements_value ?? 0);
    }
}


    @endphp


    <tr>
         <td>

          @if(isset($reports[0]->nameOfWork) && isset($reports[0]->nameOfWork->id))
           @can('show work reports')
    <div class="action-btn bg-warning ms-2">
        <a href="{{ route('daily-report.show', $reports[0]->nameOfWork->id) }}"
           class="mx-3 btn btn-sm d-inline-flex align-items-center"
           data-bs-whatever="{{__('View Budget Planner')}}" data-bs-toggle="tooltip"
           data-bs-original-title="{{__('View')}}">
            <span class="text-white"><i class="ti ti-eye"></i></span>
        </a>
    </div>
            @endcan
@else
    <span class="text-danger">{{ __('Invalid Work') }}</span>
@endif

        </td>
                <td class="fw-bold">{{ $reports[0]->nameOfWork->mainCategory->name ?? '-' }}</td>
        <td class="fw-bold">{{ $reports[0]->nameOfWork->name ?? '-' }}</td>
                <td> {{ $reports[0]->nameOfWork->mesurementattribute->name ?? '-' }} - {{ $measurementTotal ?? '-' }} {{ $reports[0]->nameOfWork->mesurementsubAttribute->name ?? '-' }}</td>


        <td>
           <ul> @foreach($manpowerTotals as $mpId => $totalPerson)
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
            @endforeach</ul>
        </td>

<td>
   <ul>  @foreach($materialCategoryTotals as $cat)
      <li>{{ $cat['total'] }} {{ $cat['unit'] }} ({{ $cat['name'] }})</li>
    @endforeach</ul>
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

       
    </tr>
@endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
@endpush
