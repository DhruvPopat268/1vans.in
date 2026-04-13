<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteGalleryController extends Controller
{
    public function index()
{
                     $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage site gallery', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage site gallery'))
    );

    if ($hasPermission) {
        $user = \Auth::user();

        $reports = DailyReport::with('nameOfWork')
            ->where('project_id', $user->project_assign_id)
            ->get()
            ->groupBy('name_of_work_id')
            ->map(function ($group) {
                return $group->first(); // return only one report per group
            });

        return view('dailyReport.site_gallery.index', compact('reports'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function show($name_of_work_id)
{
    $user = \Auth::user();
    $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // ✅ Same permission logic as index()
    $hasPermission = (
        ($user->type === 'company' && in_array('manage site gallery', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage site gallery'))
    );

    if ($hasPermission) {
        $reports = DailyReport::where('project_id', $user->project_assign_id)
            ->where('name_of_work_id', $name_of_work_id)
            ->pluck('id');

        $images = \App\Models\DailyReportImages::whereIn('daily_reports_id', $reports)
            ->with('dailyReport.nameOfWork')
            ->get();

        return view('dailyReport.site_gallery.show', compact('images'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function showVideos($name_of_work_id)
{
    $user = \Auth::user();
    $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // ✅ Unified permission logic
    $hasPermission = (
        ($user->type === 'company' && in_array('manage site gallery', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage site gallery'))
    );

    if ($hasPermission) {
        $reports = DailyReport::where('project_id', $user->project_assign_id)
            ->where('name_of_work_id', $name_of_work_id)
            ->get();

        return view('dailyReport.site_gallery.video', compact('reports'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}



}
