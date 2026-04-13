@extends('layouts.admin')
@section('page-title')
    {{__('Manage Name Of Works')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Name Of Works')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        @can('create name of works')
            <a href="#" data-size="lg" data-url="{{ route('name-of-work.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Name Of Work')}}" data-title="{{__('Create Name Of Work')}}" class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" 
           style="height: 48px;">
            <i class="ti ti-plus"></i>
            <span>{{ __('Create New Name Of Work') }}</span>
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
                              <th scope="col">{{__('Main Category')}}</th>
                            <th scope="col">{{__('Name')}}</th>
                                                        <th scope="col">{{__('Types Of Measurements')}}</th>
                                                         <th scope="col">{{__('Total Quantity Of Work')}}</th>
                                                          <th scope="col">{{__('Start Date')}}</th>
                                                           <th scope="col">{{__('End Date')}}</th>


                        </tr>
                        </thead>
                        <tbody>
                            @foreach($nameOfWork as $work)
                                <tr>
                                     <td>
                                        @can('edit name of works')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('name-of-work.edit', $work->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{ __('Edit') }}" data-title="{{ __('Edit Name Of Work') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        @endcan

                                    </td>
                                                                    <td>{{ $work->mainCategory ? $work->mainCategory->name : '-' }}</td>

                                    <td>{{ $work->name }}</td>
                                                                        <td>{{ $work->mesurementattribute->name ?? '-' }} ({{ $work->mesurementsubAttribute->name ?? '-' }})</td>
                                                                               <td>{{ $work->total_mesurement }}</td>
                                                                               <td>{{ $work->start_date }}</td>
                                                                                   <td>{{ $work->end_date }}</td>


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
    // On page load, jump to saved page if exists
    document.addEventListener('DOMContentLoaded', function () {
        var savedPage = localStorage.getItem('now_page_name_of_work');
        if (savedPage) {
            localStorage.removeItem('now_page_name_of_work');
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
        if (action && action.includes('name-of-work')) {
            e.preventDefault();
            var form = $(this);

            // Save current active page before reload
            var activePage = document.querySelector('.dataTable-pagination-list li.active [data-page]');
            var pageNum = activePage ? activePage.getAttribute('data-page') : '1';
            localStorage.setItem('now_page_name_of_work', pageNum);

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
                    localStorage.removeItem('now_page_name_of_work');
                    var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Something went wrong.';
                    show_toastr('Error', msg, 'error');
                }
            });
        }
    });
</script>
@endpush
