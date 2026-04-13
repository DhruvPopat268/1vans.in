@extends('layouts.admin')
@section('page-title')
    {{__('Manage Types Of Equipment')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Types Of Equipment')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @can('create types of equipment')
            <a href="#" data-size="lg" data-url="{{ route('equipment.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Equipment')}}" data-title="{{__('Create Types Of Equipment')}}" class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New Equipment') }}</span>
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
                                <th scope="col">{{__('Types Of Equipment Name')}}</th>
                                <th scope="col">{{__('Rate (Per Hours)')}}</th>
                                

                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($equipment as $equipments)

                                <tr class="font-style">
                                    <td class="action ">

                                         @can('edit types of equipment')
                                            <div class="action-btn me-2">
                                                <a href="#" class="mx-3 btn btn-sm align-items-center bg-info" data-url="{{ route('equipment.edit',$equipments->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Edit Types Of Equipment')}}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a></div>
                                        @endcan
                                       {{--  @can('delete project')
                                            <div class="action-btn ">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['projects.destroy', $project->id]]) !!}
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para bg-danger" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>
                                                {!! Form::close() !!}
                                            </div>
                                        @endcan  --}}
                                    </td>
                                                                        <td>{{ $equipments->name}}</td>
                                                                        <td>{{ $equipments->rate}}</td>
                                    
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
