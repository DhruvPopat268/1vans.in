@extends('layouts.admin')
@section('page-title')
    {{__('Manage Types Of Measurements')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Types Of Measurements')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @can('create types of measurements')
            <a href="#" data-size="lg" data-url="{{ route('mesurement-attribute.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Measurement')}}" data-title="{{__('Create Types Of Measurement')}}" class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New Measurement') }}</span>
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
                            <th scope="col">{{__('Types Of Measurements')}}</th>
                                                                                    <th scope="col">{{__('Unit')}}</th>

                           
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($attribute as $attributes)
                            <tr>
                                 <td>
                                    @can('edit types of measurements')
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('mesurement-attribute.edit', $attributes->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{ __('Edit') }}" data-title="{{ __('Edit Types Of Measurement') }}">
                                                <i class="ti ti-pencil text-white"></i>
                                            </a>
                                        </div>
                                            @endcan
                                             @can('show types of measurements')
                                        
                                        <div class="action-btn bg-warning ms-2">
                                            <a href="{{ route('mesurement.subattribute.index',$attributes->id) }}"
                                               class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               data-bs-whatever="{{__('View Budget Planner')}}" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('View')}}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                        </div>



                                    @endcan

                                </td>
                                <td>{{ $attributes->name }}</td>
<td>
                                    {{ $attributes->subattribute->pluck('name')->join(', ') }}

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
