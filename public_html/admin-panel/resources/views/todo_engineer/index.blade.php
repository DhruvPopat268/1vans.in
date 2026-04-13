@extends('layouts.admin')

@section('page-title')
    {{__('Manage ToDo List')}}
@endsection

@push('script-page')
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('ToDo List')}}</li>
@endsection

@section('action-btn')
   <div class="float-end">
    @can('create todo list')
        <a href="#" 
           data-size="lg" 
           data-url="{{ route('to-do-list.create') }}" 
           data-ajax-popup="true" 
           data-bs-toggle="tooltip" 
           title="{{__('Create New ToDo List')}}" 
           data-title="{{__('Create ToDo List')}}" 
           class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New ToDo List') }}</span>
        </a>
    @endcan
</div>

@endsection

@section('content')
    <div class="row">
     <div class="row mb-3 mt-3">
            <div class="col-12">
                <!-- Filter Form -->
    <form method="GET" action="{{ route('to-do-list.index') }}" class="row mb-4">
       
        <div class="col-md-3">
            <label>{{ __('Engineers') }}</label>
            <select name="engineer_id" class="form-control">
                <option value="">{{ __('All Engineers') }}</option>
                @foreach($engineers as $id => $name)
                       <option value="{{ $id }}" {{ request('engineer_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                    <a href="{{ route('to-do-list.index') }}" class="btn btn-secondary ms-2">{{ __('Reset') }}</a>
         </div>
     </form>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th scope="col">{{__('Action')}}</th>
                                <th scope="col">{{__('Task Category Name')}}</th>
                                <th scope="col">{{__('Engineer')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($todoeng as $todoengs)
                                <tr class="font-style">
                                    <td class="action">
                                        @can('show todo list')
                                        <div class="action-btn bg-warning ms-2">
                                            <a href="{{ route('todo.task.index',$todoengs->id) }}"
                                               class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('View')}}">
                                                <span class="text-white"><i class="ti ti-eye"></i></span>
                                            </a>
                                        </div>
                                         @endcan
                                    </td>
                                    <td>{{ $todoengs->name }}</td>
                                    <td>{{ $todoengs->engineer->name ?? '-' }}</td>
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
