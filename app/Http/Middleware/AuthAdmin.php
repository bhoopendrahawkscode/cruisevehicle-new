<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;
use Auth;
use Redirect;
use Carbon\Carbon;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guest()) {
            return Redirect::to('admin/login');
        } else {
            $user = Auth::user();

            if ($user->status == 0 ||$user->status == 'Inactive') {
                Auth::guard('admin')->logout();
                return Redirect::to('admin/login');
            }
            if ($user->password_changed_at && $request->session()->get('last_login_at')) {
            $passwordChangedAt = new Carbon($user->password_changed_at);
            $lastLoginAt = new Carbon($request->session()->get('last_login_at'));
                if (strtotime($passwordChangedAt)> strtotime($lastLoginAt)) {
                    Auth::guard('admin')->logout();
                    return Redirect::to('admin/login')->with('success', trans("messages.PASSWORD_CHANGED"));
                }
            }



        }

        return $next($request);
    }
}
