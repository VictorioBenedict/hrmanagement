<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SharedInThreeRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (in_array(auth()->user()->role, ['Admin', 'System Admin','Employee'])) {
            return $next($request);
        }

        // if (Auth::check() && Auth::user()->role === 'Admin' || Auth::user()->role === 'SystemAdmin' || Auth::user()->role === 'Employee') {
        //     return $next($request);
        // }

        notify()->error('Admin, System Admin, Employee, only authorized to access this resource');
        return redirect()->back();
    }
}
