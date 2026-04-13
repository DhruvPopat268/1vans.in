@extends('layouts.admin')

@section('page-title')
    {{__('Manage ToDo Task Attachments')}}
@endsection

@push('script-page')
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('ToDo Task Attachments') }}</li>
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
                                    <th scope="col">{{ __('Document Name') }}</th>
                                    <th scope="col">{{ __('Document') }}</th>
                                </tr>
                            </thead>
                           <tbody>
    @forelse ($files as $file)
        <tr>
            <td>{{ $file->file_path }}</td>
            <td>
                <a href="{{ asset('storage/todo-tasks/'.$file->file_path) }}" target="_blank" class="btn btn-sm btn-success" title="{{__('Show')}}">
                    <i class="ti ti-eye"></i>
                </a>
                <a href="{{ asset('storage/todo-tasks/'.$file->file_path) }}" download class="btn btn-sm btn-primary" title="{{__('Download')}}">
                    <i class="ti ti-download"></i>
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="2">{{ __('No attachments found.') }}</td>
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
