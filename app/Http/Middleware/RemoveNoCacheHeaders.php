<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RemoveNoCacheHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Xử lý ở đây, sau khi mọi middleware khác đã chạy xong
        if ($request->is('/') && auth()->check()) {
            // Gỡ hết các header liên quan đến cache cũ
            $response->headers->remove('Cache-Control');
            $response->headers->remove('Pragma');
            $response->headers->remove('Expires');

            // Gắn lại header cache mới
            $response->headers->set('Cache-Control', 'public, max-age=600');
        }

        return $response;
    }
}
