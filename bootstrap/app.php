<?php

use App\Http\Middleware\RequestLogger;
use App\Http\Middleware\RequestIdMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        if(app()->environment('local')) 
        {
          // 요청 추적을 위한 RequestId를 전역으로 부여 ( 테스트 )
          $middleware->append(RequestIdMiddleware::class);
        }
        // 요청/응답 로그 ( Tobe Delete ! )
        $middleware->append(RequestLogger::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->withEvents(discover: [
      // 
    ])->create();
