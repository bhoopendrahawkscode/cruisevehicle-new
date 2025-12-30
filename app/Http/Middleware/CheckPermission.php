<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Auth;
use Symfony\Component\HttpFoundation\Response;
class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $user = Auth::user();

        // if role is admin
        if ($user->hasRole('admin')) {
            return $next($request);
        }
        // Check if the user's role has the required permission
    //die($permission);
        if ($user && $user->roles()->whereHas('permissions', function($query) use ($permission) {
            $query->where('action', $permission);
        })->exists()) {
            return $next($request);
        }
        return redirect()->route('error403');
    }

}
