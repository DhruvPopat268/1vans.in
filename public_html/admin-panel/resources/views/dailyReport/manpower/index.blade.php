@extends('layouts.admin')
@section('page-title')
    {{__('Manage Man Power')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Man Power')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @can('create man power')
            <a href="#" data-size="lg" data-url="{{ route('man-power.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Man Power')}}" data-title="{{__('Create Man Power')}}" class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New Man Power') }}</span>
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
                            <th scope="col">{{__('Name')}}</th>
                            <th scope="col">{{__('Price')}}</th>
                            <th scope="col" >{{__('Total Person')}}</th>
                            <th scope="col" >{{__('Total Amount')}}</th>
                            <th scope="col">{{__('Status')}}</th>

                        </tr>
                        </thead>
                        <tbody>
                            @foreach($manpower as $man)
                                @php
                                 $price = $man->price ?? 0;
                                 $person = $man->total_person ?? 0;
                                 $total = $price * $person;
                             @endphp
                                <tr>
                                    <td>
                                  {{-- Edit Status Button (Always Show) --}}
                                  @can('edit man power')
        <div class="action-btn bg-info ms-2">
            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" 
               data-url="{{ route('man-power.edit', $man->id) }}" 
               data-ajax-popup="true" 
               data-size="md" 
               data-bs-toggle="tooltip" 
               title="{{ __('Edit') }}" 
               data-title="{{ __('Edit Status') }}">
                <i class="ti ti-pencil text-white"></i>
            </a>
        </div>

        {{-- Show Edit Data Button only if price OR total_person is 0/null --}}
        @if(empty($man->price) || $man->price == 0 || empty($man->total_person) || $man->total_person == 0)
            <div class="action-btn bg-danger ms-2">
                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" 
                   data-url="{{ route('manpower.data.edit', $man->id) }}" 
                   data-ajax-popup="true" 
                   data-size="md" 
                   data-bs-toggle="tooltip" 
                   title="{{ __('Edit Data') }}" 
                   data-title="{{ __('Edit Data') }}">
                    <i class="ti ti-alert-circle text-white"></i>
                </a>
            </div>
        @endif
        @endcan
                                </td>
                                    <td>{{ $man->name ?? "-" }}</td>
                                    <td>{{ $man->price ?? "0" }}</td>
                                    <td>{{ $man->total_person ?? "0" }}
                                    </td>
                                    <td>{{ number_format($total, 2) }}</td>
                                     <td>{{ $man->status ?? "Null" }}</td>
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
