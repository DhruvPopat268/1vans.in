@extends('layouts.admin')
@section('page-title')
    {{__('Manage Material Units')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Material Units')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @can('create material units')
            <a href="#" data-size="lg" data-url="{{ route('attribute.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Material Unit')}}" data-title="{{__('Create Material Unit')}}" class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New Material Unit') }}</span>
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
                            <th scope="col">{{__('Unit Names')}}</th>
                           

                        </tr>
                        </thead>
                        <tbody>
                            @foreach($attribute as $attr)
                                <tr>
                                    <td>
                                        @can('edit material units')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('attribute.edit', $attr->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{ __('Edit') }}" data-title="{{ __('Edit Material Unit') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        @endcan

                                    </td>
                                    <td>{{ $attr->name }}</td>
                                    
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
