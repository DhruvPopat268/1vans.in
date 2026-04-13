<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckWebAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::user();

        // Super admin bypasses all checks
        if ($user->type === 'super admin') {
            return $next($request);
        }

        $webAccess = is_array($user->web_access)
            ? $user->web_access
            : json_decode($user->web_access ?? '[]', true);

        // If user is a company → check web access
        if ($user->type === 'company') {
            if (!in_array($permission, $webAccess)) {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        // If user is NOT a company → check Laravel permission
        else {
            if (!$user->can($permission)) {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }

        return $next($request);
    }

}
