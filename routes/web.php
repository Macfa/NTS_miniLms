<?php

use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Main\MainController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\ProgramController;

Route::prefix('/')->group(function () {
  // 임시 
  Route::match(['get', 'post'], '/', [AuthController::class, 'login'])->name('login');
});

Route::prefix('admin')->group(function () {
  Route::match(['get', 'post'], '/', [AuthController::class, 'login'])->name('admin.login');
  // Route::post('login', [AuthController::class, 'login'])->name('admin.login');
  Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');
  
  Route::middleware(['auth', 'can:access-admin-page'])->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // 학생 관리
    Route::get('/student/search', [StudentController::class, 'search'])->name('admin.student.search');
    Route::resource('student', StudentController::class, ['as' => 'admin']); // 학생 관리 라우트
    Route::post('/student/destroyMany', [StudentController::class, 'destroyMany'])->name('admin.student.destroyMany');
    // 강사 관리 라우트는 관리자만 접근 가능
    Route::middleware('can:is-admin')->group(function () {
      Route::get('/manager/search', [ManagerController::class, 'search'])->name('admin.manager.search');
      Route::resource('manager', ManagerController::class, ['as' => 'admin']); // 강사 관리 라우트
      Route::post('/manager/destroyMany', [ManagerController::class, 'destroyMany'])->name('admin.manager.destroyMany');
      Route::post('/program/approvalMany', [ProgramController::class, 'approvalMany'])->name('admin.program.approvalMany');
      Route::post('/program/rejectionMany', [ProgramController::class, 'rejectionMany'])->name('admin.program.rejectionMany');
    });
    Route::get('/program/search', [ProgramController::class, 'search'])->name('admin.program.search');
    Route::resource('program', ProgramController::class, ['as' => 'admin']); // 프로그램 관리 라우트
    Route::post('/program/destroyMany', [ProgramController::class, 'destroyMany'])->name('admin.program.destroyMany');
    
    Route::get('/chapter/search', [ChapterController::class, 'search'])->name('admin.chapter.search');
    Route::resource('chapter', ChapterController::class, ['as' => 'admin']); // 챕터 관리 라우트
    Route::post('/chapter/destroyMany', [ChapterController::class, 'destroyMany'])->name('admin.chapter.destroyMany');

  });
});