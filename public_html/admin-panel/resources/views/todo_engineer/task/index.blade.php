@extends('layouts.admin')

@section('page-title')
    {{__('Manage ToDo Task')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('ToDo Task')}}</li>
@endsection

@section('action-btn')
    <div class="float-end">
        @can('create todo list')
            <a href="#" data-size="lg" data-url="{{ route('todo.task.create', $id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New ToDo Task')}}" data-title="{{__('Create ToDo Task')}}" class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New ToDo Task') }}</span>
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
                                <th>{{__('Action')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Task Title')}}</th>
                                <th>{{__('Due Date')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Created User')}}</th>
                                <th>{{__('Description')}}</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($todoengtask as $task)
                                <tr>
                                    <td>
                                        @can('show todo list')
                                        <div class="action-btn bg-warning ms-2">
                                            <a href="{{ route('todo.task.show',$task->id) }}"
                                               class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               data-bs-whatever="{{__('View Budget Planner')}}" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('View')}}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                        </div>
                                         @endcan
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($task->date)->format('d-m-Y') }}</td>
                                    <td>{{ $task->task_title }}</td>
                                    <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d-m-Y') : '-' }}</td>
                                   <td>
    @if ($task->status == 'Pending')
        <span class="badge bg-warning">{{ $task->status }}</span>
    @elseif ($task->status == 'Completed')
        <span class="badge bg-success">{{ $task->status }}</span>
    @else
        <span class="badge bg-secondary">{{ $task->status }}</span>
    @endif
</td>
 <td>{{ $task->created_user }}</td>
  <td>
                                         <span data-bs-toggle="tooltip" title="{{ $task->description }}">
                                            {{ Str::limit($task->description, 20) }}
                                        </span>
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
<script>
        $(document).ready(function () {
            $('[data-bs-toggle="tooltip"]').tooltip(); // Initialize Bootstrap tooltips
        });
    </script>
@endpush

