@extends('layouts.admin')
@section('page-title')
    {{__('Engineer Attendance')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Engineer Attendance')}}</li>
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
                            <th scope="col">{{__('Engineer Name')}}</th>
                            <th scope="col" >{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
    @forelse($users as $engineer)
        <tr>
            <td>{{ $engineer->name }}</td>
            <td>
                <a href="{{ route('engineer-attendance.show', $engineer->id) }}"
                   class="btn btn-sm btn-primary">
                    View Attendance
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="2" class="text-center text-muted">
                No App Users found.
            </td>
        </tr>
    @endforelse
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
