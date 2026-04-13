@extends('layouts.admin')
@section('page-title')
    {{__('Manage Material Record')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Material Record')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">

    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                        <tr>
                            <th scope="col">{{__('Material Names')}}</th>
                            <th scope="col">{{__('Total Used')}}</th>
                            <th scope="col" >{{__('Available Stock')}}</th>
                            <th scope="col" >{{__('Wastages')}}</th>
                            <th scope="col" >{{__('Total Material')}}</th>
                            <th scope="col">{{__('Total Amount')}}</th>
                            <th scope="col" >{{__('Order History')}}</th>
                            <th scope="col" >{{__('Material Order')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($materialcategory as $category)
                                @php
                                    $totalUsed = $category->subcategories->sum('used_stock');
                                    $totalMaterial = $category->subcategories->sum('total_stock');
                                     $availableStock = $totalMaterial - $totalUsed;
                                      $totalAmount = $category->subcategories->sum(function($sub) {
                                               return ($sub->used_stock ?? 0) * ($sub->price ?? 0);
                                          });
                                @endphp
                                <tr>
                                    <td>
                                       

                                    @can('show types of material')
                                        <div class="action-btn bg-warning ms-2">
                                            <a href="{{ route('material.subcategory.index',$category->id) }}"
                                               class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               data-bs-whatever="{{__('View Budget Planner')}}" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('View')}}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                        </div>
                                        @endcan
                                        {{ $category->name }}</td>
                                    <td>{{ $totalUsed }}</td>
                                    <td>{{ $availableStock }}</td> {{-- Placeholder for Available Stock --}}
                                    <td>--</td> {{-- Placeholder for Wastages --}}
                                    <td>{{ $totalMaterial }}</td> {{-- Placeholder for Total Material --}}
                                    <td>{{ $totalAmount }}</td>
                                    <td>
                                        @can('manage material incoming')
                                        <div class="action-btn bg-warning ms-2">
                                            <a href="{{ route('material-analysis.show', $category->id) }}"
                                               class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               data-bs-whatever="{{__('View Budget Planner')}}" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('View')}}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                        </div>
                                        @endcan
                                        ({{ $category->material_incomings_count  }})
                                    </td> {{-- Placeholder for Order History --}}
                                    <td>
                                        @can('manage material order')
                                         <div class="action-btn bg-warning ms-2">
                                            <a href="{{ route('material.purchase.order.index', $category->id) }}"
                                               class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               data-bs-whatever="{{__('View Budget Planner')}}" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('View')}}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                        </div>
                                        @endcan
                                        ({{ $category->purchase_orders_count }})
                                        
                                    </td> {{-- Placeholder for Purchase Order --}}
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
