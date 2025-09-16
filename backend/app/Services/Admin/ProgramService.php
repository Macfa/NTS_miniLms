<?php

namespace App\Services\Admin;

use App\Events\Admin\Mail\StoreProgramEvent;
use App\Models\User;
use App\Models\Program;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class ProgramService
{
  protected Program $program;

  public function __construct(Program $program) {
    $this->program = $program;
  }
  public function createProgram(array $data)
  {
    return DB::transaction(function () use ($data) {
      $program = $this->program->create([
        'category' => $data['category'],
        'name' => $data['name'],
        'description' => $data['description'],
        'manager_id' => $data['manager_id'],
        'total_week' => $data['total_week'],
        'limit_count' => $data['limit_count'],
        'total_price' => $data['total_price'],
        'approval_status' => 0, // 기본값 승인대기
        'status' => $data['status'],
      ]);
      StoreProgramEvent::dispatch($program);
      return $program;
    });
  }
  public function updateProgram(int $id, array $data)
  {
    return DB::transaction(function () use ($id, $data) {
      $program = $this->program->findOrFail($id);
    $updateData = [
      'category' => $data['category'],
      'name' => $data['name'],
      'description' => $data['description'],
      'manager_id' => $data['manager_id'],
      'total_week' => $data['total_week'],
      'limit_count' => $data['limit_count'],
      'total_price' => $data['total_price'],
      'status' => $data['status'],
      'updated_at' => now(),
    ];

    // 서비스에서 직접 게이트 체크
    if (Gate::allows('is-admin') && isset($data['approval_status'])) {
      $updateData['approval_status'] = $data['approval_status'];
    }
    $program->update($updateData);


  // 챕터 관련 로직 제거

      return $program;
    });
  }
  public function getPrograms()
  {
    $programs = $this->program->with(['user'])->get();
    return $programs;
  }
  public function getProgram(int $id)
  {
    return $this->program->with(['user'])->findOrFail($id);
  }
  public function deleteProgram(int $id)
  {
    $program = $this->program->findOrFail($id);
    return $program->delete();
  }
  public function searchPrograms(string $keyword)
  {
    return $this->program->whereHas('manager.user', function($query) use ($keyword){
      $query->where('name', 'LIKE', "%{$keyword}%");
    })->where('name', 'LIKE', "%{$keyword}%")
    ->get();
  }
}
