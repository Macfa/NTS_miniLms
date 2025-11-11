<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Curriculum extends Model
{
  use HasFactory;
  // irregular plural: Laravel은 기본적으로 'curricula'를 예상하므로, 실제 테이블명과 맞춰 명시
  protected $table = 'curriculums';
    protected $fillable = [
      'course_id',
      'title',
      'description',
      'start',
      'end',
      'week_days',
      'status',
    ];
  protected $casts = [
    'start' => 'datetime',
    'end' => 'datetime',
  ];
    protected $dayMap = [
      '0' => '월',
      '1' => '화',
      '2' => '수',
      '3' => '목',
      '4' => '금',
      '5' => '토',
      '6' => '일',
    ];
  public function course() {
    return $this->belongsTo(Course::class, 'course_id');
  }
  public function weekDays(): Attribute
  {
    return Attribute::make(
      get: function ($value) {
        $dayMap = $this->dayMap;
        $indexes = explode(',', $value);
        $days = array_map(function($day) use ($dayMap) {
          return $dayMap[$day] ?? $day;
        }, $indexes);
        return implode(',', $days); // "월,수,금"
      },
      set: function ($value) {
        $dayMap = $this->dayMap;
        $indexes = explode(',', $value);
        $days = array_map(function($day) use ($dayMap) {
          return array_search($day, $dayMap, true) !== false ? array_search($day, $dayMap, true) : $day;
        }, $indexes);
        return implode(',', $days); // "0,2,4"
      },
    );
  }

  public function status(): Attribute
  {
    return Attribute::make(
      get: fn ($value) => $value == 1 ? '활성화' : '비활성화',
      // set: fn ($value) => $value == '활성화' ? 1 : 0,
    );
  }
}
