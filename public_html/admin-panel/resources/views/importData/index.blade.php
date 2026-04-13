@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Import Data') }}
@endsection

@push('script-page')
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Import Data') }}</li>
@endsection

@section('action-btn')
    <div class="float-end">
    </div>
@endsection

@section('content')
@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\Project; // adjust the model name if different

    $user = Auth::user();
    $project = null;

    if ($user && $user->project_assign_id) {
        $project = Project::find($user->project_assign_id);
    }

    $projectName = $project ? $project->project_name : 'Project';
    $typesOfWork = $projectName . '_Types Of Work Sample.xlsx';
        $nameOfWorksFile = $projectName . '_Name Of Work Sample.xlsx';
    $workingAreaFile = $projectName . '_Working Area & Work Section Sample.xlsx';
     $typeOfMesurementsFile = $projectName . '_Types Of Measurements & Unit Sample.xlsx';
      $typesOfEquipmentsFile = $projectName . '_Types Of Equipment Sample.xlsx';
       $materialUnitFile = $projectName . '_Material Unit Sample.xlsx';
        $typesOfmaterialFile = $projectName . '_Types Of Material Sample.xlsx';
         $subCategoryOfMaterialFile = $projectName . '_Sub Category Of Material Sample.xlsx';
@endphp
    <div class="row">

        {{-- ❌ Error Message with skipped rows --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session('error') }}</strong>
                <ul>
                    @foreach(session('skippedRows') as $row)
                        <li>
                            Row {{ $row['row'] }} → "{{ $row['name'] }}"
                            (Project: {{ $row['project'] ?? '' }}) - {{ $row['reason'] }}
                        </li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- 📂 Types of Work Import --}}
        <div class="col-xl-6 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>{{ __('Types of Work Import') }}</h5>
                   <a href="{{ Storage::url('sample excel/types_of_work_sample.xlsx') }}"
                             class="btn btn-sm btn-primary"
                            download="{{ $typesOfWork }}">
                         <i class="ti ti-download"></i> {{ __('Download Sample') }}
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('import.typesOfWork') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="types_excel" class="form-label">{{ __('Upload Excel File') }}</label>
                            <input type="file" class="form-control" id="types_excel" name="file" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Import') }}</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- 📂 Name of Works Import --}}
        <div class="col-xl-6 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>{{ __('Name of Works Import') }}</h5>
                    <a href="{{ Storage::url('sample excel/name_of_works_sample.xlsx') }}" class="btn btn-sm btn-primary" download="{{ $nameOfWorksFile }}">
                        <i class="ti ti-download"></i> {{ __('Download Sample') }}
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('import.nameOfWorks') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="works_excel" class="form-label">{{ __('Upload Excel File') }}</label>
                            <input type="file" class="form-control" id="works_excel" name="file" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Import') }}</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- 📂 Working Area Import --}}
        <div class="col-xl-6 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>{{ __('Working Area & Work Section Import') }}</h5>
                    <a href="{{ Storage::url('sample excel/workingarea_worksection_sample.xlsx') }}" class="btn btn-sm btn-primary" download="{{ $workingAreaFile }}">
                        <i class="ti ti-download"></i> {{ __('Download Sample') }}
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('import.WorkingAreaWorkSection') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="works_excel" class="form-label">{{ __('Upload Excel File') }}</label>
                            <input type="file" class="form-control" id="works_excel" name="file" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Import') }}</button>
                    </form>
                </div>
            </div>
        </div>

         {{-- 📂 	Types Of Measurements --}}
        <div class="col-xl-6 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>{{ __('Types Of Measurements & Unit Import') }}</h5>
                    <a href="{{ Storage::url('sample excel/typesofmeasurment_unit_sample.xlsx') }}" class="btn btn-sm btn-primary" download="{{ $typeOfMesurementsFile }}">
                        <i class="ti ti-download"></i> {{ __('Download Sample') }}
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('import.TypesOfMeasurementsUnit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="works_excel" class="form-label">{{ __('Upload Excel File') }}</label>
                            <input type="file" class="form-control" id="works_excel" name="file" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Import') }}</button>
                    </form>
                </div>
            </div>
        </div>

                {{-- 📂 Types of Equipment Import --}}
        <div class="col-xl-6 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>{{ __('Types of Equipment Import') }}</h5>
                   <a href="{{ Storage::url('sample excel/types_of_equipment_sample.xlsx') }}"
                             class="btn btn-sm btn-primary"
                             download="{{ $typesOfEquipmentsFile }}">
                         <i class="ti ti-download"></i> {{ __('Download Sample') }}
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('import.typesOfEquipment') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="types_excel" class="form-label">{{ __('Upload Excel File') }}</label>
                            <input type="file" class="form-control" id="types_excel" name="file" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Import') }}</button>
                    </form>
                </div>
            </div>
        </div>

            {{-- 📂 Material Unit Import --}}
        <div class="col-xl-6 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>{{ __('Material Unit Import') }}</h5>
                   <a href="{{ Storage::url('sample excel/material_unit_sample.xlsx') }}"
                             class="btn btn-sm btn-primary"
                             download="{{ $materialUnitFile }}">
                         <i class="ti ti-download"></i> {{ __('Download Sample') }}
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('import.MaterialUnit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="types_excel" class="form-label">{{ __('Upload Excel File') }}</label>
                            <input type="file" class="form-control" id="types_excel" name="file" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Import') }}</button>
                    </form>
                </div>
            </div>
        </div>

           {{-- 📂 	Types Of Material --}}
        <div class="col-xl-6 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>{{ __('Types Of Material Import') }}</h5>
                    <a href="{{ Storage::url('sample excel/typesofmaterial_sample.xlsx') }}" class="btn btn-sm btn-primary" download="{{ $typesOfmaterialFile }}">
                        <i class="ti ti-download"></i> {{ __('Download Sample') }}
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('import.TypesofMaterial') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="works_excel" class="form-label">{{ __('Upload Excel File') }}</label>
                            <input type="file" class="form-control" id="works_excel" name="file" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Import') }}</button>
                    </form>
                </div>
            </div>
        </div>

          <div class="col-xl-6 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>{{ __('Sub Category Of Material Import') }}</h5>
                    <a href="{{ Storage::url('sample excel/subcategoryofmaterial_sample.xlsx') }}" class="btn btn-sm btn-primary" download="{{ $subCategoryOfMaterialFile }}">
                        <i class="ti ti-download"></i> {{ __('Download Sample') }}
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('import.TypesofMaterialSubCategory') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="works_excel" class="form-label">{{ __('Upload Excel File') }}</label>
                            <input type="file" class="form-control" id="works_excel" name="file" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Import') }}</button>
                    </form>
                </div>
            </div>
        </div>


    </div>
@endsection

@push('script-page')
@endpush
