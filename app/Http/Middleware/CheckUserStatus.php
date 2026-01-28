<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->banned == 1) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Tài Khoản Của Bạn Đã Bị Khoá');
        }

        return $next($request);
    }
}
