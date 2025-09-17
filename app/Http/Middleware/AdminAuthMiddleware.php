<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      // 로그인이 안되어있다면 로그인 페이지로 이동
      $user = auth()->user();
      if(!$user) {
        return redirect()->route('admin.login');
      }

      return $next($request);

      // access-admin 권한으로 대체
      // 어드민 페이지에 접근 권한이 없는 경우 일반 페이지로 이동
      // if(in_array($user->role, ['admin', 'manager'])) {
      //   return $next($request);
      // } else {
      //   return redirect()->route('index');
      // }
    }
}
