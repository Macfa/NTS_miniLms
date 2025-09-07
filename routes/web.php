<?php

use App\Http\Middleware\AdminAuthMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ManagerController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::post('login', [AuthController::class, 'login'])->name('admin.login');
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::middleware([AdminAuthMiddleware::class, 'can:access-admin'])->group(function () {
      Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
      Route::resource('student', StudentController::class, ['as' => 'admin']); // 학생 관리 라우트
      Route::resource('manager', ManagerController::class, ['as' => 'admin']); // 강사 관리 라우트
    });
});