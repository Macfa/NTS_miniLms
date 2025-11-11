<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main\AuthController;
use App\Http\Controllers\Main\MainController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminCurriculumController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\AdminManagerController;
use App\Http\Controllers\Admin\AdminCourseController;

Route::prefix('/')->group(function () {
  // 임시 
  Route::get('/', [MainController::class, 'index'])->name('index');
  Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
  Route::match(['get', 'post'], '/register', [AuthController::class, 'register'])->name('register');
  Route::get('/course/{course}', [CourseController::class, 'show'])->name('course.show');

  Route::get('/cart', [CartController::class, 'index'])->name('cart');
  Route::post('/cart/add', [CartController::class, 'addItem'])->name('cart.add');
  // Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
  // Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
});

Route::prefix('admin')->group(function () {
  Route::match(['get', 'post'], '/', [AdminAuthController::class, 'login'])->name('admin.login');
  // Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login');
  Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
  
  Route::middleware(['auth', 'can:access-admin-page'])->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // 학생 관리
    Route::get('/student/search', [AdminStudentController::class, 'search'])->name('admin.student.search');
    Route::resource('student', AdminStudentController::class, ['as' => 'admin']); // 학생 관리 라우트
    Route::post('/student/destroyMany', [AdminStudentController::class, 'destroyMany'])->name('admin.student.destroyMany');
    // 강사 관리 라우트는 관리자만 접근 가능
    Route::middleware('can:is-admin')->group(function () {
      Route::get('/manager/search', [AdminManagerController::class, 'search'])->name('admin.manager.search');
      Route::resource('manager', AdminManagerController::class, ['as' => 'admin']); // 강사 관리 라우트
      Route::post('/manager/destroyMany', [AdminManagerController::class, 'destroyMany'])->name('admin.manager.destroyMany');
      Route::post('/Course/approvalMany', [AdminCourseController::class, 'approvalMany'])->name('admin.Course.approvalMany');
      Route::post('/Course/rejectionMany', [AdminCourseController::class, 'rejectionMany'])->name('admin.Course.rejectionMany');
    });
    Route::get('/Course/search', [AdminCourseController::class, 'search'])->name('admin.Course.search');
    Route::resource('Course', AdminCourseController::class, ['as' => 'admin']); // 프로그램 관리 라우트
    Route::post('/Course/destroyMany', [AdminCourseController::class, 'destroyMany'])->name('admin.Course.destroyMany');
    
    Route::get('/curriculum/search', [AdminCurriculumController::class, 'search'])->name('admin.curriculum.search');
    Route::resource('curriculum', AdminCurriculumController::class, ['as' => 'admin']); // 챕터 관리 라우트
    Route::post('/curriculum/destroyMany', [AdminCurriculumController::class, 'destroyMany'])->name('admin.curriculum.destroyMany');

  });
});