<?php

namespace App\Domains;

use App\Events\Admin\Mail\StoreProgramEvent;
use App\Models\Program;
use App\Models\User;
use App\Services\Admin\MediaService;
use Illuminate\Support\Facades\DB;

class ProgramAggregate
{
    /**
     * @var Program
     */
    protected Program $programModel;

    /**
     * @var MediaService
     */
    protected MediaService $mediaService;

    public function __construct(Program $programModel, MediaService $mediaService)
    {
        $this->programModel = $programModel;
        $this->mediaService = $mediaService;
    }

    /**
     * 프로그램 생성 + 미디어 저장(트랜잭션) 후 이벤트 디스패치
     *
     * 책임 분리:
     * - 권한/역할에 따른 기본값 결정(approval_status, manager_id)
     * - DB 트랜잭션 경계 설정 및 영속화
     * - 미디어 저장
     * - 커밋 이후 이벤트 발행
     *
     * @param array $data    유효성 검증을 통과한 입력 데이터
     * @param array $files   업로드 파일 배열(attachments)
     * @param User  $user    요청자(역할 기반 분기)
     *
     * @return Program
     */
    public function createProgramWithMedia(array $data, array $files, User $user): Program
    {
        $program = DB::transaction(function () use ($data, $files, $user) {
            $payload = $data;

            // 역할 기반 manager_id/approval_status 결정
            if ($user->role === 'manager') {
                $payload['manager_id'] = $user->manager?->id;
                // 강사는 기본 대기값(테이블 기본값 0) 유지
                $payload['approval_status'] = 0;
            } elseif ($user->role === 'admin') {
                // 관리자면 전달값을 우선 사용, 없으면 기본값 1
                $payload['approval_status'] = isset($payload['approval_status'])
                    ? (int) $payload['approval_status']
                    : 1;
            }

            // 생성
            $program = $this->programModel->create([
                'category' => $payload['category'],
                'name' => $payload['name'],
                'description' => $payload['description'],
                'manager_id' => $payload['manager_id'] ?? null,
                'total_week' => $payload['total_week'],
                'limit_count' => $payload['limit_count'],
                'total_price' => $payload['total_price'],
                'approval_status' => $payload['approval_status'] ?? 0,
                'status' => $payload['status'],
            ]);

            // 미디어 저장
            $this->mediaService->storeMedia($program, $files);

            return $program;
        });

        // 트랜잭션 커밋 후 이벤트 발행
        StoreProgramEvent::dispatch($program);

        return $program;
    }
}