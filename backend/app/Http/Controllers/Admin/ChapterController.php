<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Services\Admin\ChapterService;
use App\Services\Admin\ProgramService;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
  protected ChapterService $chapterService;
  protected ProgramService $programService;

  public function __construct(ChapterService $chapterService, ProgramService $programService) {
    $this->chapterService = $chapterService;
    $this->programService = $programService;
  }
  public function index() 
  {
    $chapters = $this->chapterService->getChapters();
    // 챕터 목록을 가져와서 뷰에 전달
    return view('admin.chapter.index', compact('chapters'));
  }
  public function create()
  {
    $programs = $this->programService->getPrograms();
    return view('admin.chapter.create', compact('programs'));
  }
  public function search(Request $request)
  {
    try {
      $keyword = $request->input('keyword');
      $chapters = $this->chapterService->searchChapters($keyword);
      return response()->json(['status' => 0, 'data' => $chapters]);

    } catch (\Exception $e) {
      \Log::error('챕터 검색 실패: ' . $e->getMessage());
      return response()->json(['status' => 1, 'message' => '챕터 검색 중 오류가 발생했습니다.'], 500);
    }
  }
}