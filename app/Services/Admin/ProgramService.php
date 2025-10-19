<?php

namespace App\Services\Admin;

use App\Events\Admin\Mail\StoreProgramEvent;
use App\Domains\ProgramAggregate;
use App\Models\User;
use App\Models\Program;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProgramService
{
  protected Program $programModel;
  protected MediaService $mediaService;
  protected ProgramAggregate $programAggregate;

  public function __construct(Program $programModel, MediaService $mediaService, ProgramAggregate $programAggregate) {
    $this->programModel = $programModel;
    $this->mediaService = $mediaService;
    $this->programAggregate = $programAggregate;
  }
  public function createProgramWithMedia(array $data, array $files, User $user)
  {
    // 도메인 Aggregate에 위임 (트랜잭션/이벤트 포함)
    return $this->programAggregate->createProgramWithMedia($data, $files, $user);
  }
   public function updateProgram(int $id, array $data)
  {
    return DB::transaction(function () use ($id, $data) {
      $program = $this->programModel->findOrFail($id);
      $updateData = [
        'category' => $data['category'],
        'name' => $data['name'],
        'description' => $data['description'],
        // manager_id는 관리자만 변경 가능. 강사는 기존 소유자 유지
        'manager_id' => $program->manager_id,
        'total_week' => $data['total_week'],
        'limit_count' => $data['limit_count'],
        'total_price' => $data['total_price'],
        'status' => $data['status'],
        'updated_at' => now(),
      ];

      // 관리자면 승인 상태/소유자(manager_id) 변경 허용
      if (Gate::allows('is-admin')) {
        if (isset($data['manager_id'])) {
          $updateData['manager_id'] = (int) $data['manager_id'];
        }
        if (isset($data['approval_status'])) {
        $updateData['approval_status'] = $data['approval_status'];
        }
      }
      $program->update($updateData);
      return $program;
    });
  }
  public function getPrograms()
  {
    $programs = $this->programModel->with(['user'])->get();
    return $programs;
  }
  public function getProgram(int $id)
  {
    return $this->programModel->with(['user'])->findOrFail($id);
  }
  public function deleteProgram(int $id)
  {
    $program = $this->programModel->findOrFail($id);
    return $program->delete();
  }
  public function searchPrograms(string $keyword)
  {
    return $this->programModel->whereHas('manager.user', function($query) use ($keyword){
      $query->where('name', 'LIKE', "%{$keyword}%");
    })->where('name', 'LIKE', "%{$keyword}%")
    ->get();
  }
  public function deletePrograms(array $ids): int
  {
    return DB::transaction(function () use ($ids) {
      $programs = $this->programModel->whereIn('id', $ids)->get();
      $count = 0;
      foreach ($programs as $program) {
        $program->delete();
        $count++;
      }
      return $count;
    });
  }
  public function approvePrograms(array $ids, int $approval_status): int
  {
    return DB::transaction(function () use ($ids, $approval_status) {
      $programs = $this->programModel->whereIn('id', $ids)->get();
      $count = 0;
      foreach ($programs as $program) {
        $program->approval_status = $approval_status;
        $program->save();
        $count++;
      }
      return $count;
    });
  }
  public function rejectPrograms(array $ids, int $approval_status): int
  {
    return DB::transaction(function () use ($ids, $approval_status) {
      $programs = $this->programModel->whereIn('id', $ids)->get();
      $count = 0;
      foreach ($programs as $program) {
        $program->approval_status = $approval_status;
        $program->save();
        $count++;
      }
      return $count;
    });
  }
}
