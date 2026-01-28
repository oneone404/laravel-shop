<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UpdateLastSeen
{
public function handle(Request $request, Closure $next)
{
    \Log::info("🔥 Middleware chạy vào đây rồi");

    if (auth()->check()) {
        \Log::info("🔥 User đang đăng nhập", ['id' => auth()->id()]);
    } else {
        \Log::info("⚠ User KHÔNG đăng nhập");
    }

    return $next($request);
}

}
