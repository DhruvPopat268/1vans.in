@extends('layouts.admin')
@section('page-title')
    {{__('Manage Site Report')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Site Report')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">

    </div>
@endsection

@section('content')
<div class="row">
      <form method="GET" action="{{ route('site-report.index') }}" class="row g-3 mb-3">
    <div class="col-md-3">
        <label for="from_date" class="form-label">{{ __('From Date') }}</label>
        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
    </div>
    <div class="col-md-3">
        <label for="to_date" class="form-label">{{ __('To Date') }}</label>
        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
    </div>
    <div class="col-md-3 align-self-end">
        <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
        <a href="{{ route('site-report.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                        <tr>
                             <th scope="col" >{{__('Action')}}</th>
                            <th scope="col">{{__('Date')}}</th>
                            <th scope="col">{{__('Name Of Work')}}</th>
                            <th scope="col">{{__('Work Description')}}</th>
                            <th scope="col">{{__('Work Address')}}</th>
                           <th scope="col">{{__('Created By')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($sitereport as $sitereports)
                            <tr>
                                 <td>


                                        <div class="action-btn bg-warning ms-2">
                                            <a href="{{ route('site-report.show',$sitereports->id) }}"
                                               class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               data-bs-whatever="{{__('View Budget Planner')}}" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('View')}}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                        </div>

                                            <a href="{{ route('site.report.downloadPdf', $sitereports->id) }}" class="btn btn-sm btn-secondary" target="_blank">Download PDF</a>




                                </td>
                                <td>{{ \Carbon\Carbon::parse($sitereports->created_at)->format('d-m-Y') }}</td>
                                <td>{{ $sitereports->name_of_work }}</td>
                                <td>
                                    <span data-bs-toggle="tooltip" title="{{ $sitereports->work_description }}">
                                            {{ Str::limit($sitereports->work_description, 100) }}
                                    </span>
                                </td>
                                <td>
                                    <span data-bs-toggle="tooltip" title="{{ $sitereports->work_address }}">
                                            {{ Str::limit($sitereports->work_address, 100) }}
                                    </span>
                                </td>
                                 <td>{{ $sitereports->user->name ?? '-' }}</td>

                               
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
