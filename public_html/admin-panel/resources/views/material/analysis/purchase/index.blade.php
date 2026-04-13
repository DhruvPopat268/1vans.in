@extends('layouts.admin')
@section('page-title')
    {{__('Manage Material Order')}}
@endsection

@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Material Order')}}</li>
@endsection
@section('action-btn')
<div class="float-end">
     @can('create material order')
    <a href="#" data-size="lg"
    data-url="{{ route('material.purchase.order.create', $category->id) }}"
    data-ajax-popup="true"
    data-bs-toggle="tooltip"
    title="{{ __('Create Purchase Order') }}"
    data-title="{{ __('Create Material Order') }}"
    class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New Material Order') }}</span>
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
        <a href="{{ route('material.purchase.order.index', $category->id) }}" class="btn btn-primary">{{ __('Reset') }}</a>
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
                                <th scope="col" >{{__('Description')}}</th>
                                <th scope="col" >{{__('Status')}}</th>
                                <th scope="col">{{__('Signature')}}</th>
                                <th scope="col" >{{__('Material List')}}</th>
                                <th scope="col">{{__('Created By')}}</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($materialpurchase as $purchase)
                                                                       @php
    $status = $purchase->status ?? '-';
    $rowBgColor = match ($status) {
        'Pending' => 'background-color: #f8d7da;',     // light red
        'Processing' => 'background-color: #fff3cd;',  // light orange
        'Completed' => 'background-color: #d4edda;',   // light green
        default => '',
    };
@endphp
    <tr style="{{ $rowBgColor }}">
        <td>
                                             @if($purchase->status !== 'Completed')
                                              @can('edit material order')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('material.purchase.order.edit', $purchase->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{ __('Edit') }}" data-title="{{ __('Edit Status') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                            @endcan
                                            @endif
                                            @can('show material order')
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{ route('material.purchase.order.show', $purchase->id) }}"
                                                   class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                   data-bs-whatever="{{__('View Budget Planner')}}" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('View')}}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                            </div>
                                            @endcan
                                            @can('download material order')
                                            <a href="{{ route('material.purchase.order.downloadPdf', $purchase->id) }}" class="btn btn-sm btn-secondary" target="_blank">Download PDF</a>
                                            @endcan

                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($purchase->date)->format('d-m-Y') }}</td>
                                        <td>{{ $purchase->location ?? '-' }}</td>
                                        <td>{{ $purchase->vendor_name ?? '-' }}</td>
                                        <td>
                                         <span data-bs-toggle="tooltip" title="{{ $purchase->description  ?? '-'  }}">
                                            {{ Str::limit($purchase->description, 20) }}
                                        </span>
                                        </td>


                                        <td>{{ $status }}</td>

                                        <td>
                                            @if($purchase->signature)
                                                <img src="{{ asset('storage/'.$purchase->signature) }}" alt="Signature" width="50">
                                            @else
                                                {{ __('No Signature') }}
                                            @endif
                                        </td>
                                        <td>
                                            <ul>
                                            @foreach($purchase->stocks as $stock)
                                                <li>
                                                    {{ optional($stock->subCategory)->name }} - {{ $stock->stock }} {{ $stock->subCategory->attribute->name }} ({{ $stock->subCategory->category->name }})
                                                </li>
                                            @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ \App\Models\User::find($purchase->created_by)->name ?? '-' }}</td>
                                        
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
