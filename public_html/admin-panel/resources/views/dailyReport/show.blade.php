@extends('layouts.admin')
@section('page-title')
    {{__('Manage Daily Report View')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Daily Report View')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
    </div>
@endsection

@section('content')
<div class="row">
        <form method="GET" action="{{ route('daily-report.show', $reports->first()->name_of_work_id ?? $id) }}" class="row g-3 mb-3">
    <div class="col-md-3">
        <label for="from_date" class="form-label">{{ __('From Date') }}</label>
        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
    </div>
    <div class="col-md-3">
        <label for="to_date" class="form-label">{{ __('To Date') }}</label>
        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
    </div>
    <div class="col-md-3 align-self-end">
        <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
        <a href="{{ route('daily-report.show', $reports->first()->name_of_work_id ?? $id) }}" class="btn btn-secondary">Reset</a>
    </div>
</form>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                 <th>{{ __('Action') }}</th>
                                  <th>{{ __('Date') }}</th>
                                <th>{{ __('Name Of Work') }}</th>
                                <!--<th>{{ __('Unit Sub Category') }}</th>-->
                                
                                <th>{{ __('Location') }}</th>
                                <th>{{ __('Man Power') }}</th>
                                <th>{{ __('Material Used') }}</th>
                                <th>{{ __('Measurement') }}</th>
                                <th>{{ __('Equipment') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Signature') }}</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr>
                                     <td>
                                          @can('show work reports')
                                         <div class="action-btn bg-warning ms-2">
                                            <a href="{{ route('daily-report.details', $report->id) }}"
                                               class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               data-bs-whatever="{{__('View Budget Planner')}}" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('View')}}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                        </div>
                                                @endcan
                                                 @can('download work reports')
                                                                                    <a href="{{ route('daily-report.pdf', $report->id) }}" class="btn btn-sm btn-secondary" target="_blank">Download PDF</a>
                                                                                            @endcan

                                    </td>
                                     <td>{{ $report->date ?? '-' }}</td>
                                    <td class="fw-bold">{{ $report->nameOfWork->name ?? '-' }}</td>

                                    <!--<td>{{ $report->subCategory->name ?? '-' }} ({{ $report->UnitCategory->name ?? '-' }})</td>-->

                                    

                                    <td>{{ $report->location ?? '-' }}</td>

                                    <td>
    <ul>
        @php
            $manPowerGrouped = [];
            foreach ($report->manpowers as $mp) {
                $id = $mp->man_powers_id;
                $name = $mp->manPower->name ?? 'N/A';

                if (!isset($manPowerGrouped[$id])) {
                    $manPowerGrouped[$id] = [
                        'name' => $name,
                        'total' => 0,
                    ];
                }
                $manPowerGrouped[$id]['total'] += $mp->total_person;
            }
        @endphp

        @foreach($manPowerGrouped as $data)
            <li>{{ $data['name'] }} ({{ $data['total'] }})</li>
        @endforeach
    </ul>
</td>


                                   <td>
    <ul>
        @php
            $materialGrouped = [];
            foreach ($report->materials as $mat) {
                $key = $mat->sub_category_id;
                $name = ($mat->subCategory->attribute->name ?? '') . ' (' . ($mat->subCategory->name ?? 'N/A') . ')';

                if (!isset($materialGrouped[$key])) {
                    $materialGrouped[$key] = [
                        'name' => $name,
                        'total' => 0,
                    ];
                }

                $materialGrouped[$key]['total'] += $mat->used_stock ?? 0;
            }
        @endphp

        @foreach($materialGrouped as $mat)
            <li>{{ $mat['total'] }} {{ $mat['name'] }}</li>
        @endforeach
    </ul>
</td>


                                 <td>
    <ul>
        @php
            $measurementGrouped = [];
            foreach ($report->measurements as $measure) {
                $attributeName = $measure->attribute->name ?? 'N/A';

                if (!isset($measurementGrouped[$attributeName])) {
                    $measurementGrouped[$attributeName] = 0;
                }

                $measurementGrouped[$attributeName] += $measure->mesurements_value ?? 0;
            }
            $subAttrName = $reports[0]->nameOfWork->mesurementsubAttribute->name ?? '-';
        @endphp

        @foreach($measurementGrouped as $name => $total)
            <li>
                @if($total == 0)
                    <span style="color:red; font-weight:600;">Working in Progress</span>
                @else
                    {{ $name }} - {{ $total }} {{ $subAttrName }}
                @endif
            </li>
        @endforeach
    </ul>
</td>



                                    <td>
    <ul>
        @php
            $equipmentGrouped = [];
            foreach ($report->equipments as $eq) {
                $equipmentName = $eq->equipment->name ?? 'N/A';
                if (!isset($equipmentGrouped[$equipmentName])) {
                    $equipmentGrouped[$equipmentName] = 0;
                }
                $equipmentGrouped[$equipmentName] += $eq->total_hours;
            }
        @endphp

        @foreach($equipmentGrouped as $name => $totalHours)
            @php
                $hours = floor($totalHours);
                $minutes = round(($totalHours - $hours) * 60);
            @endphp
            <li>{{ $name }} ({{ $hours }} Hours {{ $minutes }} Minutes)</li>
        @endforeach
    </ul>
</td>


                                     <td>

                                        <span data-bs-toggle="tooltip" title="{{ $report->description }}">
                                            {{ Str::limit($report->description, 20) }}
                                        </span>
                                    </td>

                                    <td>
                                        @if(!empty($report->signature))
                                            <img src="{{ asset('storage/' . $report->signature) }}" alt="Signature" height="40">
                                        @else
                                            {{ __('N/A') }}
                                        @endif
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
<script>
        $(document).ready(function () {
            $('[data-bs-toggle="tooltip"]').tooltip(); // Initialize Bootstrap tooltips
        });
    </script>
@endpush
