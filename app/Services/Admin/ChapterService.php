<?php

namespace App\Services\Admin;
use App\Models\Chapter;

class ChapterService
{
  protected Chapter $chapter;

  public function __construct(Chapter $chapter) {
    $this->chapter = $chapter;
  }
  public function getChapter(int $id)
  {
    return $this->chapter->with(['program'])->findOrFail($id);
  }
  public function getChapters()
  {
    return $this->chapter->with(['program'])->get();
  }
  public function createChapter(array $data)
  {
    // dd($data);
    return $this->chapter->create($data);
  }
  public function updateChapter(int $id, array $data)
  {
    return \DB::transaction(function () use ($id, $data) {
      $chapter = $this->chapter->findOrFail($id);
      // dd($chapter, $data);
      $dataArr = [
        'program_id' => $data['program_id'],
        'start' => $data['start'],
        'end' => $data['end'],
        'week_days' => $data['week_days'],
        'status' => $data['status'],
        'updated_at' => now(),
      ];
      // dd($dataArr);
      $chapter->update($dataArr);
      return true;
    });
  }
  public function searchChapters($keyword)
  {
    return $this->chapter->with(['program'])
      ->whereHas('program', function ($query) use ($keyword) {
        $query->where('name', 'like', "%$keyword%");
      })
      ->get();
  }
  public function deleteChapters(array $ids)
  {
    return \DB::transaction(function () use ($ids) {
      $chapters = $this->chapter->whereIn('id', $ids)->get();
      $count = 0;
      foreach ($chapters as $chapter) {
        $chapter->delete();
        $count++;
      }
      return $count;
    });
  }
}