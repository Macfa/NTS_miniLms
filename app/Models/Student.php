<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
  protected $fillable = ['user_id'];

  public function user() {
    return $this->belongsTo(User::class);
  }
  public function getStatusTextAttribute(): string
  {
      return $this->user->status ? '활성화' : '비활성화';
  }
}
