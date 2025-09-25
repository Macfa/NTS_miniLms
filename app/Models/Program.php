<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
// use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Media;

class Program extends Model implements HasMedia
{
  use SoftDeletes, HasFactory, InteractsWithMedia;

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
    public function scopeActiveProgram(Builder $query)
    {
      return $query->where(['status' => 1, 'approval_status' => 1]);
    }
    // Spatie MediaLibrary 이미지 변환(썸네일 등) 규칙 선언
    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(1280)
            ->height(720)
            ->sharpen(10)
            ->performOnCollections('images');

      // 이미지 변환: 썸네일, 프리뷰
      $this->addMediaConversion('thumb')
        ->width(1280)
        ->height(720)
        ->sharpen(10)
        ->performOnCollections('images');

      $this->addMediaConversion('preview')
        ->width(1200)
        ->height(900)
        ->sharpen(5)
        ->performOnCollections('images');

      // 비디오 변환: 썸네일(정지화면), 프리뷰(저해상도)
      $this->addMediaConversion('video_thumb')
        ->width(640)
        ->height(360)
        ->extractVideoFrameAtSecond(1)
        ->performOnCollections('videos');

      $this->addMediaConversion('video_preview')
        ->width(1280)
        ->height(720)
        ->extractVideoFrameAtSecond(1)
        ->performOnCollections('videos');
    }
}
