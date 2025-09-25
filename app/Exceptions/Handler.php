<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function render($request, Throwable $exception)
    {
      // 미정
        // if ($exception instanceof \Illuminate\Validation\ValidationException) {
        //     return response()->view('errors.validation', ['errors' => $exception->errors()], 422);
        // }
        // return parent::render($request, $exception);
    }
}
