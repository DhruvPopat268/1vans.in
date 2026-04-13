@extends('layouts.admin')
@section('page-title')
    {{__('Manage Autocad Files')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Autocad Files')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @can('create autocad files')
           <a href="#" 
           data-size="lg" 
           data-url="{{ route('auto-desk.create') }}" 
           data-ajax-popup="true" 
           data-bs-toggle="tooltip" 
           title="{{__('Create New Autocad Files Category')}}" 
           data-title="{{__('Create Autocad Files Category')}}" 
           class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New Autocad Files Category') }}</span>
        </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row min-750" id="project_view"></div>
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
                            @foreach ($auto_dex as $auto_dexs)

                               <tr class="font-style">
                               <td class="action">
                                    @can('show autocad files')
                                        <div class="action-btn bg-warning ms-2">
                                           
                                            <a href="{{ route('auto-desk.show',$auto_dexs->id) }}"
                                               class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('View')}}">
                                                <span class="text-white"><i class="ti ti-eye"></i></span>
                                            </a>
                                            
                                        </div>
                                        @can('create autocad files')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('auto-desk.edit', $auto_dexs->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{ __('Edit') }}" data-title="{{ __('Edit Auto Dex') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        @endcan
                                        @endcan
                                    </td>
                               <td>{{ $auto_dexs->name }}</td>
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
