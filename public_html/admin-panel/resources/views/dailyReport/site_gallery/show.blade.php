@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Site Gallery') }}
@endsection

@push('css-page')
@endpush

@push('script-page')
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Site Gallery Attachments') }}</li>
@endsection

@section('action-btn')
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Site Gallery Attachments') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($images as $image)
                        <div class="col-md-2 mb-4">
                            <div class="card">

                                            <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank">
        <img src="{{ asset('storage/' . $image->image_path) }}"
             class="card-img-top"
             alt="Report Image">
                                </a>

    {{-- Date Overlay --}}
    <div class="position-absolute bottom-0 start-0 w-100 px-2 py-1 text-end"
     style="background: rgba(0,0,0,0.6); color: #fff; font-size: 12px;">
    {{ $image->created_at->format('d/m/Y') }}
</div>

                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center">{{ __('No images found.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
