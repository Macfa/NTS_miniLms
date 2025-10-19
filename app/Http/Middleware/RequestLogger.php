<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequestLogger
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('Request start', ['url' => $request->url()]);
        dump('Request dump: ' . $request);
        $response = $next($request);
        Log::info('Request end', ['status' => $response->status()]);
        return $response;
    }
}
