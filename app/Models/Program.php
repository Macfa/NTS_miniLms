<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
  use SoftDeletes, HasFactory;

    protected $fillable = [
        'category',
        'name',
        'description',
        'manager_id',
        'total_week',
        'limit_count',
        'total_price',
        'status',
        'approval_status',
    ];
    
    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
    public function user()
    {
      return $this->hasOneThrough(User::class, Manager::class, 'id', 'id', 'manager_id', 'user_id');
    }
    public function manager() {
        return $this->belongsTo(Manager::class, 'manager_id');
    }
    public function getApprovalStatusAttribute() 
    {
      switch($this->attributes['approval_status']) {
        case 1:
          return '승인';
        case 2:
          return '승인 대기';
        case 3:
          return '승인 거부';
        default:
          return '승인 대기';
      }
    }
    public function getDeletedAtAttribute()
    {
      $this->deleted_at ?? 'deleted';
    }
}
