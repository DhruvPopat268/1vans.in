@extends('layouts.admin')
@section('page-title')
    {{__('Material Report Details')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Material Report Details')}}</li>
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
            <h5>Form #{{ $materialreports->location }}</h5>
        </div>
        <div class="card-body">

            <p><strong>Location:</strong> {{ $materialreports->location }}</p>
            <p><strong>Vendor Name:</strong> {{ $materialreports->vendor_name }}</p>
            <p><strong>Description:</strong> {{ $materialreports->description }}</p>
            <p><strong>Challan Number:</strong> {{ $materialreports->challan_number }}</p>
            <p><strong>Bill Number:</strong> {{ $materialreports->bill_number }}</p>
            <p><strong>Vehicle Number:</strong> {{ $materialreports->vehicle_number }}</p>
            <p><strong>Remark:</strong> {{ $materialreports->remark }}</p>
            <p><strong>Created By:</strong> {{ $materialreports->user->name ?? 'N/A' }}</p>
            <p><strong>Signature:</strong>
                @if($materialreports->signature)
                    <img src="{{ asset('storage/' . $materialreports->signature) }}" width="100">
                @else
                    N/A
                @endif
            </p>
            <hr>
            <h6>Material</h6>
            <table class="table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materialreports->stocks as $stock)
                        <tr>
                            <td>{{ $stock->subCategory->category->name ?? 'N/A' }}</td>
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
 <hr>
            <h6>Images:</h6>
<div class="row">
    @foreach($materialreports->materialReportImage ?? [] as $image)
        <div class="col-md-3 mb-2">
            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Material Image" class="img-fluid" style="max-height: 200px;">
        </div>
    @endforeach

    @if(empty($materialreports->materialReportImage) || $materialreports->materialReportImage->isEmpty())
        <div class="col-12">
            <p>No images available.</p>
        </div>
    @endif
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
@endpush
