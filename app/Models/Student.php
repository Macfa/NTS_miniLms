<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
  use SoftDeletes;
  protected $fillable = ['user_id'];

  public function user() {
    return $this->belongsTo(User::class);
  }
  protected static function booting()
  {
    parent::booting();

    static::deleting(function ($student) {
      if ($student->user) {
        $student->user->delete();
      }
    });
  }
}
