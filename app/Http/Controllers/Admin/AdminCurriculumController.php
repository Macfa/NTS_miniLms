<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCurriculumRequest;
use App\Services\Admin\CurriculumService;
use App\Services\Admin\CourseService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Curriculum;
use Illuminate\Support\Facades\Log;

class AdminCurriculumController extends Controller
{
  use AuthorizesRequests;
  protected CurriculumService $curriculumService;
  protected CourseService $CourseService;

  public function __construct(CurriculumService $curriculumService, CourseService $CourseService) {
    $this->curriculumService = $curriculumService;
    $this->CourseService = $CourseService;
  }
  public function index() 
  {
    $this->authorize('viewAny', Curriculum::class);
    $curriculums = $this->curriculumService->getCurriculums();
    $user = auth()->user();
    if ($user && $user->role === 'manager') {
      $curriculums = $curriculums->filter(function($c) use ($user) {
        return $c->Course && $c->Course->manager && $c->Course->manager->user_id === $user->id;
      });
    }
    // 챕터 목록을 가져와서 뷰에 전달
    return view('admin.curriculum.index', compact('curriculums'));
  }
  public function create()
  {
    $this->authorize('create', Curriculum::class);
    $Courses = $this->CourseService->getCourses();
    $user = auth()->user();
    if ($user && $user->role === 'manager') {
      $Courses = $Courses->filter(fn($p) => $p->manager && $p->manager->user_id === $user->id);
    }
    return view('admin.curriculum.create', compact('Courses'));
  }
  public function store(StoreCurriculumRequest $request)
  {
    try {
      $this->authorize('create', Curriculum::class);
      $validated = $request->validated();
      $this->curriculumService->createCurriculum($validated);

      return redirect()->route('admin.curriculum.index')->with(['status' => 0, 'message' => '챕터가 성공적으로 생성되었습니다.']);
    } catch (\Exception $e) {
      \Log::error('챕터 생성 실패: ' . $e->getMessage());
      return back()->withErrors(['status' => 1, 'message' => '챕터 생성 중 오류가 발생했습니다.'])->withInput();
    }
  }
  public function edit(int $id)
  {
    $curriculum = $this->curriculumService->getCurriculum($id);
    $this->authorize('view', $curriculum);
    $Courses = $this->CourseService->getCourses();
    $user = auth()->user();
    if ($user && $user->role === 'manager') {
      $Courses = $Courses->filter(fn($p) => $p->manager && $p->manager->user_id === $user->id);
    }
    return view('admin.curriculum.edit', compact('curriculum', 'Courses'));
  }
  public function update(StoreCurriculumRequest $request, int $id)
  {
    try {
      $validated = $request->validated();
      $curriculum = $this->curriculumService->getCurriculum($id);
      $this->authorize('update', $curriculum);
      $curriculum = $this->curriculumService->updateCurriculum($id, $validated);

      return redirect()->route('admin.curriculum.index')->with(['status' => 0, 'message' => '챕터가 성공적으로 수정되었습니다.']);
    } catch (\Exception $e) {
      Log::error('챕터 수정 실패: ' . $e->getMessage());
      return back()->withErrors(['status' => 1, 'message' => '챕터 수정 중 오류가 발생했습니다.'])->withInput();
    }
  }
  public function search(Request $request)
  {
    try {
      $keyword = $request->input('keyword');
      $curriculums = $this->curriculumService->searchCurriculums($keyword);
      return response()->json(['status' => 0, 'data' => $curriculums]);

    } catch (\Exception $e) {
      Log::error('챕터 검색 실패: ' . $e->getMessage());
      return response()->json(['status' => 1, 'message' => '챕터 검색 중 오류가 발생했습니다.'], 500);
    }
  }
  public function destroyMany(Request $request)
  {
    try {
      $curriculumIds = $request->input('curriculum_ids', []);
      $user = auth()->user();
      if ($user && $user->role === 'manager') {
        $curriculums = Curriculum::whereIn('id', $curriculumIds)->with('Course.manager')->get();
        foreach ($curriculums as $ch) {
          $this->authorize('delete', $ch);
        }
      }
      $deletedCount = $this->curriculumService->deleteCurriculums($curriculumIds);
      return response()->json(['status' => 0, 'message' => "$deletedCount 개의 챕터가 성공적으로 삭제되었습니다."]);
    } catch (\Exception $e) {
      Log::error('챕터 일괄 삭제 실패: ' . $e->getMessage());
      return response()->json(['status' => 1, 'message' => '챕터 일괄 삭제 중 오류가 발생했습니다.']);
    }
  }
}