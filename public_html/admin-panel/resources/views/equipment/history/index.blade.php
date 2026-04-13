@extends('layouts.admin')
@section('page-title')
    {{__('Manage Equipments Summary')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Equipments Summary')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th scope="col">{{__('Action')}}</th>
                                <th scope="col">{{__('Equipment Name')}}</th>
                                <th scope="col">{{__('Total Hours')}}</th>
                                <th scope="col">{{__('Rate (Per Hours)')}}</th>
                                <th scope="col" >{{__('Total Amount')}}</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                         <td>
                                         @can('show equipments summary')
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{ route('equipment.history.show', $item->equipment_id) }}"
                                                   class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                   data-bs-whatever="{{__('View Budget Planner')}}" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('View')}}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                            </div>
                                            @endcan
                                        </td>
                                        <td>{{ $item->equipment->name ?? '-' }}</td>
                                        @php
    $hours = floor($item->total_hours);
    $minutes = round(($item->total_hours - $hours) * 60);
@endphp
<td>{{ $hours }} Hours {{ $minutes }} Minutes</td>

                                        <td>{{ number_format($item->rate, 2) }}</td>
                                        <td>{{ number_format($item->total_amount, 2) }}</td>
                                       
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
