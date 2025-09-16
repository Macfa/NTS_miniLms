<?php

namespace App\Services\Admin;
use App\Models\Chapter;

class ChapterService
{
  protected Chapter $chapter;

  public function __construct(Chapter $chapter) {
    $this->chapter = $chapter;
  }
  public function getChapters()
  {
    return $this->chapter->with(['program'])->get();
  }
  public function searchChapters($keyword)
  {
    return $this->chapter->with(['program'])
      ->whereHas('program', function ($query) use ($keyword) {
        $query->where('name', 'like', "%$keyword%");
      })
      ->get();
  }
}