@extends('layouts.admin')
@section('page-title')
    {{__('Equipment Report Details')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Equipment Report Details')}}</li>
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
            <h5>Form #{{ $form->location }}</h5>
        </div>
        <div class="card-body">

            <p><strong>Location:</strong> {{ $form->location }}</p>
            <p><strong>Description:</strong> {{ $form->description }}</p>
            <p><strong>Created By:</strong> {{ $form->user->name ?? 'N/A' }}</p>
            <p><strong>Signature:</strong>
                @if($form->signature)
                    <img src="{{ asset('storage/' . $form->signature) }}" width="100">
                @else
                    N/A
                @endif
            </p>
            <hr>
            <h6>Equipment Items</h6>
            <table class="table">
                            <thead>
                            <tr>
                        <th>Equipment</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Total Hours</th>
                        <th>Rate</th>
                        <th>Total Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                                                @foreach($form->items as $item)
                        <tr>
                            <td>{{ $item->equipment->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->start_time)->format('h:i A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->end_time)->format('h:i A') }}</td>
                            @php
    $hours = floor($item->total_hours);
    $minutes = round(($item->total_hours - $hours) * 60);
@endphp
<td>{{ $hours }} Hours {{ $minutes }} Minutes</td>
                            <td>{{ $item->rate }}</td>
                            <td>{{ $item->total_amount }}</td>
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
