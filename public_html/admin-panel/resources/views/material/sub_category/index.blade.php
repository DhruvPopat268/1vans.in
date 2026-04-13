@extends('layouts.admin')
@section('page-title')
    {{__('Manage Material Sub Categories')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Material Sub Categories')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @can('create material units')
            <a href="#" data-size="lg" data-url="{{ route('material.subcategory.create', $id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Sub Category')}}" data-title="{{__('Create Sub Category')}}" class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New Sub Category') }}</span>
            </a>
        @endcan
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
                            <th scope="col" >{{__('Action')}}</th>
                            <th scope="col">{{__('Sub Category Names')}}</th>
                            <th scope="col">{{__('Material Units')}}</th>
                            <th scope="col" >{{__('Price')}}</th>
                            <th scope="col">{{__('Used Stock')}}</th>
                            <th scope="col">{{__('Available Stock')}}</th>
                            <th scope="col" >{{__('Total Stock')}}</th>
                            <th scope="col" >{{__('Wastages')}}</th>
                            <th scope="col">{{__('Total Amount')}}</th>
                              <th scope="col" >{{__('Status')}}</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($material_sub_category as $sub_category)
                                <tr>
                                    <td>
                                        

                                        @can('edit material units')
                                         <div class="action-btn bg-info ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('material.subcategory.edit', $sub_category->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{ __('Edit') }}" data-title="{{ __('Edit Status') }}">
                                                <i class="ti ti-pencil text-white"></i>
                                            </a>
                                        </div>
                                        @endcan
                                    </td>
                                    <td>{{ $sub_category->name }}</td>
                                    <td>{{ optional($sub_category->attribute)->name }}</td>
                                    <td>{{ $sub_category->price }}</td>
                                     <td>{{ $sub_category->used_stock ?? 0 }}</td>
                                     <td>{{ ($sub_category->total_stock ?? 0) - ($sub_category->used_stock ?? 0) }}</td>
                                    <td>{{ $sub_category->total_stock ?? 0 }}</td>
                                    <td>0</td>
                                    <td>
                                         {{ number_format(($sub_category->used_stock ?? 0) * ($sub_category->price ?? 0), 2) }}
                                     </td>
                                    <td>{{ $sub_category->status ?? '-' }}</td>
                                    
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
