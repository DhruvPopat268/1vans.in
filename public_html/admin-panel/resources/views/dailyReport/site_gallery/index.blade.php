@extends('layouts.admin')
@section('page-title')
    {{__('Manage Site Gallery')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Site Gallery')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">

    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="row">
          @forelse($reports as $report)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm position-relative">
            <div class="card-body text-center">
                <h5 class="card-title mt-3">
                    <i class="fa fa-briefcase me-2"></i> {{ $report->nameOfWork->name ?? 'N/A' }}
                </h5>

                <div class="mt-4 d-flex justify-content-center gap-2">
                    {{-- Image Button --}}
                    <a href="{{ route('site-gallery.show', $report->name_of_work_id) }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-image me-1"></i> {{ __('Images') }}
                    </a>

                    {{-- Video Button --}}
                    <a href="{{ route('site-gallery.video', $report->name_of_work_id) }}" class="btn btn-dark btn-sm">
                        <i class="fa fa-video me-1"></i> {{ __('Videos') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <p class="text-center">{{ __('No reports found.') }}</p>
    </div>
@endforelse



        </div>
    </div>
</div>

@endsection

@push('script-page')
@endpush
