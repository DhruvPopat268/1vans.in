@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Bill Of Quantity') }}
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/test.css') }}">
@endpush

@push('script-page')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>

    <script>
        Dropzone.autoDiscover = true;

        var myDropzone = new Dropzone("#dropzonewidget", {
            maxFiles: 20,
            parallelUploads: 1,
            url: "{{ route('billofquantity.file.upload') }}",

            success: function (file, response) {
                if (response.is_success) {
                    if(response.status==1){
                        show_toastr('success', response.success_msg, 'success');
                    }else{
                        show_toastr('{{__("success")}}', 'Attachment Create Successfully!', 'success');
                        dropzoneBtn(file, response);
                    }

                } else {

                    myDropzone.removeFile(file);
                    show_toastr('{{__("Error")}}', 'The attachment must be same as stoarge setting', 'Error');
                }
            },
            error: function (file, response) {
                myDropzone.removeFile(file);
                show_toastr('{{ __("Error") }}', 'Upload failed.', 'error');
            }
        });

        myDropzone.on("sending", function (file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
        });

        function dropzoneBtn(file, response) {
            var download = document.createElement('a');
            download.setAttribute('href', response.download);
            download.setAttribute('class', "action-btn btn-primary mx-1 mt-1 btn btn-sm d-inline-flex align-items-center");
            download.setAttribute('data-toggle', "tooltip");
            download.setAttribute('data-original-title', "{{__('Download')}}");
            download.innerHTML = "<i class='fas fa-download'></i>";

            var del = document.createElement('a');
            del.setAttribute('href', response.delete);
            del.setAttribute('class', "action-btn btn-danger mx-1 mt-1 btn btn-sm d-inline-flex align-items-center");
            del.setAttribute('data-toggle', "tooltip");
            del.setAttribute('data-original-title', "{{__('Delete')}}");
            del.innerHTML = "<i class='ti ti-trash'></i>";

            del.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm("Are you sure ?")) {
                    var btn = $(this);
                    $.ajax({
                        url: btn.attr('href'),
                        data: { _token: $('meta[name="csrf-token"]').attr('content') },
                        type: 'DELETE',
                        success: function (response) {
                            location.reload();
                        },
                        error: function (response) {
                            show_toastr('{{__("Error")}}', 'Delete failed.', 'error');
                        }
                    });
                }
            });

            var html = document.createElement('div');
            html.setAttribute('class', "text-center mt-10");
            file.previewTemplate.appendChild(html);
        }
    </script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Bill Of Quantity') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-9" style="width: 100%">
            <div id="useradd-2">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Bill Of Quantity Attachments') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="col-md-12 dropzone top-5-scroll browse-file" id="dropzonewidget"></div>
                        </div>

                        <div class="scrollbar-inner">
                            <div class="card-wrapper p-3 lead-common-box">
                                @foreach($bill_of_quantity as $file)
                                    <div class="card mb-3 border shadow-none">
                                        <div class="px-3 py-3">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <h6 class="text-sm mb-0">
                                                        <a href="{{ asset('storage/bill_of_quantity/' . $file->files) }}" target="_blank">
                                                            {{ basename($file->files) }}
                                                        </a>
                                                    </h6>
                                                    <p class="card-text small text-muted">
                                                        @if(!empty($file->files) && file_exists(storage_path('app/public/bill_of_quantity/' . $file->files)))
                                                            {{ number_format(\File::size(storage_path('app/public/bill_of_quantity/' . $file->files)) / 1048576, 2) . ' MB' }}
                                                        @endif
                                                    </p>
                                                </div>

                                                <div class="col-auto actions">
                                                    <div class="action-btn bg-warning">
                                                        <a href="{{ asset('storage/' . $file->files) }}"
                                                           class="btn btn-sm d-inline-flex align-items-center"
                                                           download="{{ basename($file->files) }}"
                                                           data-bs-toggle="tooltip" title="Download">
                                                            <span class="text-white"><i class="ti ti-download"></i></span>
                                                        </a>
                                                    </div>

                                                    <div class="action-btn bg-warning">
                                                        <a href="{{ asset('storage/' . $file->files) }}"
                                                           class="btn btn-sm d-inline-flex align-items-center mx-2"
                                                           target="_blank" data-bs-toggle="tooltip" title="View">
                                                            <span class="text-white"><i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </div>
                                                   
@can('delete bill of quantity')
                                                    <div class="action-btn bg-danger">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['billofquantity.file.delete', $file->id]]) !!}
                                                        <a href="#!" class="mx-3 btn btn-sm align-items-center bs-pass-para">
                                                            <i class="ti ti-trash text-white" data-bs-toggle="tooltip" title="{{__('Delete')}}"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                     @endcan
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
