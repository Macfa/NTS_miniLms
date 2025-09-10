<?php

namespace App\Services\Admin;

use App\Events\StoreProgramEvent;
use App\Models\Chapter;
use App\Models\User;
use App\Models\Program;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProgramService
{
  protected Program $programModel;
  protected Chapter $chapterModel;
  
  public function __construct(Program $programModel, Chapter $chapterModel) {
    $this->programModel = $programModel;
    $this->chapterModel = $chapterModel;
  }
  public function createProgramWithChapters(array $data)
  {
    return DB::transaction(function () use ($data) {
      $program = $this->programModel->create([
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

      if (isset($data['chapters']) && is_array($data['chapters'])) {
        // 검증된 챕터 배열을 그대로 일괄 저장 (program_id는 관계에서 자동 세팅)
        $program->chapters()->createMany($data['chapters']);
      }
      
      StoreProgramEvent::dispatch($program);
      return $program;
    });
  }
  public function getProgramWithUsers()
  {
    $programs = $this->programModel->withTrashed()->get();
    return $programs;
  }
}
