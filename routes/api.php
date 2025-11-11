<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 1. 장바구니에 상품을 추가하는 POST 요청 (인증 필요)
// Route::post('/cart/add', [CartController::class, 'addItem'])
//      ->middleware('auth:sanctum'); // API 인증 미들웨어 강제

// // 2. 장바구니 목록을 가져오는 GET 요청 (인증 필요)
// Route::get('/cart/items', [CartController::class, 'getCartItems'])
//      ->middleware('auth:sanctum');

// 3. 장바구니 상품을 삭제하는 DELETE 요청 (인증 필요)
// Route::delete('/cart/{item_id}', [CartController::class, 'deleteItem'])
//       ->middleware('auth:sanctum');