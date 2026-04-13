@extends('layouts.admin')
@section('page-title')
    {{__('Manage Work Section')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Work Section')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @can('create working area')
            <a href="#" data-size="lg" data-url="{{ route('flour.create', $id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Work Section')}}" data-title="{{__('Create Work Section')}}" class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New Work Section') }}</span>
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
                            <th scope="col">{{__('Work Section Name')}}</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($flour as $flours)
                            <tr>
                                 <td>
                                    @can('edit working area')
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('flour.edit', $flours->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{ __('Edit') }}" data-title="{{ __('Edit Work Section') }}">
                                                <i class="ti ti-pencil text-white"></i>
                                            </a>
                                        </div>
                                        {{--  <div class="action-btn bg-warning ms-2">
                                            <a href="{{ route('wing.subcategory.index',$wings->id) }}"
                                               class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               data-bs-whatever="{{__('View Budget Planner')}}" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('View')}}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                        </div>  --}}
                                    @endcan

                                </td>
                                <td>{{ $flours->name }}</td>

                               
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var savedPage = localStorage.getItem('now_page_flour');
        if (savedPage) {
            localStorage.removeItem('now_page_flour');
            var checkReady = setInterval(function () {
                var pagers = document.querySelectorAll('.dataTable-pagination-list li');
                if (pagers.length > 0) {
                    clearInterval(checkReady);
                    var targetPage = document.querySelector('.dataTable-pagination-list li [data-page="' + savedPage + '"]');
                    if (targetPage) targetPage.click();
                }
            }, 100);
        }
    });

    $(document).on('submit', '#commonModal form', function (e) {
        var action = $(this).attr('action');
        if (action && action.includes('flour')) {
            e.preventDefault();
            var form = $(this);
            var activePage = document.querySelector('.dataTable-pagination-list li.active [data-page]');
            var pageNum = activePage ? activePage.getAttribute('data-page') : '1';
            localStorage.setItem('now_page_flour', pageNum);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function (res) {
                    if (res.success) {
                        $('#commonModal').modal('hide');
                        show_toastr('Success', res.message, 'success');
                        setTimeout(function () { location.reload(); }, 800);
                    }
                },
                error: function (xhr) {
                    localStorage.removeItem('now_page_flour');
                    var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Something went wrong.';
                    show_toastr('Error', msg, 'error');
                }
            });
        }
    });
</script>
@endpush
