@extends('layouts.admin')
@section('page-title')
    {{__('Manage Sub Category Of Work')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Sub Category Of Work')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @can('create working agency')
            <a href="#" data-size="lg" data-url="{{ route('unit.subcategory.create', $id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Sub Category Of Work')}}" data-title="{{__('Create Sub Category Of Work')}}" class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New Sub Category Of Work') }}</span>
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
                            <th scope="col">{{__('Sub Category Of Work Name')}}</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($unit_sub_category as $sub_category)
                                <tr>
                                     <td>
                                          @can('edit working agency')
                                         <div class="action-btn bg-info ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('unit.subcategory.edit', $sub_category->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{ __('Edit') }}" data-title="{{ __('Edit Sub Category Of Work') }}">
                                                <i class="ti ti-pencil text-white"></i>
                                            </a>
                                        </div>
                                                @endcan
                                    </td>
                                    <td>{{ $sub_category->name }}</td>
                                   
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
