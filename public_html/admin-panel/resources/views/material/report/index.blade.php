@extends('layouts.admin')
@section('page-title')
    {{__('Manage Material Report')}}
@endsection

@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Material Report')}}</li>
@endsection
@section('action-btn')
<div class="float-end">
    <a href="#" data-size="lg"
    data-url="{{ route('material-reports.create') }}"
    data-ajax-popup="true"
    data-bs-toggle="tooltip"
    title="{{__('Create Report')}}"
    data-title="{{__('Create Report')}}"
   class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New Report') }}</span>
 </a>
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
                                <th scope="col">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($materialreports as $report)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($report->date)->format('d-m-Y') }}</td>
                                        <td>{{ $report->location ?? '-' }}</td>
                                        <td>{{ $report->vendor_name ?? '-' }}</td>
                                        <td>{{ $report->challan_number ?? '-' }}</td>
                                        <td>{{ $report->bill_number ?? '-' }}</td>
                                        <td>{{ $report->vehicle_number ?? '-' }}</td>
                                        <td>{{ $report->description ?? '-' }}</td>
                                        <td>{{ $report->remark ?? '-' }}</td>
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
                                        <td>
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{ route('material-reports.show', $report->id) }}"
                                                   class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                   data-bs-whatever="{{__('View Budget Planner')}}" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('View')}}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                            </div>
                                            <a href="{{ route('material.form.downloadPdf', $report->id) }}" class="btn btn-sm btn-secondary" target="_blank">Download PDF</a>

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
