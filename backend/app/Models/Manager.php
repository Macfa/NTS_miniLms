<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manager extends Model
{
  use HasFactory, SoftDeletes;
    protected $fillable = ['user_id'];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
  public function getStatusTextAttribute(): string
  {
      return $this->user->status ? '활성화' : '비활성화';
  }    
  public function programs()
  {
    return $this->hasMany(Program::class, 'manager_id', 'id');
  }

    protected static function booting()
    {
      parent::booting();
      
      static::deleting(function ($manager) {
        if ($manager->user) {
          $manager->user->delete();
        }
      });
    }  
}
