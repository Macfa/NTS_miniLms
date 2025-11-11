<?php

namespace App\Services\Admin;
use App\Models\Curriculum;

class CurriculumService
{
  protected Curriculum $curriculum;

  public function __construct(Curriculum $curriculum) {
    $this->curriculum = $curriculum;
  }
  public function getCurriculum(int $id)
  {
    return $this->curriculum->with(['Course'])->findOrFail($id);
  }
  public function getCurriculums()
  {
    return $this->curriculum->with(['Course'])->get();
  }
  public function createCurriculum(array $data)
  {
    return $this->curriculum->create($data);
  }
  public function updateCurriculum(int $id, array $data)
  {
    return \DB::transaction(function () use ($id, $data) {
      $curriculum = $this->curriculum->findOrFail($id);
      // dd($curriculum, $data);
      $dataArr = [
        'Course_id' => $data['Course_id'],
        'start' => $data['start'],
        'end' => $data['end'],
        'week_days' => $data['week_days'],
        'status' => $data['status'],
        'updated_at' => now(),
      ];
      // dd($dataArr);
      $curriculum->update($dataArr);
      return true;
    });
  }
  public function searchCurriculums($keyword)
  {
    return $this->curriculum->with(['Course'])
      ->whereHas('Course', function ($query) use ($keyword) {
        $query->where('name', 'like', "%$keyword%");
      })
      ->get();
  }
  public function deleteCurriculums(array $ids)
  {
    return \DB::transaction(function () use ($ids) {
      $curriculums = $this->curriculum->whereIn('id', $ids)->get();
      $count = 0;
      foreach ($curriculums as $curriculum) {
        $curriculum->delete();
        $count++;
      }
      return $count;
    });
  }
}