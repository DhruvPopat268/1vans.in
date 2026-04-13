@extends('layouts.admin')
@section('page-title')
{{__('Manage Material Testing Reports')}}
@endsection
@push('script-page')
<style>
    @media (min-width: 1200px) {
        .col-xl-2-4 {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }

    .card {
        border-radius: 12px;
        transition: 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .card-body img {
        height: 160px;
        object-fit: cover;
        border-radius: 10px;
    }

    .card-title {
        font-size: 16px;
        font-weight: 600;
        min-height: 40px;
    }

</style>
@endpush
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Material Testing Reports')}}</li>
@endsection
@section('action-btn')
<div class="float-end">
    @can('create material testing reports')
    <a href="#" data-size="lg" data-url="{{ route('material-testing-reports.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create Material Testing Reports Category')}}" data-title="{{__('Create Material Testing Reports Category')}}" class="btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-1" style="height: 48px;">
        <i class="ti ti-plus"></i>
        <span>{{ __('Create New Material Testing Reports Category') }}</span>
    </a>
    @endcan
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="row">
            @forelse($Materialtestingreports as $Materialtestingreport)
            <div class="col-xl-2-4 col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm position-relative d-flex flex-column">
                    {{-- Edit icon on top-right --}}
                    @can('edit material testing reports')
                    <div class="position-absolute" style="top: 10px; right: 10px; z-index: 1;">
                        <a href="#" data-url="{{ route('material-testing-reports.edit', $Materialtestingreport->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{ __('Edit') }}" data-title="{{ __('Edit Material Testing Reports Category') }}">
                            <i class="ti ti-dots-vertical" style="font-size: 18px; color: #000;"></i>
                        </a>


                    </div>
                    @endcan

                    <div class="card-body text-center d-flex flex-column justify-content-between">
                        <h5 class="card-title mt-3">{{ $Materialtestingreport->name }}</h5>

                        @if($Materialtestingreport->image)
                        <a href="{{ route('material-testing-reports.show', $Materialtestingreport->id) }}">
                            <img src="{{ asset('storage/' . $Materialtestingreport->image) }}" alt="{{ $Materialtestingreport->name }}" class="img-fluid mt-2">
                        </a>
                        @else
                        <div class="d-flex align-items-center justify-content-center" style="height:160px;">
                            <p class="text-muted mb-0">{{ __('No image uploaded') }}</p>
                        </div>
                        @endif

                        {{-- Latest attachments --}}
                        {{-- @if($Materialtestingreport->attachments->count())
                            <div class="mt-3 text-start">
                                <ul class="list-unstyled">
                                    @foreach($Materialtestingreport->attachments as $attachment)
                                        <li class="mb-2" style="border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $extension = strtolower(pathinfo($attachment->files, PATHINFO_EXTENSION));
                                                    $icon = 'fa fa-file';
                                                    if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                                                        $icon = 'fa fa-image';
                                                    }
                                                @endphp

                                                <i class="{{ $icon }} me-2" style="font-size: 24px;"></i>

                        <a href="{{ asset('storage/drawing_attachment/' . $attachment->files) }}" target="_blank" class="text-decoration-none">
                            {{ $attachment->files }}
                        </a>
                    </div>
                    </li>
                    @endforeach
                    </ul>
                </div>
                @endif --}}
            </div>
        </div>
    </div>

    @empty
    <div class="col-12">
       <p class="text-center">{{ __('No Material Testing Reports found.') }}</p>
    </div>
    @endforelse
</div>
</div>
</div>

@endsection

@push('script-page')
@endpush
