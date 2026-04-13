<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/themify-icons@0.1.2/css/themify-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
    background: #0b5a8b;

            border-bottom: 1px solid #e5e7eb;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 76px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 32px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #6366f1;
            text-decoration: none;
        }

        .logo img {
    height: 40px;
    object-fit: contain;
}


        .nav-menu {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 9px;
            text-decoration: none;
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
            gap: 6px;
            margin-right: -50px;
        }

        .nav-item:hover {
            color: #ffffff;
        }

        .nav-item.active {
            color: #ffffff;
            border-bottom-color: #004085;
        }

        .nav-item .dropdown-arrow {
            font-size: 12px;
            transition: transform 0.2s ease;
        }

        .nav-item:hover .dropdown-arrow {
            transform: rotate(180deg);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
            background:#0b5a8b;

        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .user-avatar:hover {
            transform: scale(1.05);
        }

        .dropdown {
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 12px 16px;
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: #ffffff;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 180px;
            background: #0b5a8b;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            z-index: 10;
            padding: 8px 0;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-item {
            display: block;
            padding: 10px 16px;
            font-size: 14px;
            color: #ffffff;
            text-decoration: none;
            transition: background 0.2s ease;
        }



        .dropdown-item:hover {
            background: #f3f4f6;
        }

        .dropdown-item.active {
            background-color: #eef2ff;
            color: #FFFFF;
            font-weight: 500;
        }

         .global-search-container {
    position: relative;
    padding: 12px 24px;
    border-bottom: 1px solid #e5e7eb;
    z-index: 100; /* ensure it stays on top */
}

#searchResults {
    position: absolute;
    top: 100%;
    left: 24px;
    right: 24px;
    background: white;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border-radius: 6px;
    max-height: 240px;
    overflow-y: auto;
    display: none;
    z-index: 999; /* increased z-index */
}


#globalMenuSearch {
   width: 100%; padding: 10px; font-size: 14px; border: 1px solid #ccc; border-radius: 6px;
}

.project-text{
    color:white;
}

 .bg-teal {
        background: #0b5a8b;
    }

    .text-white {
        color: white !important;
    }

    .text-black {
        color: black !important;
    }

    .hoverable:hover {
        background-color: #006673 !important;
        color: white !important;
    }

    .no-hover:hover {
        background-color: white !important;
        color: black !important;
    }



     @media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        align-items: flex-start;
        padding: 12px 16px;
        margin-top: 70px;
    }

    .navbar-left {
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
    }

    .nav-menu {
        display: none;
        width: 100%;
        flex-direction: column;
        background: #ffffff;
        border-top: 1px solid #e5e7eb;
        padding: 12px 0;
    }

    .nav-menu.show {
        display: flex;
    }

    .nav-item {
        width: 100%;
        margin: 0;
        padding: 10px 16px;
        border-bottom: 1px solid #f1f1f1;
        color: #333 !important;
    }

    .nav-item .dropdown-arrow {
        display: none;
    }

    .dropdown-menu {
        position: relative;
        box-shadow: none;
        border: none;
        padding-left: 12px;
    }

    .dropdown:hover .dropdown-menu {
        display: block;
    }

    .menu-toggle {
        display: block;
    }

    .navbar-right {
        flex-direction: column-reverse;
        align-items: flex-start;
        gap: 10px;
        width: 100%;
        margin-top: 59px;
       display: ruby;
        position: absolute;
        background-color:white;
    }

    .navbar-right .drp-company {
        display:none !important;
    }

    .global-search-container {
   display:none;
}

#mobileglobalMenuSearch {
    width: 100%;
    font-size: 14px;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;

}

#mobilesearchResults {
    position: absolute;
    top: 100%;
    left: 114px; /* aligns with search bar's margin-left */
    right: 16px;
    background: white;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 6px;
    max-height: 200px;
    overflow-y: auto;
    font-size: 14px;
    z-index: 9999;
}

.project-text{
    color:black;
}
.your-project{
    margin-top:10px;
}

}

    </style>
</head>
<body>
@php
    use App\Models\Utility;
    $setting = \App\Models\Utility::settings();
    $logo = \App\Models\Utility::get_file('uploads/logo');
    $profile=\App\Models\Utility::get_file('uploads/avatar/');

    $company_logo = $setting['company_logo_dark'] ?? '';
    $company_logos = $setting['company_logo_light'] ?? '';
    $company_small_logo = $setting['company_small_logo'] ?? '';

  $projectId = auth()->user()->project_assign_id;

$notifications = \App\Models\WebNotification::where('project_id', $projectId)->orderBy('created_at', 'desc')->get();
$notificationCount = \App\Models\WebNotification::where('project_id', $projectId)
    ->where('status', 'Pending')
    ->count();

    $emailTemplate = \App\Models\EmailTemplate::emailTemplateData();
    $lang = Auth::user()->lang;

    $userPlan = \App\Models\Plan::getPlan(\Auth::user()->show_dashboard());
    $user = Auth::user();
    $webAccess = is_array(Auth::user()->web_access) ? Auth::user()->web_access : json_decode(Auth::user()->web_access ?? '[]', true);

@endphp

@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on')
    <header class="dash-header transprent-bg d-block d-md-none">
@else
    <header class="dash-header d-block d-md-none">
@endif
    <div class="header-wrapper" style="background: #0b5a8b;">
        <div class="me-auto dash-mob-drp">
            <ul class="list-unstyled">
                <li class="dash-h-item mob-hamburger">
                    <a href="#!" class="dash-head-link" id="mobile-collapse" style="background-color: white;">
                        <div class="hamburger hamburger--arrowturn">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>
                    </a>
                </li>

               <li class="dropdown dash-h-item drp-company">
    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" style="background-color: white;">
        <span class="theme-avtar">
            <img src="{{ !empty(\Auth::user()->avatar) ? $profile . \Auth::user()->avatar :  $profile.'avatar.png'}}" class="img-fluid rounded border-2 border border-primary">
        </span>
        <span class="hide-mob ms-2">{{__('Hi, ')}}{{\Auth::user()->name }}!</span>
        <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
    </a>
    <div class="dropdown-menu dash-h-dropdown">
        <a href="{{route('profile')}}" class="dropdown-item">
            <i class="ti ti-user text-dark"></i><span>{{__('Profile')}}</span>
        </a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="dropdown-item">
            <i class="ti ti-power text-dark"></i><span>{{__('Logout')}}</span>
        </a>
        <form id="frm-logout" action="{{ route('logout') }}" method="POST" class="d-none">
            {{ csrf_field() }}
        </form>
    </div>
</li>

<!-- Move search bar OUTSIDE the dropdown above -->
<li class="dash-h-item" style="width: 74%; padding: 0 16px; margin-top: -2px; position: absolute; margin-left: 114px;">
    <input type="text" id="mobileglobalMenuSearch" oninput="filterGlobalMenuMobile()" placeholder="Search" autocomplete="off" style="width: 100%;">
    <div id="mobilesearchResults"></div>
</li>


            </ul>
        </div>
        <div class="ms-auto">

        </div>

    </div>

    </header>

<div class="navbar-right" style="display: flex; align-items: center; justify-content: space-between; width: 100%;">

    <!-- 🔹 Left: Profile -->
    <div class="dash-h-item drp-company" style="display: flex; align-items: center; gap: 10px;">
        <span class="theme-avtar">
            <img src="{{ !empty(\Auth::user()->avatar) ? $profile . \Auth::user()->avatar :  $profile.'avatar.png'}}"
                 class="img-fluid rounded-circle border border-primary"
                 style="width: 32px; height: 32px; object-fit: cover;">
        </span>
        <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
           role="button" aria-haspopup="true" aria-expanded="false"
           style="display: flex; align-items: center; gap: 6px; text-decoration: none; font-weight: 500; color:white;">
            <span>{{ __('Hi, ') }}{{ \Auth::user()->name }}!</span>
            <i class="ti ti-chevron-down drp-arrow"></i>
        </a>
        <div class="dropdown-menu" style="margin-top:10px;">
            <a href="{{ route('profile') }}" class="dropdown-item {{ request()->routeIs('profile') ? 'active' : '' }}">Profile</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="dropdown-item">Logout</a>
            <form id="frm-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                {{ csrf_field() }}
            </form>
        </div>
    </div>

    <!-- 🔹 Center: Global Search -->
    <div class="global-search-container" style="flex: 1; margin: 0 32px;">
        <input type="text" id="globalMenuSearch" oninput="filterGlobalMenu()" placeholder="Search" autocomplete="off">
        <div id="searchResults"></div>
    </div>

    <!-- 🔹 Right Section -->
    <div style="display:flex; align-items:center; gap:18px;">

        <!-- ✅ Support / Contact -->
@if(auth()->user()->type != 'super admin')
<div class="support-contact" style="display:flex; align-items:center; gap:6px; color:white; font-weight:500;">
    <i class="ti ti-headset" style="font-size:18px;"></i>
    <span>+91 77788-81307</span>
</div>
@endif

        <!-- 🔹 Project Selection -->
        @if(auth()->user()->type != 'super admin')
        <div class="your-project">
            <form method="post" action="{{ route('update.project') }}" class="d-flex align-items-center">
                @csrf
                @php
                    $projects = \App\Models\Project::where('created_by', auth()->id())->pluck('project_name', 'id');
                    $userDetail = \Auth::user();

                    if ($userDetail->type === 'company') {
                        $projects = \App\Models\Project::where('created_by', $userDetail->id)
                            ->pluck('project_name', 'id');
                    } else {
                        $assignedProjects = is_array($userDetail->project_id)
                            ? $userDetail->project_id
                            : json_decode($userDetail->project_id, true);

                        $projects = \App\Models\Project::whereIn('id', $assignedProjects ?? [])
                            ->pluck('project_name', 'id');
                    }
                @endphp

                <label for="project_assign_id" class="me-2 fw-bold mb-0 project-text" style="white-space: nowrap;">
                    {{ __('Your Selected Project:') }}
                </label>

                <select name="project_assign_id" id="project_assign_id"
                        class="form-select form-select-sm me-2" style="min-width: 180px;">
                    <option value="">{{ __('Select Project') }}</option>
                    @foreach($projects as $id => $name)
                        <option value="{{ $id }}" {{ $userDetail->project_assign_id == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-sm"
                        style="background:radial-gradient(at top center, rgba(132, 133, 255, 1) 0%,rgba(70, 72, 255, 1) 100%); color:white;">
                    {{ __('Update') }}
                </button>
            </form>
        </div>
        @endif

    </div>
</div>

    <nav class="navbar">
        <div class="navbar-left">
            <a href="#" class="logo">
                @if ($setting['cust_darklayout'] && $setting['cust_darklayout'] == 'on')
                    <img src="{{ $logo . '/' . (isset($company_logos) && !empty($company_logos) ? $company_logos : 'logo-dark.png') . '?' . time() }}"
                        alt="{{ config('app.name', 'Vans') }}" class="logo logo-lg" height="40">
                @else
                    <img src="{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-light.png') . '?' . time() }}"
                        alt="{{ config('app.name', 'Vans') }}" class="logo logo-lg" height="40">
                @endif
            </a>


@if(auth()->user()->type === 'super admin')
    <!-- Super Admin Menu -->
    <div class="nav-menu" style="gap: 55px;">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="ti ti-home"></i> Super Dashboard
        </a>

        <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
            <i class="ti ti-users"></i> Admin
        </a>

        <a href="{{ route('project.superadmin.index') }}" class="nav-item {{ request()->routeIs('project.superadmin.index') ? 'active' : '' }}">
            <i class="ti ti-users"></i> Admin Project
        </a>

         <a href="{{ route('plans.index') }}" class="nav-item {{ request()->routeIs('plans.index') ? 'active' : '' }}">
            <i class="ti ti-trophy"></i> Plan
        </a>

        <a href="{{ route('plan_request.index') }}" class="nav-item {{ request()->routeIs('plan_request.index') ? 'active' : '' }}">
            <i class="ti ti-arrow-up-right-circle"></i> Plan Request
        </a>
        <a href="{{ route('referral-program.index') }}" class="nav-item {{ request()->routeIs('referral-program.index') ? 'active' : '' }}">
            <i class="ti ti-discount-2"></i> Referral Program
        </a>
        <a href="{{ route('coupons.index') }}" class="nav-item {{ request()->routeIs('coupons.index') ? 'active' : '' }}">
            <i class="ti ti-gift"></i> Coupon
        </a>
        <a href="{{ route('order.index') }}" class="nav-item {{ request()->routeIs('order.index') ? 'active' : '' }}">
            <i class="ti ti-shopping-cart-plus"></i> Order
        </a>
        <a href="{{ route('email_template.index') }}" class="nav-item {{ request()->routeIs('email_template.index') ? 'active' : '' }}">
            <i class="ti ti-template"></i> Email Template
        </a>
        <a href="{{ route('systems.index') }}" class="nav-item {{ request()->routeIs('systems.index') ? 'active' : '' }}">
            <i class="ti ti-settings"></i> Settings
        </a>

    </div>
@else
            <div class="nav-menu">
                <div class="nav-item dropdown">
                    <div class="nav-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                        </svg>
                        Dashboard
                        <span class="dropdown-arrow">▼</span>
                    </div>
                    <div class="dropdown-menu">
                        @can('show project dashboard')
                        <a href="{{ route('project.dashboard') }}" class="dropdown-item {{ request()->routeIs('project.dashboard') ? 'active' : '' }}">
                            Project Scheduling
                        </a>
                           @endcan
                        @can('show graph dashboard')
                        <a href="{{ route('graph.dashboard') }}" class="dropdown-item {{ request()->routeIs('graph.dashboard') ? 'active' : '' }}">
                            Smart Dashboard
                        </a>
                           @endcan
                    </div>
                </div>

                <!-- ✅ Project Setup replaces Pages -->
                @if(auth()->user()->type === 'company')

                <div class="nav-item dropdown">
                    <div class="nav-link">
                        <i class="ti ti-users"></i>
                       Setup
                        <span class="dropdown-arrow">▼</span>
                    </div>
                    <div class="dropdown-menu">
                         <a href="{{ route('projects.index') }}" class="dropdown-item {{ request()->routeIs('projects.index') ? 'active' : '' }}">
                            Projects
                        </a>
                        <a href="{{ route('clients.index') }}" class="dropdown-item {{ request()->routeIs('clients.index') ? 'active' : '' }}">
                            Project Clients
                        </a>

                        <a href="{{ route('users.index') }}" class="dropdown-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
                            Project Engineers
                        </a>
                         <a href="{{ route('roles.index') }}" class="dropdown-item {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                            Project Team Roles
                        </a>
                          <a href="{{ route('other.user.index') }}" class="dropdown-item {{ request()->routeIs('other.user.index') ? 'active' : '' }}">
                             Project Teams
                        </a>

                    </div>
                </div>
                @endif


@if(
    ($user->type === 'company' && (
        in_array('manage site documents', $webAccess) ||
        in_array('manage working drawings', $webAccess) ||
        in_array('manage bill of quantity', $webAccess) ||
        in_array('manage material testing reports', $webAccess) ||
        in_array('manage autocad files', $webAccess)
    )) ||
    ($user->type !== 'company' && (
        $user->can('manage site documents') ||
        $user->can('manage working drawings') ||
        $user->can('manage bill of quantity') ||
        $user->can('manage material testing reports') ||
        $user->can('manage autocad files')
    ))
)
    <div class="nav-item dropdown">
        <div class="nav-link">
            <i class="far fa-file-alt"></i>
            Documents
            <span class="dropdown-arrow">▼</span>
        </div>
                    <div class="dropdown-menu">

            {{-- Site Documents --}}
            @if(($user->type === 'company' && in_array('manage site documents', $webAccess)) || ($user->type !== 'company' && $user->can('manage site documents')))
                         <a href="{{ route('project.document.index') }}" class="dropdown-item {{ request()->routeIs('project.document.index') ? 'active' : '' }}">
                            Site Documents
                        </a>
            @endif

            {{-- Working Drawings --}}
            @if(($user->type === 'company' && in_array('manage working drawings', $webAccess)) || ($user->type !== 'company' && $user->can('manage working drawings')))
                        <a href="{{ route('drawings.index') }}" class="dropdown-item {{ request()->routeIs('drawings.index') ? 'active' : '' }}">
                            Working Drawings
                        </a>
            @endif

            {{-- Bill Of Quantity --}}
            @if(($user->type === 'company' && in_array('manage bill of quantity', $webAccess)) || ($user->type !== 'company' && $user->can('manage bill of quantity')))
                        <a href="{{ route('billOfQuantity.index') }}" class="dropdown-item {{ request()->routeIs('billOfQuantity.index') ? 'active' : '' }}">
                          Bill Of Quantity
                        </a>
            @endif

            {{-- Material Testing Reports --}}
            @if(($user->type === 'company' && in_array('manage material testing reports', $webAccess)) || ($user->type !== 'company' && $user->can('manage material testing reports')))
                        <a href="{{ route('material-testing-reports.index') }}" class="dropdown-item {{ request()->routeIs('material-testing-reports.index') ? 'active' : '' }}">
                           Material Testing Reports
                        </a>
            @endif

            {{-- Autocad Files --}}
            @if(($user->type === 'company' && in_array('manage autocad files', $webAccess)) || ($user->type !== 'company' && $user->can('manage autocad files')))
                         <a href="{{ route('auto-desk.index') }}" class="dropdown-item {{ request()->routeIs('auto-desk.index') ? 'active' : '' }}">
                          Autocad Files
                        </a>
            @endif

                    </div>
                </div>
@endif

             @if(
    ($user->type === 'company' && (
        in_array('manage types of work', $webAccess) ||
        in_array('manage name of works', $webAccess) ||
        in_array('manage working area', $webAccess) ||
        in_array('manage types of measurements', $webAccess) ||
        in_array('manage work reports', $webAccess) ||
        in_array('manage all reports', $webAccess) ||
        in_array('manage man power', $webAccess) ||
        in_array('manage working agency', $webAccess) ||
        in_array('manage import data', $webAccess)
    )) ||
    ($user->type !== 'company' && (
        $user->can('manage types of work') ||
        $user->can('manage name of works') ||
        $user->can('manage working area') ||
        $user->can('manage types of measurements') ||
        $user->can('manage work reports') ||
        $user->can('manage all reports') ||
        $user->can('manage man power') ||
        $user->can('manage working agency') ||
        $user->can('manage import data')
    ))
)
               <div class="nav-item dropdown">
                    <div class="nav-link">
                        <i class="ti ti-report"></i>
                       Works
                        <span class="dropdown-arrow">▼</span>
                    </div>

                    <div class="dropdown-menu">
            {{-- Types Of Work --}}
            @if(($user->type === 'company' && in_array('manage types of work', $webAccess)) || ($user->type !== 'company' && $user->can('manage types of work')))
                          <a href="{{ route('main-category.index') }}" class="dropdown-item {{ request()->routeIs('main-category.index') ? 'active' : '' }}">
                            Types Of Work
                        </a>
            @endif

            {{-- Name Of Works --}}
            @if(($user->type === 'company' && in_array('manage name of works', $webAccess)) || ($user->type !== 'company' && $user->can('manage name of works')))
                          <a href="{{ route('name-of-work.index') }}" class="dropdown-item {{ request()->routeIs('name-of-work.index') ? 'active' : '' }}">
                            Name Of Works
                        </a>
                        @endif
                         <!--@can('manage working agency')-->
                     <!--   <a href="{{ route('unit-category.index') }}" class="dropdown-item {{ request()->routeIs('unit-category.index') ? 'active' : '' }}">-->
                     <!--       Working Agency-->
                     <!--   </a>-->
                     <!--   @endcan-->

            {{-- Working Area --}}
            @if(($user->type === 'company' && in_array('manage working area', $webAccess)) || ($user->type !== 'company' && $user->can('manage working area')))
                        <a href="{{ route('wing.index') }}" class="dropdown-item {{ request()->routeIs('wing.index') ? 'active' : '' }}">
                            Working Area
                        </a>
            @endif

            {{-- Types Of Measurement --}}
            @if(($user->type === 'company' && in_array('manage types of measurements', $webAccess)) || ($user->type !== 'company' && $user->can('manage types of measurements')))
                        <a href="{{ route('mesurement-attribute.index') }}" class="dropdown-item {{ request()->routeIs('mesurement-attribute.index') ? 'active' : '' }}">
                           Types Of Measurement
                        </a>
            @endif

            {{-- Work Reports --}}
            @if(($user->type === 'company' && in_array('manage work reports', $webAccess)) || ($user->type !== 'company' && $user->can('manage work reports')))
                        <a href="{{ route('daily-report.index') }}" class="dropdown-item {{ request()->routeIs('daily-report.index') ? 'active' : '' }}">
                            Work Reports
                        </a>
            @endif

            {{-- All Reports --}}
            @if(($user->type === 'company' && in_array('manage all reports', $webAccess)) || ($user->type !== 'company' && $user->can('manage all reports')))
                        <a href="{{ route('all-report.index') }}" class="dropdown-item {{ request()->routeIs('all-report.index') ? 'active' : '' }}">
                            All Reports
                        </a>
            @endif

            {{-- Man Power --}}
            @if(($user->type === 'company' && in_array('manage man power', $webAccess)) || ($user->type !== 'company' && $user->can('manage man power')))
                        <a href="{{ route('man-power.index') }}" class="dropdown-item {{ request()->routeIs('man-power.index') ? 'active' : '' }}">
                            Man Power
                        </a>
            @endif

            {{-- Import Data --}}
            @if(($user->type === 'company' && in_array('manage import data', $webAccess)) || ($user->type !== 'company' && $user->can('manage import data')))
                          <a href="{{ route('import-data.index') }}" class="dropdown-item {{ request()->routeIs('import-data.index') ? 'active' : '' }}">
                            Import Data
                        </a>
                        @endif
                    </div>
                </div>
                @endif

{{-- ================= Equipment Management ================= --}}
 @if(
    ($user->type === 'company' && (
        in_array('manage types of equipment', $webAccess) ||
        in_array('manage equipments summary', $webAccess) ||
        in_array('manage equipments reports', $webAccess)
    )) ||
    ($user->type !== 'company' && (
        $user->can('manage types of equipment') ||
        $user->can('manage equipments summary') ||
        $user->can('manage equipments reports')
    ))
)
                 <div class="nav-item dropdown">
                    <div class="nav-link">
                        <i class="fas fa-truck-monster"></i>
                        Fleet
                        <span class="dropdown-arrow">▼</span>
                    </div>
                    <div class="dropdown-menu">
            {{-- Types Of Equipment --}}
            @if(($user->type === 'company' && in_array('manage types of equipment', $webAccess)) || ($user->type !== 'company' && $user->can('manage types of equipment')))
                        <a href="{{ route('equipment.index') }}" class="dropdown-item {{ request()->routeIs('equipment.index') ? 'active' : '' }}">
                           Types Of Equipment
                        </a>
            @endif

            {{-- Equipments Summary --}}
            @if(($user->type === 'company' && in_array('manage equipments summary', $webAccess)) || ($user->type !== 'company' && $user->can('manage equipments summary')))
                        <a href="{{ route('equipment.history.index') }}" class="dropdown-item {{ request()->routeIs('equipment.history.index') ? 'active' : '' }}">
                           Equipments Summary
                        </a>
            @endif

            {{-- Equipments Report --}}
            @if(($user->type === 'company' && in_array('manage equipments reports', $webAccess)) || ($user->type !== 'company' && $user->can('manage equipments reports')))
                        <a href="{{ route('equipment.report.index') }}" class="dropdown-item {{ request()->routeIs('equipment.report.index') ? 'active' : '' }}">
                           Equipments Report
                        </a>
            @endif
                    </div>
                </div>
                 @endif


{{-- ================= Material Reports ================= --}}
                  @if(
    ($user->type === 'company' && (
        in_array('manage types of material', $webAccess) ||
        in_array('manage material units', $webAccess) ||
        in_array('manage material record', $webAccess)
    )) ||
    ($user->type !== 'company' && (
        $user->can('manage types of material') ||
        $user->can('manage material units') ||
        $user->can('manage material record')
    ))
)
                <div class="nav-item dropdown">
                    <div class="nav-link">
                        <i class="ti ti-report"></i>
                       Materials
                        <span class="dropdown-arrow">▼</span>
                    </div>
                    <div class="dropdown-menu">
            {{-- Material Units --}}
            @if(($user->type === 'company' && in_array('manage material units', $webAccess)) || ($user->type !== 'company' && $user->can('manage material units')))
                        <a href="{{ route('attribute.index') }}" class="dropdown-item {{ request()->routeIs('attribute.index') ? 'active' : '' }}">
                            Material Units
                        </a>
            @endif

            {{-- Types Of Material --}}
            @if(($user->type === 'company' && in_array('manage types of material', $webAccess)) || ($user->type !== 'company' && $user->can('manage types of material')))
                        <a href="{{ route('material-category.index') }}" class="dropdown-item {{ request()->routeIs('material-category.index') ? 'active' : '' }}">
                            Types Of Material
                        </a>
            @endif

            {{-- Material Record --}}
            @if(($user->type === 'company' && in_array('manage material record', $webAccess)) || ($user->type !== 'company' && $user->can('manage material record')))
                        <a href="{{ route('material-analysis.index') }}" class="dropdown-item {{ request()->routeIs('material-analysis.index') ? 'active' : '' }}">
                            Material Record
                        </a>
            @endif
            
            
                    </div>
                </div>
                @endif


<!--{{-- ================= ToDo List ================= --}}-->
<!--@if(($user->type === 'company' && in_array('manage todo list', $webAccess)) || ($user->type !== 'company' && $user->can('manage todo list')))-->
<!--    <a href="{{ route('to-do-list.index') }}" class="nav-item {{ request()->routeIs('to-do-list.index') ? 'active' : '' }}">-->
<!--                     <i class="fas fa-tasks"></i>-->
<!--                     ToDo List-->
<!--                 </a>-->
<!--@endif-->


<!--{{-- ================= Work Issue ================= --}}-->
<!--@if(($user->type === 'company' && in_array('manage work issue', $webAccess)) || ($user->type !== 'company' && $user->can('manage work issue')))-->
<!--                  <a href="{{ route('work-issue.index') }}" class="nav-item {{ request()->routeIs('work-issue.index') ? 'active' : '' }}" style="margin-left:10px;">-->
<!--                    <i class="fa fa-question-circle"></i>-->
<!--                       Work Issue-->
<!--                   </a>-->
<!--@endif-->


<!--{{-- ================= Site Gallery ================= --}}-->
<!--@if(($user->type === 'company' && in_array('manage site gallery', $webAccess)) || ($user->type !== 'company' && $user->can('manage site gallery')))-->
<!--                <a href="{{ route('site-gallery.index') }}" class="nav-item {{ request()->routeIs('site-gallery.index') ? 'active' : '' }}" style="margin-left:10px;">-->
<!--                     <i class="fa fa-image"></i>-->
<!--                    Gallery-->
<!--                 </a>-->
<!--@endif-->

{{-- ================= Main Dropdown ================= --}}
@if(
    ($user->type === 'company' && (
        in_array('manage todo list', $webAccess) ||
        in_array('manage work issue', $webAccess) ||
        in_array('manage site gallery', $webAccess)
    )) ||
    ($user->type !== 'company' && (
        $user->can('manage todo list') ||
        $user->can('manage work issue') ||
        $user->can('manage site gallery')
    ))
)
    <div class="nav-item dropdown">
        <div class="nav-link">
            <i class="fas fa-toolbox"></i>
            Operations
            <span class="dropdown-arrow">▼</span>
        </div>

        <div class="dropdown-menu">

            {{-- ================= ToDo List ================= --}}
            @if(($user->type === 'company' && in_array('manage todo list', $webAccess)) 
                || ($user->type !== 'company' && $user->can('manage todo list')))
                <a href="{{ route('to-do-list.index') }}"
                    class="dropdown-item {{ request()->routeIs('to-do-list.index') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i> ToDo List
                </a>
            @endif

            {{-- ================= Work Issue ================= --}}
            @if(($user->type === 'company' && in_array('manage work issue', $webAccess)) 
                || ($user->type !== 'company' && $user->can('manage work issue')))
                <a href="{{ route('work-issue.index') }}"
                    class="dropdown-item {{ request()->routeIs('work-issue.index') ? 'active' : '' }}">
                    <i class="fa fa-question-circle"></i> Work Issue
                </a>
            @endif

            {{-- ================= Site Gallery ================= --}}
            @if(($user->type === 'company' && in_array('manage site gallery', $webAccess)) 
                || ($user->type !== 'company' && $user->can('manage site gallery')))
                <a href="{{ route('site-gallery.index') }}"
                    class="dropdown-item {{ request()->routeIs('site-gallery.index') ? 'active' : '' }}">
                    <i class="fa fa-image"></i> Gallery
                </a>
            @endif

        </div>
    </div>
@endif

@if(
    ($user->type === 'company' && (
        in_array('manage holiday', $webAccess) ||
        in_array('manage engineer attendance', $webAccess)
    )) ||
    ($user->type !== 'company' && (
        $user->can('manage engineer attendance') ||
        $user->can('manage engineer attendance')
    ))
)
                 <div class="nav-item dropdown">
                    <div class="nav-link">
                        <i class="fas fa-user-check"></i>
                        Attendance
                        <span class="dropdown-arrow">▼</span>
                    </div>
                    <div class="dropdown-menu">
            {{-- Types Of Equipment --}}
            @if(($user->type === 'company' && in_array('manage holiday', $webAccess)) || ($user->type !== 'company' && $user->can('manage holiday')))
                        <a href="{{ route('holiday.index') }}" class="dropdown-item {{ request()->routeIs('holiday.index') ? 'active' : '' }}">
                           Holiday
                        </a>
            @endif

            {{-- Equipments Summary --}}
            @if(($user->type === 'company' && in_array('manage engineer attendance', $webAccess)) || ($user->type !== 'company' && $user->can('manage engineer attendance')))
                        <a href="{{ route('engineer-attendance.index') }}" class="dropdown-item {{ request()->routeIs('engineer-attendance.index') ? 'active' : '' }}">
                           Monthly Attendance
                        </a>
            @endif

           
                    </div>
                </div>
                 @endif

                    @if(
                    ($user->type === 'company' && (
                    in_array('manage site report', $webAccess)
                    )) ||
                    ($user->type !== 'company' && (
                    $user->can('manage site report')
                    ))
                    )
                    <div class="nav-item dropdown">
                        <div class="nav-link">
                            <i class="fas fa-clipboard-list"></i>
                            Site reports
                            <span class="dropdown-arrow">▼</span>
                        </div>

                        <div class="dropdown-menu">

                            {{-- ================= Site Report ================= --}}
                            @if(($user->type === 'company' && in_array('manage site report', $webAccess))
                            || ($user->type !== 'company' && $user->can('manage site report')))
                            <a href="{{ route('site-report.index') }}" class="dropdown-item {{ request()->routeIs('site-report.index') ? 'active' : '' }}">
                                <i class="fas fa-file-alt"></i> Site reports
                            </a>
                            @endif

                        </div>
                    </div>
                    @endif
                    
 @if(auth()->user()->type === 'company')
                <div class="nav-item dropdown">
                    <div class="nav-link">
                        <i class="ti ti-settings"></i>
                        Settings
                        <span class="dropdown-arrow">▼</span>
                    </div>
                    <div class="dropdown-menu">
                        <a href="{{ route('plans.index') }}" class="dropdown-item {{ request()->routeIs('plans.index') ? 'active' : '' }}">
                            Setup Subscription Plan
                        </a>
                    </div>
                </div>


                  <li class="nav-item">
    <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-circle" style="font-size: 10px;margin-top: 4px; margin-left: -7px;">
            {{ $notificationCount ?? 0 }}
        </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="notificationDropdown" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
        
         {{-- ✅ Dclear all button (only if notifications exist) --}}
        @if(count($notifications) > 0)
            <div class="d-flex justify-content-end mb-2 px-2">
                <form action="{{ route('notification.deleteAll') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Are you sure you want to clear all notifications for this project?')">
                        clear all
                    </button>
                </form>
            </div>

            <hr class="dropdown-divider">
        @endif

    @forelse($notifications as $notification)
    @php
        $isRead = $notification->status === 'Read';
        $class = $isRead
            ? 'bg-white text-black no-hover'
            : 'bg-teal text-white hoverable';
    @endphp
    <li>
    <div class="d-flex justify-content-between align-items-start">
        {{-- Notification link --}}
        <a class="dropdown-item flex-grow-1 {{ $class }}"
           href="{{ route('notification.read', $notification->id) }}">
            <strong>{{ $notification->title }}</strong><br>
            <small>{!! nl2br($notification->formattedMessage()) !!}</small>
        </a>

        {{-- Delete icon/button --}}
        <form action="{{ route('notification.delete', $notification->id) }}" method="POST" class="ms-2">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-link text-danger p-0 m-1" title="Delete" style="font-size:28px">
                &times;
            </button>
        </form>
    </div>
    </li>


    <li><hr class="dropdown-divider"></li>
@empty
    <li><span class="dropdown-item">No new notifications</span></li>
@endforelse



    </ul>
</li>

                 @endif

            </div>
            @endif
        </div>
        <br>
        {{-- <div class="navbar-right">
            @if(auth()->user()->type != 'super admin')
        <div class="ms-auto" style="margin-right: 16px;">
            <form method="post" action="{{ route('update.project') }}" class="d-flex align-items-center">
                @csrf
                @php
                    $projects = \App\Models\Project::where('created_by', auth()->id())->pluck('project_name', 'id');
                    $userDetail = \Auth::user();
                @endphp

                <label for="project_assign_id" class="me-2 fw-bold text-dark mb-0" style="white-space: nowrap;">
                    {{ __('Your Selected Project:') }}
                </label>

                <select name="project_assign_id" id="project_assign_id" class="form-select form-select-sm me-2" style="min-width: 180px;">
                    <option value="">{{ __('Select Project') }}</option>
                    @foreach($projects as $id => $name)
                        <option value="{{ $id }}" {{ isset($userDetail) && $userDetail->project_assign_id == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-sm btn-primary">{{ __('Update') }}</button>
            </form>
        </div>
           @endif
          <div class="dropdown dash-h-item drp-company" style="display: flex; align-items: center; gap: 10px;">
             <span class="theme-avtar">
              <img src="{{ !empty(\Auth::user()->avatar) ? $profile . \Auth::user()->avatar :  $profile.'avatar.png'}}"
                 class="img-fluid rounded-circle border border-primary"
                 style="width: 32px; height: 32px; object-fit: cover;">
              </span>
                      <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"
                        style="display: flex; align-items: center; gap: 6px; text-decoration: none; font-weight: 500; {{ auth()->user()->type === 'super admin' ? 'color: white;' : 'color: #374151;' }}">
                            <span>{{ __('Hi, ') }}{{ \Auth::user()->name }}!</span>
                          <i class="ti ti-chevron-down drp-arrow"></i>
                       </a>


                      <div class="dropdown-menu" style="margin-top:10px;">
                        <a href="{{ route('profile') }}" class="dropdown-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                            Profile
                        </a>
                       <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="dropdown-item">
                           <span>{{ __('Logout') }}</span>
                           </a>
                   <form id="frm-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                {{ csrf_field() }}
                     </form>
         </div>
    </div> --}}

</div>

    </nav>

    <script>
    function toggleMenu() {
        const menu = document.querySelector('.nav-menu');
        menu.classList.toggle('show');
    }

    // Define menu items and routes
  const menuItems = [
        @if(auth()->user()->type === 'super admin')
            { name: 'Super Dashboard', url: "{{ route('dashboard') }}" },
            { name: 'Admin', url: "{{ route('users.index') }}" },
            { name: 'Plan', url: "{{ route('plans.index') }}" },
            { name: 'Plan Request', url: "{{ route('plan_request.index') }}" },
            { name: 'Referral Program', url: "{{ route('referral-program.index') }}" },
            { name: 'Coupon', url: "{{ route('coupons.index') }}" },
            { name: 'Order', url: "{{ route('order.index') }}" },
            { name: 'Email Template', url: "{{ route('email_template.index') }}" },
            { name: 'Settings', url: "{{ route('systems.index') }}" }
        @else
            { name: 'Dashboard', url: "{{ route('project.dashboard') }}" },
            { name: 'Graph Dashboard', url: "{{ route('graph.dashboard') }}" },
            { name: 'Client', url: "{{ route('clients.index') }}" },
            { name: 'Projects', url: "{{ route('projects.index') }}" },
            { name: 'Engineer', url: "{{ route('users.index') }}" },
            { name: 'Site Gallery', url: "{{ route('site-gallery.index') }}" },
            { name: 'Working Drawings', url: "{{ route('drawings.index') }}" },
            { name: 'Project Documents', url: "{{ route('project.document.index') }}" },
            { name: 'Material Testing Reports', url: "{{ route('material-testing-reports.index') }}" },
            { name: 'Bill Of Quantity', url: "{{ route('billOfQuantity.index') }}" },
            { name: 'Equipment', url: "{{ route('equipment.index') }}" },
            { name: 'Equipment Summary', url: "{{ route('equipment.history.index') }}" },
            { name: 'Daily Report', url: "{{ route('equipment.report.index') }}" },
            { name: 'Dimension', url: "{{ route('attribute.index') }}" },
            { name: 'Material Name', url: "{{ route('material-category.index') }}" },
            { name: 'Material Analysis', url: "{{ route('material-analysis.index') }}" },
            { name: 'Work Issue', url: "{{ route('work-issue.index') }}" },
            { name: 'Category', url: "{{ route('unit-category.index') }}" },
            { name: 'Work Area', url: "{{ route('wing.index') }}" },
            { name: 'Name Of Work', url: "{{ route('name-of-work.index') }}" },
            { name: 'Mesurement Attribute', url: "{{ route('mesurement-attribute.index') }}" },
            { name: 'Man Power', url: "{{ route('man-power.index') }}" },
            { name: 'Report', url: "{{ route('daily-report.index') }}" },
            { name: 'All Report', url: "{{ route('all-report.index') }}" },
            { name: 'Setup Subscription Plan', url: "{{ route('plans.index') }}" }
        @endif
    ];

    function filterGlobalMenu() {
        const input = document.getElementById('globalMenuSearch');
        const resultsContainer = document.getElementById('searchResults');
        const filter = input.value.toLowerCase();

        resultsContainer.innerHTML = '';

        if (filter.trim() === '') {
            resultsContainer.style.display = 'none';
            return;
        }

        const matches = menuItems.filter(item =>
            item.name.toLowerCase().includes(filter)
        );

        if (matches.length === 0) {
            resultsContainer.innerHTML = '<div style="padding: 10px; color: #888;">No results found</div>';
        } else {
            matches.forEach(item => {
                const div = document.createElement('div');
                div.innerHTML = item.name;
                div.style.padding = '10px 16px';
                div.style.cursor = 'pointer';
                div.style.borderBottom = '1px solid #f1f1f1';

                div.addEventListener('click', () => {
                    window.location.href = item.url;
                });

                div.addEventListener('mouseover', () => {
                    div.style.background = '#f3f4f6';
                });

                div.addEventListener('mouseout', () => {
                    div.style.background = '#fff';
                });

                resultsContainer.appendChild(div);
            });
        }

        resultsContainer.style.display = 'block';
    }

        function filterGlobalMenuMobile() {
        const input = document.getElementById('mobileglobalMenuSearch');
        const resultsContainerMobile = document.getElementById('mobilesearchResults');
        const filter = input.value.toLowerCase();

        resultsContainerMobile.innerHTML = '';

        if (filter.trim() === '') {
            resultsContainerMobile.style.display = 'none';
            return;
        }

        const matches = menuItems.filter(item =>
            item.name.toLowerCase().includes(filter)
        );

        if (matches.length === 0) {
            resultsContainerMobile.innerHTML = '<div style="padding: 10px; color: #888;">No results found</div>';
        } else {
            matches.forEach(item => {
                const div = document.createElement('div');
                div.innerHTML = item.name;
                div.style.padding = '10px 16px';
                div.style.cursor = 'pointer';
                div.style.borderBottom = '1px solid #f1f1f1';

                div.addEventListener('click', () => {
                    window.location.href = item.url;
                });

                div.addEventListener('mouseover', () => {
                    div.style.background = '#f3f4f6';
                });

                div.addEventListener('mouseout', () => {
                    div.style.background = '#fff';
                });

                resultsContainerMobile.appendChild(div);
            });
        }

        resultsContainerMobile.style.display = 'block';
    }

    // Hide search results if clicked outside
document.addEventListener('click', function (e) {
        const searchBox = document.getElementById('globalMenuSearch');
        const results = document.getElementById('searchResults');
        if (!searchBox.contains(e.target) && !results.contains(e.target)) {
            results.style.display = 'none';
    }
});

document.addEventListener('click', function (e) {
        const searchBox = document.getElementById('mobileglobalMenuSearch');
        const results = document.getElementById('mobilesearchResults');
        if (!searchBox.contains(e.target) && !results.contains(e.target)) {
            results.style.display = 'none';
    }
});


        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function (e) {
                if (this.classList.contains('dropdown')) return;
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });

        document.querySelector('.user-avatar').addEventListener('click', function () {
            alert('User menu clicked!');
        });

         function toggleMenu() {
        const menu = document.querySelector('.nav-menu');
        menu.classList.toggle('show');
    }
    </script>
</body>
</html>
