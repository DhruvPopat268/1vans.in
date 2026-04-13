@extends('layouts.admin')
@section('page-title')
    {{__('Daily Report Details')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Daily Report Details')}}</li>
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
                    <h5>Form #{{ $report->location }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                           <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($report->date)->format('d-m-Y') }}</p>
                           <p><strong>Weather:</strong> {{ $report->weather ?? 'N/A' }}</p>
                             <p><strong>Name Of Work:</strong> {{ $report->nameOfWork->name ?? '-' }}</p>
                            <!--<p><strong>For:</strong> {{ $report->for }}</p>-->
                            <!--<p><strong>At:</strong> {{ $report->at ?? 'N/A' }}</p>-->
                            <p><strong>Types Of Work:</strong> {{ $report->mainCategory->name ?? 'N/A' }}</p>
                            <p><strong>Work Area:</strong> {{ $report->wing->name ?? 'N/A' }}</p>
                            <p><strong>Work Section:</strong> {{ $report->flour->name ?? 'N/A' }}</p>
                            <!--<p> {{ $report->UnitCategory->name ?? '-' }} - {{ $report->subCategory->name ?? '-' }}</p>-->
                            <p><strong>Measurments:</strong>
    @if($report->measurements && $report->measurements->isNotEmpty())

        @php
            $m = $report->measurements[0];
            $value = $m->mesurements_value ?? 0;
        @endphp

        @if($value == 0)
            <!--<span style="color:red; font-weight:600;">Working in Progress</span>-->
                        <span>Working in Progress</span>

        @else
            {{ $m->attribute->name ?? '-' }} - {{ $value }} {{ $report->nameOfWork->mesurementsubAttribute->name ?? '-' }}
        @endif

    @else
        <p>No Measurement Found</p>
    @endif
</p>

                        </div>

                        <!-- Right Column -->
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
                        </div>
                    </div>
                      <hr>
<h6>Location Map:</h6>
<div class="map-container" style="height: 400px;">
    @php
        // Fallback coordinates if not available
        $lat = $report->latitude ?? '22.309425';
        $lng = $report->longitude ?? '72.136230';
    @endphp
    <iframe
        src="https://maps.google.com/maps?q={{ $lat }},{{ $lng }}&t=k&z=15&output=embed"
        width="100%"
        height="100%"
        style="border:0;"
        allowfullscreen=""
        loading="lazy">
    </iframe>
</div>

                  <hr>
                   <h6>Man Power</h6>
            <table class="table">
                <thead>
                    <tr>

                        <th>Name</th>
                        <th>Price</th>
                        <th>Person</th>
                    </tr>
                </thead>
                <tbody>
                   @forelse($report->manpowers as $stock)
    <tr>
        <td>{{ $stock->manPower->name ?? 'N/A' }}</td>
        <td>{{ $stock->manPower->price ?? 'N/A' }}</td>
        <td>{{ $stock->total_person }}</td>
    </tr>
@empty
    <tr>
        <td colspan="3">No Man Power Found</td>
    </tr>
@endforelse

                </tbody>
            </table>

            <hr>
<h6>Material Used</h6>
<table class="table">
    <thead>
        <tr>
            <th>Category</th>
            <th>Sub Category</th>

            <th>Used Stock</th>
        </tr>
    </thead>
    <tbody>
        @forelse($report->materials as $material)
            <tr>
                <td>{{ $material->subCategory->category->name ?? 'N/A' }}</td>
                <td>{{ $material->subCategory->name ?? 'N/A' }}</td>
                <td>{{ $material->used_stock ?? '0' }} {{ $material->subCategory->attribute->name ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No Material Found</td>
            </tr>
        @endforelse
    </tbody>
</table>
<!--                     <hr>-->
<!--<h6>Measurements</h6>-->
<!--<table class="table">-->
<!--    <thead>-->
<!--        <tr>-->
<!--            <th>Measurement Value</th>-->
<!--            <th>Attribute</th>-->
<!--        </tr>-->
<!--    </thead>-->
<!--    <tbody>-->
<!--        @forelse($report->measurements as $measure)-->
<!--            <tr>-->
<!--                <td>{{ $measure->mesurements_value ?? '-' }}</td>-->
<!--                <td>{{ $measure->attribute->name ?? '-' }}</td>-->
<!--            </tr>-->
<!--        @empty-->
<!--            <tr>-->
<!--                <td colspan="2">No Measurements Found</td>-->
<!--            </tr>-->
<!--        @endforelse-->
<!--    </tbody>-->
<!--</table>-->
<hr>
<h6>Equipments</h6>
<table class="table">
    <thead>
        <tr>
            <th>Equipment Name</th>
            <th>Total Hours</th>
            <th>Rate</th>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>
        @forelse($report->equipments as $equipment)
            <tr>
                <td>{{ $equipment->equipment->name ?? 'N/A' }}</td>
                <td>
                    @php
                        $hours = floor($equipment->total_hours);
                        $minutes = round(($equipment->total_hours - $hours) * 60);
                    @endphp
                    {{ $hours }} Hours {{ $minutes }} Minutes
                </td>
                <td>{{ $equipment->rate ?? 'N/A' }}</td>
                <td>{{ $equipment->total_amount ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2">No Equipment Found</td>
            </tr>
        @endforelse
    </tbody>
</table>

<hr>
            <h6>Images:</h6>
<div class="row">
    @foreach($report->dailyReportImage ?? [] as $image)
        <div class="col-md-3 mb-2">
                                    <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank">

            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Material Image" class="img-fluid" style="max-height: 200px;">
            </a>
        </div>
    @endforeach

    @if(empty($report->dailyReportImage) || $report->dailyReportImage->isEmpty())
        <div class="col-12">
            <p>No images available.</p>
        </div>
    @endif
                    </div>
                    
                    <hr>
<h6>Video:</h6>
@if($report->video_path)
    <div class="mb-3">
        <video controls style="width: 100%; max-height: 400px;">
            <source src="{{ asset('storage/' . $report->video_path) }}" type="video/mp4">
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
@endsection

@push('script-page')
@endpush
