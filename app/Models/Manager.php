<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Manager extends Model implements HasMedia
{
  use HasFactory, SoftDeletes, InteractsWithMedia;
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
    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
      $this->addMediaConversion('preview')
        ->width(1200)
        ->height(900)
        ->sharpen(5)
        ->performOnCollections('images');
    }  
}
