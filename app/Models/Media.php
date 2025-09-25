<?php

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;
use Illuminate\Support\Str;

class Media extends BaseMedia
{
    // 이미지 파일 여부
    public function isImage(): bool
    {
        return Str::startsWith($this->mime_type, 'image/');
    }

    // 비디오 파일 여부
    public function isVideo(): bool
    {
        return Str::startsWith($this->mime_type, 'video/');
    }
}
