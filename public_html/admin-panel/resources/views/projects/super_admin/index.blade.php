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
       
    </div>
@endsection

@section('content')
    <div class="row min-750" id="project_view"></div>
    <div class="row">
   <div class="row mb-3">
    <div class="col-md-6">
        <form method="GET" action="{{ route('project.superadmin.index') }}" class="d-flex">
            <select name="created_by" class="form-control me-2">
                <option value="">{{ __('-- Filter by Created By --') }}</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('created_by') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary me-2">{{ __('Filter') }}</button>

            @if(request('created_by'))
                <a href="{{ route('project.superadmin.index') }}" class="btn btn-secondary">
                    {{ __('Reset') }}
                </a>
            @endif
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
                                <th scope="col" >{{__('Action')}}</th>
                                <th scope="col">{{__('Project')}}</th>
                                <th scope="col">{{__('PDF Logo')}}</th>
                                 <th scope="col">{{__('Company Name')}}</th>
                                <th scope="col">{{__('Project Number')}}</th>
                                <th scope="col">{{__('Status')}}</th>
                                <th scope="col">{{__('Site Address')}}</th>
                                <th scope="col">{{__('Client')}}</th>
                                <th scope="col">{{__('Client Email')}}</th>
                                <th scope="col">{{__('Start Date')}}</th>
                                <th scope="col">{{__('End Date')}}</th>
                                <th scope="col">{{__('Created By')}}</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($projects as $project)

                                <tr class="font-style">
                                    <td class="action ">
                                      @if(auth()->user()->hasRole('super admin'))
    <div class="action-btn">
        <a href="javascript:void(0);" 
           class="mx-3 btn btn-sm align-items-center bg-danger text-white"
           data-project-id="{{ $project->id }}"
           onclick="sendOtp(this)">
            <i class="ti ti-trash"></i>
        </a>
    </div>
@endif

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
                                                                        <td>{{ $project->client ? $project->client->email : '-' }}</td>
                                                                        <td>{{ $project->start_date}}</td>
                                    <td>{{ $project->end_date}}</td>
                                     <td>{{ $project->user->name}}</td>

                                    
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('OTP Verification') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('We’ve sent a verification code to the client’s email. Enter it below to confirm project deletion.') }}</p>
                <input type="hidden" id="otp_project_id">
                <input type="text" id="otp_code" class="form-control" placeholder="Enter OTP">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button class="btn btn-danger" onclick="verifyOtp()">{{ __('Verify & Delete') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- Fullscreen Loader -->
<div id="loader" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(194, 194, 194, 0.8); z-index: 9999;">
    <div class="loader-content" style="background: rgba(255, 255, 255, 0.8); box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); border-radius: 10px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px;">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>



@endsection

@push('script-page')
<script>
    function sendOtp(el) {
        let projectId = $(el).data('project-id');

    // Show loader
    $('#loader').show();

        $.post("{{ route('projects.sendOtp') }}", {
            _token: "{{ csrf_token() }}",
            project_id: projectId
        }, function(response) {
        // Hide loader
        $('#loader').hide();

            if (response.success) {
                $('#otp_project_id').val(projectId);
                var otpModal = new bootstrap.Modal(document.getElementById('otpModal'));
                otpModal.show();
            } else {
                alert(response.message);
            }
    }).fail(function() {
        $('#loader').hide(); // hide loader if request fails
        alert("Something went wrong. Please try again.");
        });
    }

    function verifyOtp() {
        let projectId = $('#otp_project_id').val();
        let otp = $('#otp_code').val();

    // Show loader
    $('#loader').show();

        $.post("{{ route('projects.verifyOtp') }}", {
            _token: "{{ csrf_token() }}",
            project_id: projectId,
            otp: otp
        }, function(response) {
        // Hide loader
        $('#loader').hide();

            if (response.success) {
                window.location.reload();
            } else {
                alert(response.message);
            }
    }).fail(function() {
        $('#loader').hide(); // hide loader on error
        alert("Something went wrong. Please try again.");
        });
    }

</script>
@endpush

