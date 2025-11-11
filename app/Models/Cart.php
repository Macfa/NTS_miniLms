<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 장바구니 항목 모델
 * 경로: app/Models/Cart.php
 * 목적: 사용자별 코스 담기. 수량/최종 추가 시점 추후 확장 고려.
 * 설명용 필드 설정: 현재 마이그레이션은 id,timestamps 만 존재하므로
 * 실제 기능 수행을 위해 user_id, course_id 컬럼이 추가되어야 한다.
 * (마이그레이션 개선 필요 - 아직 없다면 생성/수정해야 함)
 */
class Cart extends Model
{
    // 대량 할당 허용 필드 정의
    protected $fillable = [
        'user_id',
        'course_id',
        'quantity',
    ];
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    // 기본 수량 디폴트 처리 (DB에 없을 경우를 대비하여 접근 시 1로 간주)
    public function getQuantityAttribute($value)
    {
        return $value ?: 1;
    }
}
