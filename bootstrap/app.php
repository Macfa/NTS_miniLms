<?php

use App\Http\Middleware\RequestLogger;
use App\Http\Middleware\RequestIdMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
  ->withMiddleware(function (Middleware $middleware): void {
    // 주의: 앱 부트스트랩 이전에는 컨테이너에 'env' 바인딩이 없을 수 있으므로
    // app()->environment('local') 같은 호출은 ReflectionException을 유발할 수 있음.

    $applyEnvs = ['local', 'development']; // 필요 시 확장
    $env = strtolower(trim($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'production'));
    if (in_array($env, $applyEnvs, true)) {
        // $middleware->append(RequestIdMiddleware::class);
    }

    // 요청/응답 로그 ( Tobe Delete ! )
    // $middleware->append(RequestLogger::class);
  })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->withEvents(discover: [
      // 
    ])->create();
