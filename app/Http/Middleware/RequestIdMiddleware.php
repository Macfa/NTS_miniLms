<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RequestIdMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 고유 아이디 생성
        $incoming = (string) $request->headers->get('X-Request-Id');
        $requestId = Str::isUuid($incoming) ? $incoming : (string) Str::uuid();

        //  개별 
        $request->headers->set('X-Request-Id', $requestId);
        $request->attributes->set('request_id', $requestId);
        Log::withContext(['request_id' => $requestId]);

        $response = $next($request);

        // 응답 헤더에도 포함시켜 추적 가능하게 함
        $response->headers->set('X-Request-Id', $requestId);

        return $response;
    }
}
