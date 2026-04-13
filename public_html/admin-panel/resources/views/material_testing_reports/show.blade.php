@extends('layouts.admin')

@section('page-title')
    {{__('Manage Material Testing Reports')}}
@endsection

@push('script-page')
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Material Testing Reports') }}</li>
@endsection

@section('action-btn')
    <div class="float-end">
        @can('create material testing reports')
        <a href="#"
           data-size="lg"
           data-url="{{ route('material.report.details.create', ['reportId' => $material_testing_report->id]) }}"
           data-ajax-popup="true"
           data-bs-toggle="tooltip"
           title="{{__('Create New')}}"
           data-title="{{__('Create')}}"
           class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New Material Testing Reports') }}</span>
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
                                    <th scope="col">{{ __('Remark') }}</th>
                                    <th scope="col">{{ __('Document Name') }}</th>
                                    <th scope="col">{{ __('Document') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($details as $detail)
                                    <tr>
                                        <td>{{ $detail->remark }}</td>
<td>{{ basename($detail->file) }}</td>
                                        <td>
                                            <!-- Show Button -->
                                            <a href="{{ asset('storage/'.$detail->file) }}" target="_blank" class="btn btn-sm btn-success" title="{{__('Show')}}">
                                                <i class="ti ti-eye"></i>
                                            </a>

                                            <!-- Download Button -->
                                            <a href="{{ asset('storage/'.$detail->file) }}" download class="btn btn-sm btn-primary" title="{{__('Download')}}">
                                                <i class="ti ti-download"></i>
                                            </a>
                                            <div class="action-btn">
                                                @can('edit material testing reports')
                                            <a href="#" class="mx-3 btn btn-sm bg-info" data-url="{{ route('material.report.details.edit', $detail->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Edit Rename')}}">
                                                <i class="ti ti-pencil text-white"></i>
                                            </a>
                                            @endcan
                                            </div>
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
@endpush
