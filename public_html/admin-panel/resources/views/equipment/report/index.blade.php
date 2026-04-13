@extends('layouts.admin')
@section('page-title')
    {{__('Manage Equipments Reports')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Equipments Reports')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
@can('create equipments reports')
        <a href="#" data-size="lg"
        data-url="{{ route('equipment.report.create') }}"
        data-ajax-popup="true"
        data-bs-toggle="tooltip"
        title="{{__('Create Equipments Report')}}"
        data-title="{{__('Create Equipments Report')}}"
       class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New Equipments Report') }}</span>
     </a>
    @endcan
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
                                <th scope="col">{{__('Date')}}</th>
                                <th scope="col">{{__('Location')}}</th>
                                <th scope="col" >{{__('Description')}}</th>
                                <th scope="col" >{{__('Equipment List')}}</th>
                                <th scope="col" >{{__('Signature')}}</th>
                                <th scope="col" >{{__('Created By')}}</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($forms as $form)
                                    <tr>
                                        <td>
                                            @can('show equipments reports')

                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{ route('equipment.report.show', $form->id) }}"
                                                   class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                   data-bs-whatever="{{__('View Budget Planner')}}" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('View')}}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                            </div>
                                            @endcan
                                            @can('download equipments reports')

                                            <a href="{{ route('equipment.form.downloadPdf', $form->id) }}" class="btn btn-sm btn-secondary" target="_blank">Download PDF</a>
                                            @endcan

                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($form->created_at)->format('Y-m-d') }}</td>
                                        <td>{{ $form->location }}</td>
                                        <td>
                                         <span data-bs-toggle="tooltip" title="{{ $form->description }}">
                                            {{ Str::limit($form->description, 20) }}
                                        </span>
                                        </td>
                                        <td>
                                            <ul>
                                                @foreach($form->items as $item)
                                                @php
    $hours = floor($item->total_hours);
    $minutes = round(($item->total_hours - $hours) * 60);
@endphp
                                                    <li>{{ $item->equipment->name ?? 'N/A' }} - {{ $hours }} Hours {{ $minutes }} Minutes</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            @if($form->signature)
                                                <img src="{{ asset('storage/' . $form->signature) }}" alt="Signature" width="50">
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $form->user->name ?? 'N/A' }}</td>
                                        
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
