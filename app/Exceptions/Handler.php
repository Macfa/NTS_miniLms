<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Exception;
use DomainException;
use RuntimeException;

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
        $this->renderable(function (Throwable $e, $request) {
            if($request->expectsJson()){
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 500);

            }
        });
    }
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }
}
