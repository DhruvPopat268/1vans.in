@extends('layouts.admin')
@section('page-title')
  {{ __('Equipment Summary for ') . $equipment->name }}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{ __('Equipment Summary for ') . $equipment->name }}</li>
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
                    <h5>{{ $equipment->name }} - Detailed History</h5>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Total Hours</th>
                                <th>Rate</th>
                                <th>Total Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->start_time)->format('h:i A') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->end_time)->format('h:i A') }}</td>
                                    @php
                                    $hours = floor($item->total_hours);
                                    $minutes = round(($item->total_hours - $hours) * 60);
                                @endphp
                                <td>{{ $hours }} Hours {{ $minutes }} Minutes</td>

                                        <td>{{ number_format($item->rate, 2) }}</td>
                                        <td>{{ number_format($item->total_amount, 2) }}</td>
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
