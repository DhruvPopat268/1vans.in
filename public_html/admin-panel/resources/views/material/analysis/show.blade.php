@extends('layouts.admin')
@section('page-title')
    {{__('Manage Material Incoming')}}
@endsection

@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Material Incoming')}}</li>
@endsection
@section('action-btn')
<div class="float-end">
    @can('create material incoming')
    <a href="#" data-size="lg"
    data-url="{{ route('material.incoming.create', $category->id) }}"
    data-ajax-popup="true"
    data-bs-toggle="tooltip"
    title="{{ __('Create Material Incoming') }}"
    data-title="{{ __('Create Material Incoming') }}"
  class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New Material Incoming') }}</span>
 </a>
 @endcan

</div>
@endsection

@section('content')
    <div class="row">
         <form method="GET" class="row g-3 mb-3">
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
        <a href="{{ route('material-analysis.show', $category->id) }}" class="btn btn-primary">{{ __('Reset') }}</a>
    </div>
</form>
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
                                <th scope="col">{{__('Vendor Name')}}</th>
                                <th scope="col">{{__('Challan Number')}}</th>
                                <th scope="col" >{{__('Bill Number')}}</th>
                                <th scope="col" >{{__('Vehicle Number')}}</th>
                                <th scope="col" >{{__('Description')}}</th>
                                <th scope="col" >{{__('Remark')}}</th>
                                <th scope="col">{{__('Signature')}}</th>
                                <th scope="col" >{{__('Material List')}}</th>
                                <th scope="col">{{__('Created By')}}</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($materialincoming as $report)
    <tr @if($report->issue_status == 'Yes') style="background-color: #f8d7da;" @endif>
     <td>
                                        @can('edit material incoming')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('material.incoming.edit', $report->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{ __('Edit') }}" data-title="{{ __('Edit Work Issue Status') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                            @endcan
                                             @can('show material incoming')
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{ route('material.incoming.show', $report->id) }}"
                                                   class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                   data-bs-whatever="{{__('View Budget Planner')}}" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('View')}}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                            </div>
                                            @endcan
                                             @can('download material incoming')
                                            <a href="{{ route('material.form.downloadPdf', $report->id) }}" class="btn btn-sm btn-secondary" target="_blank">Download PDF</a>
                                            @endcan

                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($report->date)->format('d-m-Y') }}</td>
                                        <td>{{ $report->location ?? '-' }}</td>
                                        <td>{{ $report->vendor_name ?? '-' }}</td>
                                        <td>{{ $report->challan_number ?? '-' }}</td>
                                        <td>{{ $report->bill_number ?? '-' }}</td>
                                        <td>{{ $report->vehicle_number ?? '-' }}</td>
<td>
                                        <span data-bs-toggle="tooltip" title="{{ $report->description }}">
                                            {{ Str::limit($report->description, 20) }}
                                        </span>
                                    </td>
                                        <td>
                                         <span data-bs-toggle="tooltip" title="{{ $report->remark }}">
                                            {{ Str::limit($report->remark, 20) }}
                                        </span>
                                        </td>
                                        <td>
                                            @if($report->signature)
                                                <img src="{{ asset('storage/'.$report->signature) }}" alt="Signature" width="50">
                                            @else
                                                {{ __('No Signature') }}
                                            @endif
                                        </td>
                                        <td>
                                            <ul>
                                            @foreach($report->stocks as $stock)
                                                <li>
                                                    {{ optional($stock->subCategory)->name }} - {{ $stock->stock }} {{ $stock->subCategory->attribute->name }} ({{ $stock->subCategory->category->name }})
                                                </li>
                                            @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ \App\Models\User::find($report->created_by)->name ?? '-' }}</td>
                                       
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
