<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\storeManagerRequest;
use App\Http\Requests\UpdateManagerRequest;
use App\Services\Admin\ManagerService;
use App\Services\Admin\MediaService;
use Illuminate\Http\Request;
use App\Models\User; // User 모델 추가
use App\Models\Manager; // Manager 모델 추가

class AdminManagerController extends Controller
{
  protected ManagerService $managerService;
  protected MediaService $mediaService;

  public function __construct(ManagerService $managerService, MediaService $mediaService) 
  {
    $this->managerService = $managerService;
    $this->mediaService = $mediaService;
  }
  public function index() 
  {
    // 사용자 목록을 가져와서 뷰에 전달
    $managers = $this->managerService->getManagersWithUser();
    return view('admin.manager.index', compact('managers'));
  }
  public function create() 
  {
    return view('admin.manager.create');
  }
  public function store(storeManagerRequest $request) 
  {
    try {
      $validated = $request->validated();
      $files = $request->file('attachments', []);
      unset($validated['attachments']);
      // 만약 전역 에러 핸들링을 쓴다면 서비스를 분리하는게 좋아보임
      $this->managerService->createManagerWithMedia($validated, $files);

      return redirect()->route('admin.manager.index')->with(['status' => 0, 'message' => '학생이 성공적으로 생성되었습니다.']);
    } catch (\Exception $e) {
      \Log::error('학생 생성 실패: ' . $e->getMessage());
      return back()->withErrors(['status' => 1, 'message' => '학생 생성 중 오류가 발생했습니다.'])->withInput();
    }
  }
  public function show(int $id) 
  {
    // show는 보통 특정 학생의 상세 정보를 보여줌
    $manager = Manager::with('user')->findOrFail($id);
    return view('admin.manager.show', compact('manager'));
  }
  public function edit(int $id) 
  {
    // 수정 폼을 보여주기 위해 학생 정보 로드
    $manager = Manager::with('user')->findOrFail($id);
    return view('admin.manager.edit', compact('manager'));
  }
  public function update(UpdateManagerRequest $request, Manager $manager) 
  {
    try {
      $validated = $request->validated();
      $this->managerService->updateManager($manager->user_id, $validated);
      
      return redirect()->route('admin.manager.index')->with(['status' => 0, 'message' => '학생 정보가 성공적으로 수정되었습니다.']);
    } catch (\Exception $e) {
      \Log::error('학생 정보 수정 실패: ' . $e->getMessage());
      return back()->withErrors(['status' => 1, 'message' => '학생 정보 수정 중 오류가 발생했습니다.'])->withInput();
    }
  }
  public function destroy(int $id) 
  {
    try {
      $this->managerService->deleteManager($id);
      return redirect()->route('admin.manager.index')->with(['status' => 0, 'message' => '학생이 성공적으로 삭제되었습니다.']);
    } catch (\Exception $e) {
      \Log::error('학생 삭제 실패: ' . $e->getMessage());
      return back()->withErrors(['status' => 1, 'message' => '학생 삭제 중 오류가 발생했습니다.']);
    }
    
  }
  /**
  * 강사 검색 (이름/이메일)
  */
  public function search(Request $request)
  {
    try {
      $keyword = $request->input('keyword');
      $managers = $this->managerService->searchManagers($keyword);
      return response()->json(['status' => 0, 'message' => '', 'data' => $managers]);
    } catch (\Exception $e) {
      \Log::error('강사 검색 실패: ' . $e->getMessage());
      return response()->json(['status' => 1, 'message' => '강사 검색 중 오류가 발생했습니다.']);
    }
  }
  
  /**
  * 강사 일괄 삭제
  */
  public function destroyMany(Request $request)
  {
    try {
      $userIds = $request->input('user_ids', []);
      $deletedCount = $this->managerService->deleteManagers($userIds);
      return response()->json(['status' => 0, 'message' => " $deletedCount 명의 강사가 성공적으로 삭제되었습니다."]);
    } catch (\Exception $e) {
      \Log::error('강사 일괄 삭제 실패: ' . $e->getMessage());
      return response()->json(['status' => 1, 'message' => '강사 일괄 삭제 중 오류가 발생했습니다.']);
    }
  }
}
