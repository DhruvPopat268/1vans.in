@extends('layouts.admin')
@section('page-title')
    {{__('Manage Working Area')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Working Area')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @can('create working area')
            <a href="#" data-size="lg" data-url="{{ route('wing.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Working Area')}}" data-title="{{__('Create Working Area')}}" class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New Working Area') }}</span>
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
                            <th scope="col" >{{__('Action')}}</th>
                            <th scope="col">{{__('Working Area Name')}}</th>
                            <th scope="col">{{__('Work Section List')}}</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($wing as $wings)
                            <tr>
                                 <td>
                                    @can('edit working area')
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('wing.edit', $wings->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{ __('Edit') }}" data-title="{{ __('Edit Working Area') }}">
                                                <i class="ti ti-pencil text-white"></i>
                                            </a>
                                        </div>
                                                @endcan
                                                 @can('show working area')
                                        <div class="action-btn bg-warning ms-2">
                                            <a href="{{ route('flour.index',$wings->id) }}"
                                               class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               data-bs-whatever="{{__('View Budget Planner')}}" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('Work Section')}}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                        </div>
                                    @endcan

                                </td>
                                <td>{{ $wings->name }}</td>
                                <td>
                                    {{ $wings->flours->pluck('name')->join(', ') }}

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
