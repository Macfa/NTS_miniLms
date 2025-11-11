<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use DomainException;
use RuntimeException;

class CartService
{
  public function addToCart(User $user, int $course_id)
    {
      if (!$course_id) {
        throw new NotFoundException('COURSE_ID_REQUIRED');
      }

      $user_id = $user->id;

      return DB::transaction(function () use ($user_id, $course_id) {
        // 레코드 조회 (잠금은 DB 종류/규모에 따라 select for update 고려 가능)
        $existing = Cart::where('user_id', $user_id)->where('course_id', $course_id)->first();
        if ($existing) {
          // 정책: 중복이면 수량 +1 (혹은 DomainException 'ALREADY_IN_CART') 중 선택
          $existing->quantity = ($existing->quantity ?: 1) + 1;
          $existing->touch(); // updated_at 갱신
          $existing->save();
          Log::info('Cart item quantity incremented', [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'quantity' => $existing->quantity,
          ]);
          return $existing;
        }
        // 신규 생성 (기본 quantity=1)
        $created = Cart::create([
          'user_id' => $user_id,
          'course_id' => $course_id,
          'quantity' => 1,
        ]);
        Log::info('Cart item created', [
          'user_id' => $user_id,
          'course_id' => $course_id,
        ]);
        return $created;
      });
    }
}