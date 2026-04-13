@extends('layouts.admin')
@section('page-title')
    {{__('Material Order Details')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Material Order Details')}}</li>
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
            <h5>Form #{{ $materialpurchase->location }}</h5>
        </div>
        <div class="card-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">

                             <p><strong>Date:</strong> {{ $materialpurchase->date }}</p>
                             <p><strong>Location:</strong> {{ $materialpurchase->location }}</p>
                             <p><strong>Vendor Name:</strong> {{ $materialpurchase->vendor_name }}</p>

                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">

            <p><strong>Description:</strong> {{ $materialpurchase->description }}</p>
            <p><strong>Status:</strong> {{ $materialpurchase->status }}</p>
            <p><strong>Created By:</strong> {{ $materialpurchase->user->name ?? 'N/A' }}</p>
            <p><strong>Signature:</strong>
                @if($materialpurchase->signature)
                    <img src="{{ asset('storage/' . $materialpurchase->signature) }}" width="100">
                @else
                    N/A
                @endif
            </p>
                        </div>
                    </div>

            <hr>
                    <h6>Material - {{ $materialpurchase->category->name ?? 'N/A' }}</h6>
            <table class="table">
                <thead>
                    <tr>

                        <th>Material Name</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materialpurchase->stocks as $stock)
                        <tr>
                            <td>{{ $stock->subCategory->name ?? 'N/A' }}</td>
                            <td>{{ $stock->stock }} {{ $stock->subCategory->attribute->name }}</td>
                        </tr>
                    @empty
                        <tr>
                                    <td colspan="2">No Material Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
@endpush
