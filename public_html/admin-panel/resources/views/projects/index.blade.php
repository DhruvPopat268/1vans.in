@extends('layouts.admin')
@section('page-title')
    {{__('Manage Projects')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Projects')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @can('create project')
            <a href="#" data-size="lg" data-url="{{ route('projects.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Project')}}" data-title="{{__('Create Project')}}" class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New Project') }}</span>
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
                                <th scope="col">{{__('Project')}}</th>
                                <th scope="col">{{__('PDF Logo')}}</th>
                                 <th scope="col">{{__('Company Name')}}</th>
                                <th scope="col">{{__('Project Number')}}</th>
                                <th scope="col">{{__('Status')}}</th>
                                <th scope="col">{{__('Site Address')}}</th>
                                <th scope="col">{{__('Client')}}</th>
                                <th scope="col">{{__('Start Date')}}</th>
                                <th scope="col">{{__('End Date')}}</th>
                                

                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($projects as $project)

                                <tr class="font-style">
                                    <td class="action ">

                                        @can('edit project')
                                            <div class="action-btn me-2">
                                                <a href="#" class="mx-3 btn btn-sm align-items-center bg-info" data-url="{{ route('projects.edit',$project->id) }}" data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Edit Project')}}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a></div>
                                        @endcan
                                        <!--@can('delete project')-->
                                        <!--    <div class="action-btn ">-->
                                        <!--        {!! Form::open(['method' => 'DELETE', 'route' => ['projects.destroy', $project->id]]) !!}-->
                                        <!--        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para bg-danger" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>-->
                                        <!--        {!! Form::close() !!}-->
                                        <!--    </div>-->
                                        <!--@endcan-->
                                    </td>
                                    <td> <div class="d-flex align-items-center">
                                            <img {{$project->img_image}} class="wid-40 rounded border-2 border border-primary me-3">
                                            <p class="mb-0"> {{ $project->project_name}}</p>
                                        </div>
                                    </td>
                                    <td>
    @if($project->pdf_logo)
        <img src="{{ asset('storage/uploads/pdf_logo/' . $project->pdf_logo) }}" alt="PDF Logo" class="wid-40 rounded">
    @else
        <span>-</span>
    @endif
</td>
<td>{{ $project->company_name}}</td>
                                    <td>{{ $project->project_number}}</td>

                                    <td>
                                        @php
                                            $statusLabels = [
                                                'in_progress' => 'In Progress',
                                                'complete' => 'Complete',
                                                'canceled' => 'Canceled',
                                                'on_hold' => 'On Hold',
                                            ];
                                        @endphp

                                        {{ $statusLabels[$project->status] ?? ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </td>
                                                                        <td>{{ $project->site_address}}</td>
                                                                        <td>{{ $project->client ? $project->client->name : '-' }}</td>
                                                                        <td>{{ $project->start_date}}</td>
                                    <td>{{ $project->end_date}}</td>

                                    
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="EngineerModal" tabindex="-1" aria-labelledby="EngineerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EngineerModalLabel">{{ __('Add Engineer') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Would you like to add a Engineer now?') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('No') }}</button>
                    <button type="button" class="btn btn-primary" onclick="handleEngineerChoice('yes')">{{ __('Yes') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')

<script>
    document.addEventListener('DOMContentLoaded', function() {
                @if(session('showEngineerModal'))
                var EngineerModal = new bootstrap.Modal(document.getElementById('EngineerModal'), {});
                EngineerModal.show();
                @endif
            });

            function handleEngineerChoice(choice) {
                if (choice === 'yes') {
                    window.location.href = "{{ route('users.index') }}"; // Redirect to the Engineer index page
                }
            }
</script>
@endpush
