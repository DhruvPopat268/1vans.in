@extends('layouts.admin')
@section('page-title')
    {{__('Manage Work Issue')}}
@endsection

@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Work Issue')}}</li>
@endsection
@section('action-btn')
<div class="float-end">

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
                                <th scope="col">{{__('Date')}}</th>
                                <th scope="col" >{{__('Name Of Work')}}</th>
                                <th scope="col">{{__('Location')}}</th>
                                 <th scope="col" >{{__('Issue')}}</th>
                                <th scope="col" >{{__('Description')}}</th>
                              
                                <th scope="col" >{{__('Status')}}</th>
                                <th scope="col">{{__('Created By')}}</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($workissue as $issue)
                                    <tr>
                                         <td>
                                            @if($issue->status !== 'Completed')
                                            @can('edit work issue')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('work-issue.edit', $issue->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{ __('Edit') }}" data-title="{{ __('Edit Work Issue Status') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                             @endcan
                                            @endif
                                             @can('show work issue')
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{ route('work-issue.show', $issue->id) }}"
                                                   class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                   data-bs-whatever="{{__('View Budget Planner')}}" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('View')}}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                            </div>
                                             @endcan
                                             @can('download work issue')
                                                                                        <a href="{{ route('work-issue-data.pdf', $issue->id) }}" class="btn btn-sm btn-secondary" target="_blank">Download PDF</a>

                                             @endcan
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($issue->date)->format('Y-m-d') }}</td>
                                         <td>{{ $issue->name_of_work }}</td>
                                        <td>{{ $issue->location }}</td>
                                        <td>{{ $issue->issue }}</td>
                                        <td>
                                         <span data-bs-toggle="tooltip" title="{{ $issue->description }}">
                                            {{ Str::limit($issue->description, 20) }}
                                        </span>
                                        </td>
                                        
                                        <td>
                                            {{ $issue->status }}
                                        </td>
                                        <td>{{ \App\Models\User::find($issue->created_by)->name ?? 'N/A' }}</td>
                                       
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
