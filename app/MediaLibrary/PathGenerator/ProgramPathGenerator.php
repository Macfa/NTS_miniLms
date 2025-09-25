<?php

namespace App\MediaLibrary\PathGenerator;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

/**
 * Program 전용 PathGenerator
 * 기본(미디어 ID 기반) 디렉토리 구조를 모델/컬렉션 기반 구조로 바꿔
 * 한 번의 다중 업로드(또는 동일 Program에 대한 반복 업로드) 파일이
 * 하나의 폴더(program/{program_id}/{collection})에 모이도록 한다.
 *
 * 구조 예시:
 *  program/15/images/파일들
 *  program/15/images/conversions/
 *  program/15/images/responsive/
 *  program/15/videos/...
 */
class ProgramPathGenerator implements PathGenerator
{
    protected function basePath(Media $media): string
    {
        $model = $media->model; // Program 인스턴스
        // Program 외에 잘못 매핑되었을 경우를 대비한 fallback
        $short = strtolower((new \ReflectionClass($model))->getShortName());
        // program/{program_id}/{collection_name}/
        return $short . '/' . $model->getKey() . '/' . $media->collection_name . '/';
    }

    public function getPath(Media $media): string
    {
        return $this->basePath($media);
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->basePath($media) . 'conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->basePath($media) . 'responsive/';
    }
}
