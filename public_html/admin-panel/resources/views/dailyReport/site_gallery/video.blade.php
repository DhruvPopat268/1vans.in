@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Site Gallery Videos') }}
@endsection

@push('css-page')
@endpush

@push('script-page')
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Site Gallery Videos') }}</li>
@endsection

@section('action-btn')
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Site Gallery Videos') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($reports as $report)
                        @if(!empty($report->video_path))
                           <div class="col-md-2 mb-4">
                                <div class="card">
                                    <div class="card-body text-center">

                                        <video controls style="width: 100%; height: auto;">
                                            <source src="{{ asset('storage/' . $report->video_path) }}" type="video/mp4">
                                            {{ __('Your browser does not support the video tag.') }}
                                        </video>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="col-12">
                            <p class="text-center">{{ __('No videos found.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
