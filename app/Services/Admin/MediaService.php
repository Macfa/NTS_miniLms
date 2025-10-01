<?php

namespace App\Services\Admin;

use App\Models\Program;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Spatie\Image\Image;

class MediaService
{
  protected Program $programModel;

  public function __construct(Program $programModel) {
    $this->programModel = $programModel;
  }
  public function storeMedia(Model $model, array $files)
  {
    foreach ($files as $file) {
      $mimeType = $file->getMimeType();
      if(str_starts_with($mimeType, 'image/')) {
        $media = $model->addMedia($file)->toMediaCollection('images');
      } else if(str_starts_with($mimeType, 'video/')) {
        $media = $model->addMedia($file)->toMediaCollection('videos');
      } else {
        $media = $model->addMedia($file)->toMediaCollection('files');
      }
      // 저장 후 Media 인스턴스에서 타입별 추가 처리 가능
    }
  }
  public function storePrivateMedia(Model $model, array $files)
  {
    foreach ($files as $file) {
      $mimeType = $file->getMimeType();
      if(str_starts_with($mimeType, 'image/')) {
        $media = $model->addMedia($file)->toMediaCollection('private_images', 'private');
      } else if(str_starts_with($mimeType, 'video/')) {
        $media = $model->addMedia($file)->toMediaCollection('private_videos', 'private');
      } else {
        $media = $model->addMedia($file)->toMediaCollection('private_files', 'private');
      }
      // 저장 후 Media 인스턴스에서 타입별 추가 처리 가능
    }
  }
}
