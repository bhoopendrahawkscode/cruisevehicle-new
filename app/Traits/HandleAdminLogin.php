<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

trait HandleAdminLogin{

    private function attemptLogin($request, $remember_me)
    {
        return Auth::guard('admin')->attempt([
            'email' => $request->username,
            'password' => $request->password,
            'status' => 1
        ], $remember_me);
    }

    private function handleFailedLogin($request, $remember_me)
    {
        if ($remember_me) {
            setcookie("remember_me", $request->username, time() + (86400 * 30));
        } else {
            $this->clearCookies();
        }
        setcookie("admin_email", $request->username, time() + (86400 * 30));
        setcookie("admin_password", $request->password, time() + (86400 * 30));
    }

    private function setRememberMeCookies($request)
    {
        setcookie("remember_me", $request->username, time() + (86400 * 30));
        setcookie("admin_email", $request->username, time() + (86400 * 30));
        setcookie("admin_password", $request->password, time() + (86400 * 30));
    }

    private function clearCookies()
    {
        setcookie('admin_email', "", time() - 36000);
        setcookie('admin_password', "", time() - 36000);
        setcookie('remember_me', "", time() - 36000);
        unset($_COOKIE["admin_email"]);
        unset($_COOKIE["admin_password"]);
        unset($_COOKIE["remember_me"]);
    }

    private function hasAdminRole()
    {
        $userRoles = Auth::guard('admin')->user()->roles;
        return $userRoles->contains('slug', 'admin') || $userRoles->contains('slug', 'sub_admin') ||  $userRoles->contains('slug', 'insurance_manager');
    }

    private function handleInvalidRole($request)
    {
        Session::flash('error', trans("messages.INVALID_CREDENTIALS"));
        Auth::guard('admin')->logout();
        if ($request->session()->has('link')) {
            $request->session()->forget('link');
        }
    }


    private function updateDeviceToken()
    {
        User::where('id', Auth::guard('admin')->user()->id)->update(['device_token' => time()]);
    }
}
