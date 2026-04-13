@extends('layouts.admin')
@section('page-title')
    {{__('Work Issue Details')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Work Issue Details')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
        <div class="card-header">
            <h5>Form #{{ $workissue->location }}</h5>
        </div>
        <div class="card-body">
<p><strong>Date:</strong> {{ $workissue->date }}</p>
<p><strong>Name Of Work:</strong> {{ $workissue->name_of_work }}</p>

            <p><strong>Location:</strong> {{ $workissue->location }}</p>
            <p><strong>Issue:</strong> {{ $workissue->issue }}</p>
            <p><strong>Description:</strong> {{ $workissue->description }}</p>
            <p><strong>Status:</strong> {{ $workissue->status }}</p>
            <p><strong>Created By:</strong> {{ $workissue->user->name ?? 'N/A' }}</p>

            <hr>

            <hr>
            <h6>Work Issue Images:</h6>
<div class="row">
    @foreach($workissue->workissueImage ?? [] as $image)
        <div class="col-md-3 mb-2">
            <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank">
                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Material Image" class="img-fluid" style="max-height: 200px;">
            </a>
        </div>
    @endforeach

    @if(empty($workissue->workissueImage) || $workissue->workissueImage->isEmpty())
        <div class="col-12">
            <p>No images available.</p>
        </div>
    @endif
</div>

<hr>
<h6>Work Issue Video:</h6>
@if($workissue->video_path)
    <div class="mb-3">
        <video controls style="width: 100%; max-height: 400px;">
            <source src="{{ asset('storage/' . $workissue->video_path) }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
@else
    <p>No video available.</p>
@endif



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
@endpush
