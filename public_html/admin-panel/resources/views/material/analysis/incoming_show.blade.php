@extends('layouts.admin')
@section('page-title')
    {{__('Material Incoming Details')}}
@endsection
@push('script-page')
<style>
  .view-btn {
    display: inline-block;
    padding: 6px 14px;
    background-color: #28a745;
    color: #fff !important;   /* ⭐ fix */
    font-weight: bold;
    border-radius: 5px;
    text-decoration: none;
    margin-left: 10px;
}

.view-btn:hover {
    background-color: #1e7e34;
    color: #fff !important;   /* ⭐ same color */
    text-decoration: none;
}
</style>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Material Incoming Details')}}</li>
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
            <h5>Form #{{ $materialincoming->location }}</h5>
        </div>
        <div class="card-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <p><strong>Challan Number:</strong> {{ $materialincoming->challan_number }}</p>
                            <p><strong>Bill Number:</strong> {{ $materialincoming->bill_number }}</p>
                            <p><strong>Vendor Name:</strong> {{ $materialincoming->vendor_name }}</p>
                            <p><strong>GST Number:</strong> {{ $materialincoming->gst_number ?? 'N/A' }}</p>
                            <p><strong>Batch Number:</strong> {{ $materialincoming->batch_number ?? 'N/A' }}</p>
                            <p><strong>E-Way Bill Number:</strong> {{ $materialincoming->eway_bill_no ?? 'N/A' }}</p>


                            <p><strong>E-Way Bill File:</strong>
                                @if($materialincoming->eway_bill_file)
                                    <a href="{{ asset('storage/' . $materialincoming->eway_bill_file) }}" target="_blank" class="view-btn">VIEW</a>
                                @else
                                    N/A
                                @endif
                            </p>
                            <p><strong>Royalty Slip Number:</strong> {{ $materialincoming->royalty_slip_no ?? 'N/A' }}</p>
                            <p><strong>Royalty Slip File:</strong>
                                @if($materialincoming->royalty_slip_file)
                                    <a href="{{ asset('storage/' . $materialincoming->royalty_slip_file) }}" target="_blank" class="view-btn">VIEW</a>
                                @else
                                    N/A
                                @endif
                            </p>
                                                        <p><strong>Comment:</strong> {{ $materialincoming->comment ?? 'N/A' }}</p>

                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <p><strong>Date:</strong> {{ $materialincoming->date }}</p>
            <p><strong>Location:</strong> {{ $materialincoming->location }}</p>
            <p><strong>Description:</strong> {{ $materialincoming->description }}</p>
            <p><strong>Vehicle Number:</strong> {{ $materialincoming->vehicle_number }}</p>
            <p><strong>Remark:</strong> {{ $materialincoming->remark }}</p>
            <p><strong>Created By:</strong> {{ $materialincoming->user->name ?? 'N/A' }}</p>
            <p><strong>Signature:</strong>
                @if($materialincoming->signature)
                    <img src="{{ asset('storage/' . $materialincoming->signature) }}" width="100">
                @else
                    N/A
                @endif
            </p>
                        </div>
                    </div>

            <hr>
                    <h6>Material - {{ $materialincoming->category->name ?? 'N/A' }}</h6>
            <table class="table">
                <thead>
                    <tr>

                        <th>Material Name</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materialincoming->stocks as $stock)
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
 <hr>
            <h6>Images:</h6>
<div class="row">
    @foreach($materialincoming->materialIncomingImage ?? [] as $image)
        <div class="col-md-3 mb-2">
            <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank">
                <img src="{{ asset('storage/' . $image->image_path) }}" 
                     alt="Material Image" 
                     class="img-fluid"
                     style="max-height: 200px;">
            </a>
        </div>
    @endforeach

    @if(empty($materialincoming->materialIncomingImage) || $materialincoming->materialIncomingImage->isEmpty())
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
