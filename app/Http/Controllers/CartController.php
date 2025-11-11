<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Services\CourseEnrollService;
use App\Services\CartService;
class CartController extends Controller
{
  protected $courseEnrollService;
  protected $cartService;

  public function __construct(CourseEnrollService $courseEnrollService, CartService $cartService) {
    $this->courseEnrollService = $courseEnrollService;
    $this->cartService = $cartService;
  }
  public function index(Request $request)
  {
      $user = $request->user();
      $cart_items = Cart::where('user_id', $user->id)->with(['course', 'course.user'])->get();
      // dd($cart_items);
      if($cart_items->isEmpty()) {
        $cart_items = [];
      }

      return view('main.cart.index', compact('cart_items'));
  }
  public function addItem(Request $request)
  {
    // validate input
    $request->validate([
      'course_id' => 'required|integer|exists:courses,id',
    ]);

    // init values
    $course_id = $request->input('course_id');
    $user = $request->user();

    // logic to add item to cart
    $cartItem = $this->cartService->addToCart($user, $course_id);

    return response()->json([
      'status' => 'ok',
      'message' => '장바구니에 추가되었습니다.',
      'data' => $cartItem,
    ], 200);
  }
  public function deleteItem($item_id)
  {
      // 장바구니 항목 삭제 로직 구현
      return response()->json(['message' => 'Item deleted from cart successfully'], 200);
  }
}
