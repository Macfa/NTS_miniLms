<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chapter extends Model
{
  use HasFactory;
    protected $fillable = [
      'start',
      'end',
      'week_days',
      'status',
    ];

   public function program() {
    return $this->belongsTo(Program::class);
  } 
  public function getWeekDaysAttribute($value) {
    $data = [
      '0' => '월',
      '1' => '화',
      '2' => '수',
      '3' => '목',
      '4' => '금',
      '5' => '토',
      '6' => '일',
    ];
    $days = array_map(function($day) use ($data) {
      return $data[$day];
    }, explode(',', $value));
    return $days;
  }
  public function getStartAttribute($value) {
    return date('Y-m-d H:i:s', strtotime($value));
  }
  public function getEndAttribute($value) {
    return date('Y-m-d H:i:s', strtotime($value));
  }
}
