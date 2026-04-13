@extends('layouts.admin')
@php
    $profile = \App\Models\Utility::get_file('uploads/avatar');
@endphp
@section('page-title')
    {{ __('Manage Project Teams') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('Project Teams') }}</li>
@endsection

@section('action-btn')
    <div class="float-end">
        @can('create user')
            <a href="#" data-size="lg" data-url="{{ route('other.user.create') }}" data-ajax-popup="true"
               data-bs-toggle="tooltip" data-title="Create Project Team" data-bs-original-title="Create Project Team"
               class="btn btn-sm btn-primary me-1">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection

@section('content')
<div class="row">
    @foreach ($users as $user)
        <div class="col-xxl-3 col-lg-4 col-sm-6 mb-4">
            <div class="user-card d-flex flex-column h-100">
                <div class="user-card-top d-flex align-items-center justify-content-between flex-1 gap-2 mb-3">

                    {{-- ✅ Always show user type badge --}}
                    <div class="badge bg-primary p-1 px-2">
                        {{ ucfirst($user->type) }}
                    </div>

                    {{-- Edit/Delete Actions --}}
                    @if (Gate::check('edit user') || Gate::check('delete user'))
                        <div class="btn-group card-option">
                            @if ($user->is_active == 1 && $user->is_disable == 1)
                                <button type="button" class="btn p-0 border-0" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>

                                <div class="dropdown-menu icon-dropdown dropdown-menu-end">
                                    @can('edit user')
                                        <a href="#!" data-size="lg" data-url="{{ route('other.user.edit', $user->id) }}"
                                           data-ajax-popup="true" class="dropdown-item"
                                           data-bs-original-title="{{ __('Edit Project Team') }}">
                                            <i class="ti ti-pencil"></i>
                                            <span>{{ __('Edit') }}</span>
                                        </a>
                                    @endcan

                                    @can('delete user')
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['other.user.destroy', $user['id']],
                                            'id' => 'delete-form-' . $user['id'],
                                        ]) !!}
                                        <a href="#!" class="dropdown-item bs-pass-para">
                                            <i class="ti ti-trash"></i>
                                            <span>
                                                @if ($user->delete_status != 0)
                                                    {{ __('Delete') }}
                                                @else
                                                    {{ __('Restore') }}
                                                @endif
                                            </span>
                                        </a>
                                        {!! Form::close() !!}
                                    @endcan

                                    {{-- ✅ Removed "Login as Admin" option --}}

                                    <a href="#!" data-url="{{ route('other.user.reset', \Crypt::encrypt($user->id)) }}"
                                        data-ajax-popup="true" data-size="md" class="dropdown-item"
                                        data-bs-original-title="{{ __('Reset  Password') }}">
                                        <i class="ti ti-adjustments"></i>
                                        <span> {{ __('Reset Password') }}</span>
                                    </a>

                                    {{-- Enable/Disable login --}}
                                    @if ($user->is_enable_login == 1)
                                        <a href="{{ route('users.login', \Crypt::encrypt($user->id)) }}"
                                            class="dropdown-item">
                                            <i class="ti ti-road-sign"></i>
                                            <span class="text-danger"> {{ __('Login Disable') }}</span>
                                        </a>
                                    @elseif ($user->is_enable_login == 0 && $user->password == null)
                                        <a href="#"
                                            data-url="{{ route('other.user.reset', \Crypt::encrypt($user->id)) }}"
                                            data-ajax-popup="true" data-size="md" class="dropdown-item login_enable"
                                            data-title="{{ __('New Password') }}">
                                            <i class="ti ti-road-sign"></i>
                                            <span class="text-success"> {{ __('Login Enable') }}</span>
                                        </a>
                                    @else
                                        <a href="{{ route('users.login', \Crypt::encrypt($user->id)) }}"
                                            class="dropdown-item">
                                            <i class="ti ti-road-sign"></i>
                                            <span class="text-success"> {{ __('Login Enable') }}</span>
                                        </a>
                                    @endif

                                </div>
                            @else
                                <a href="#" class="action-item text-lg"><i class="ti ti-lock"></i></a>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- User Info --}}
                <div class="user-info-wrp d-flex align-items-center gap-3 border-bottom pb-3 mb-3">
                    <div class="user-image rounded-1 border-1 border border-primary">
                        <img src="{{ !empty($user->avatar) ? Utility::get_file('uploads/avatar/') . $user->avatar : asset(Storage::url('uploads/avatar/avatar.png')) }}"
                             alt="user-image" height="100%" width="100%">
                    </div>
                    <div class="user-info flex-1">
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        @if ($user->delete_status == 0)
                            <h6 class="mb-1">{{ __('Soft Deleted') }}</h6>
                        @endif
                        <span class="text-sm text-muted text-break">{{ $user->email }}</span>
                    </div>
                </div>

                {{-- Login Date + Time --}}
                <div class="date-wrp d-flex align-items-center justify-content-between gap-2">
                    @php
                        $date = \Carbon\Carbon::parse($user->last_login_at)->format('Y-m-d');
                        $time = \Carbon\Carbon::parse($user->last_login_at)->format('H:i:s');
                    @endphp
                    <div class="date d-flex align-items-center gap-2">
                        <div class="date-icon d-flex align-items-center justify-content-center">
                            <i class="f-16 ti ti-calendar text-white"></i>
                        </div>
                        <span class="text-sm">{{ $date }}</span>
                    </div>
                    <div class="time d-flex align-items-center gap-2">
                        <div class="time-icon d-flex align-items-center justify-content-center">
                            <i class="f-16 ti ti-clock text-white"></i>
                        </div>
                        <span class="text-sm">{{ $time }}</span>
                    </div>
                </div>

                {{-- ✅ Removed "Plan" and "Company Info" blocks --}}

            </div>
        </div>
    @endforeach

    {{-- Create User Button --}}
    <div class="col-xxl-3 col-lg-4 col-sm-6 mb-4">
        <a href="#" class="btn-addnew-project border-primary" data-ajax-popup="true"
           data-url="{{ route('other.user.create') }}"
           data-title="{{ __('Create Project Team') }}"
           data-bs-toggle="tooltip"
           data-bs-original-title="{{ __('Create Project Team') }}">
            <div class="bg-primary proj-add-icon">
                <i class="ti ti-plus"></i>
            </div>
            <h6 class="mt-3 mb-2">{{ __('Create Project Team') }}</h6>
            <p class="text-muted text-center mb-0">{{ __('Click here to add new Project Team') }}</p>
        </a>
    </div>
</div>
@endsection
