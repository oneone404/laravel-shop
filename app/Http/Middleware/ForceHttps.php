<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttps
{
    public function handle(Request $request, Closure $next)
    {
        // Nếu có www thì redirect về non-www
        if (str_starts_with($request->getHost(), 'www.')) {
            $nonWwwHost = str_replace('www.', '', $request->getHost());
            return redirect()->to('https://' . $nonWwwHost . $request->getRequestUri());
        }

        return $next($request);
    }
}
