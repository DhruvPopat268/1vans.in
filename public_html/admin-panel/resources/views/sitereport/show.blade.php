@extends('layouts.admin')
@section('page-title')
{{__('Site Report Details')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Site Report Details')}}</li>
@endsection
@section('action-btn')
<div class="float-end">

</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            {{-- <div class="card-header">
                <h5>Form #{{ $report->location }}</h5>
            </div> --}}
            <div class="card-body">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($sitereport->date)->format('d-m-Y') }}</p>
                        <p><strong>Name Of Work:</strong> {{ $sitereport->name_of_work ?? '-' }}</p>
                        <p><strong>Work Description:</strong> {{ $sitereport->work_description ?? 'N/A' }}</p>
                        <p><strong>Work Address:</strong> {{ $sitereport->work_address ?? 'N/A' }}</p>
                    </div>

                    {{-- <!-- Right Column -->
                    <div class="col-md-6">
                        <p><strong>Location:</strong> {{ $report->location }}</p>
                        <p><strong>Description:</strong> {{ $report->description ?? 'N/A' }}</p>
                        <p><strong>Issue:</strong> {{ $report->comment ?? 'N/A' }}</p>

                        <p><strong>Signature:</strong>
                            @if($report->signature)
                            <img src="{{ asset('storage/' . $report->signature) }}" width="100">
                            @else
                            N/A
                            @endif
                        </p>
                    </div> --}}
                </div>
                <hr>

                <h6>Images:</h6>
                <div class="row">
                    @foreach($sitereport->attachments ?? [] as $image)
                    <div class="col-md-3 mb-2">
                        <a href="{{ asset('storage/' . $image->files) }}" target="_blank">

                            <img src="{{ asset('storage/' . $image->files) }}" alt="Site Report Image" class="img-fluid" style="max-height: 200px;">
                        </a>
                    </div>
                    @endforeach

                    @if(empty($sitereport->attachments) || $sitereport->attachments->isEmpty())
                    <div class="col-12">
                        <p>No images available.</p>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
@endpush
