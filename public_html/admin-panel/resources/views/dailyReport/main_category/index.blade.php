@extends('layouts.admin')
@section('page-title')
    {{__('Manage Types Of Works')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Types Of Works')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @can('create types of work')
            <a href="#" data-size="lg" data-url="{{ route('main-category.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Types Of Work')}}" data-title="{{__('Create Types Of Work')}}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
                <span>{{ __('Create Types Of Work') }}</span>
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
                            <th scope="col">{{__('Name')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($maincategory as $category)
                                <tr>
                                <td>
                                        @can('edit types of work')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('main-category.edit', $category->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{ __('Edit') }}" data-title="{{ __('Edit Types Of Work') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        @endcan

                                    </td>
                                    <td>{{ $category->name }}</td>
                                    
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
