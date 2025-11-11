<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\AttachmentService;
use App\Events\Admin\Mail\StoreCourseEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseMediaRequest;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Services\Admin\ManagerService;
use App\Services\Admin\MediaService;
use App\Services\Admin\CourseService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Course;
use Exception;

class AdminCourseController extends Controller
{
  use AuthorizesRequests;
  protected CourseService $courseService;
  protected ManagerService $managerService;
  protected MediaService $mediaService;

  public function __construct(CourseService $courseService, ManagerService $managerService, MediaService $mediaService) 
  {
    $this->courseService = $courseService;
    $this->managerService = $managerService;
    $this->mediaService = $mediaService;
  }
  
  public function index() 
  {
    // 목록 권한 (관리자/강사만)
    $this->authorize('viewAny', Course::class);
    $courses = $this->courseService->getCourses();
    $user = auth()->user();
    if ($user && $user->role === 'manager') {
      // 강사는 자신의 프로그램만
      $courses = $courses->filter(fn($p) => $p->manager && $p->manager->user_id === $user->id);
    }
    return view('admin.course.index', compact('courses'));
  }
  
  public function create() 
  {
    $managers = $this->managerService->getManagersWithUser();
    
    return view('admin.course.create', compact('managers'));
  }

  public function store(StoreCourseRequest $request) 
  {
    try {
      $this->authorize('create', Course::class);
      $validated = $request->validated();
      $files = $request->file('attachments', []);
      unset($validated['attachments']);
      $user = auth()->user();
      $course = $this->courseService->createCourseWithMedia($validated, $files, $user);

      return redirect()->route('admin.course.index')->with(['status' => 0, 'message' => '프로그램이 성공적으로 생성되었습니다.']);

    } catch (Exception $e) {
      Log::error('프로그램 생성 실패: ' . $e->getMessage());
      return back()->withErrors(['status' => '1', 'message' => '프로그램 생성 중 오류가 발생했습니다.'])->withInput();
    }
  }
  
  public function edit(int $id) 
  {
    $course = $this->courseService->getCourse($id);    
    $this->authorize('view', $course);
    $managers = $this->managerService->getManagersWithUser();

    return view('admin.course.edit', compact('course', 'managers'));
  }
  
  public function update(UpdateCourseRequest $request, int $id) 
  {
    try {
      $validated = $request->validated();
      $course = $this->courseService->updateCourse($id, $validated);
      $this->authorize('update', $course);

      return redirect()->route('admin.course.index', $id)->with(['status' => 0, 'message' => '프로그램 정보가 성공적으로 수정되었습니다.']);

    } catch (Exception $e) {
      Log::error('프로그램 수정 실패: ' . $e->getMessage());
      return back()->withErrors(['status' => 1, 'message' => $e->getMessage()])->withInput();
    }
  }
  
  public function destroy(int $id) 
  {
    try {
      $course = $this->courseService->getCourse($id);
      $this->authorize('delete', $course);
      $this->courseService->deleteCourse($id);
      return redirect()->route('admin.course.index')->with(['status' => 0, 'message' => '프로그램이 성공적으로 삭제되었습니다.']);
    } catch (Exception $e) {
      Log::error('프로그램 삭제 실패: ' . $e->getMessage());
      return back()->withErrors(['status' => 1, 'message' => '프로그램 삭제 중 오류가 발생했습니다.']);
    }
  }
  public function search(Request $request)
  {
    try {
      $keyword = $request->input('keyword', '');
      $courses = $this->courseService->searchCourses($keyword);
      $user = auth()->user();
      if ($user && $user->role === 'manager') {
        $courses = $courses->filter(fn($p) => $p->manager && $p->manager->user_id === $user->id)->values();
      }
      return response()->json(['status' => 0, 'data' => $courses]);
    } catch (Exception $e) {
      Log::error('프로그램 검색 실패: ' . $e->getMessage());
      return response()->json(['status' => 1, 'message' => '프로그램 검색 중 오류가 발생했습니다.']);
    }
  }
  public function destroyMany(Request $request)
  {
    try {
      $ids = $request->input('course_ids', []);
      // 개별 권한 체크: 소유하지 않은 항목 포함 시 403
      $user = auth()->user();
      if ($user && $user->role === 'manager') {
        $courses = \App\Models\Course::whereIn('id', $ids)->get();
        foreach ($courses as $p) {
          $this->authorize('delete', $p);
        }
      }
      $deletedCount = $this->courseService->deleteCourses($ids);
      return response()->json(['status' => 0, 'message' => "프로그램 $deletedCount 개가 성공적으로 삭제되었습니다."]);
    } catch (Exception $e) {
      Log::error('프로그램 여러개 삭제 실패: ' . $e->getMessage());
      return response()->json(['status' => 1, 'message' => '프로그램 여러개 삭제 중 오류가 발생했습니다.']);
    }
  }
  public function approvalMany(Request $request)
  {
    try {
      $ids = $request->input('course_ids', []);
      $approval_status = $request->input('approval_status', 1);
      $updatedCount = $this->courseService->approveCourses($ids, $approval_status);
      return response()->json(['status' => 0, 'message' => "프로그램 $updatedCount 개의 승인 상태가 성공적으로 변경되었습니다."]);
    } catch (Exception $e) {
      Log::error('프로그램 여러개 승인 상태 변경 실패: ' . $e->getMessage());
      return response()->json(['status' => 1, 'message' => '프로그램 여러개 승인 상태 변경 중 오류가 발생했습니다.']);
    }
  }
  public function rejectionMany(Request $request)
  {
    try {
      $ids = $request->input('course_ids', []);
      $approval_status = $request->input('approval_status', -1);
      $updatedCount = $this->courseService->rejectCourses($ids, $approval_status);
      return response()->json(['status' => 0, 'message' => "프로그램 $updatedCount 개의 승인 상태가 성공적으로 변경되었습니다."]);
    } catch (Exception $e) {
      Log::error('프로그램 여러개 승인 상태 변경 실패: ' . $e->getMessage());
      return response()->json(['status' => 1, 'message' => '프로그램 여러개 승인 상태 변경 중 오류가 발생했습니다.']);
    }
  }
}
