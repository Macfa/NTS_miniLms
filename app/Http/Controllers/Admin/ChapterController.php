<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChapterRequest;
use App\Services\Admin\ChapterService;
use App\Services\Admin\ProgramService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChapterController extends Controller
{
  use AuthorizesRequests;
  protected ChapterService $chapterService;
  protected ProgramService $programService;

  public function __construct(ChapterService $chapterService, ProgramService $programService) {
    $this->chapterService = $chapterService;
    $this->programService = $programService;
  }
  public function index() 
  {
    $this->authorize('viewAny', \App\Models\Chapter::class);
    $chapters = $this->chapterService->getChapters();
    $user = auth()->user();
    if ($user && $user->role === 'manager') {
      $chapters = $chapters->filter(function($c) use ($user) {
        return $c->program && $c->program->manager && $c->program->manager->user_id === $user->id;
      });
    }
    // 챕터 목록을 가져와서 뷰에 전달
    return view('admin.chapter.index', compact('chapters'));
  }
  public function create()
  {
    $this->authorize('create', \App\Models\Chapter::class);
    $programs = $this->programService->getPrograms();
    $user = auth()->user();
    if ($user && $user->role === 'manager') {
      $programs = $programs->filter(fn($p) => $p->manager && $p->manager->user_id === $user->id);
    }
    return view('admin.chapter.create', compact('programs'));
  }
  public function store(StoreChapterRequest $request)
  {
    try {
      $this->authorize('create', \App\Models\Chapter::class);
      $validated = $request->validated();
      $this->chapterService->createChapter($validated);

      return redirect()->route('admin.chapter.index')->with(['status' => 0, 'message' => '챕터가 성공적으로 생성되었습니다.']);
    } catch (\Exception $e) {
      \Log::error('챕터 생성 실패: ' . $e->getMessage());
      return back()->withErrors(['status' => 1, 'message' => '챕터 생성 중 오류가 발생했습니다.'])->withInput();
    }
  }
  public function edit(int $id)
  {
    $chapter = $this->chapterService->getChapter($id);
    $this->authorize('view', $chapter);
    $programs = $this->programService->getPrograms();
    $user = auth()->user();
    if ($user && $user->role === 'manager') {
      $programs = $programs->filter(fn($p) => $p->manager && $p->manager->user_id === $user->id);
    }
    return view('admin.chapter.edit', compact('chapter', 'programs'));
  }
  public function update(StoreChapterRequest $request, int $id)
  {
    try {
      $validated = $request->validated();
      $chapter = $this->chapterService->getChapter($id);
      $this->authorize('update', $chapter);
      $chapter = $this->chapterService->updateChapter($id, $validated);

      return redirect()->route('admin.chapter.index')->with(['status' => 0, 'message' => '챕터가 성공적으로 수정되었습니다.']);
    } catch (\Exception $e) {
      \Log::error('챕터 수정 실패: ' . $e->getMessage());
      return back()->withErrors(['status' => 1, 'message' => '챕터 수정 중 오류가 발생했습니다.'])->withInput();
    }
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
  public function destroyMany(Request $request)
  {
    try {
      $chapterIds = $request->input('chapter_ids', []);
      $user = auth()->user();
      if ($user && $user->role === 'manager') {
        $chapters = \App\Models\Chapter::whereIn('id', $chapterIds)->with('program.manager')->get();
        foreach ($chapters as $ch) {
          $this->authorize('delete', $ch);
        }
      }
      $deletedCount = $this->chapterService->deleteChapters($chapterIds);
      return response()->json(['status' => 0, 'message' => "$deletedCount 개의 챕터가 성공적으로 삭제되었습니다."]);
    } catch (\Exception $e) {
      \Log::error('챕터 일괄 삭제 실패: ' . $e->getMessage());
      return response()->json(['status' => 1, 'message' => '챕터 일괄 삭제 중 오류가 발생했습니다.']);
    }
  }
}