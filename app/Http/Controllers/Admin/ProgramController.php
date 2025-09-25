<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\AttachmentService;
use App\Events\Admin\Mail\StoreProgramEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgramMediaRequest;
use App\Http\Requests\StoreProgramRequest;
// use App\Http\Requests\UpdateProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use App\Services\Admin\ManagerService;
use App\Services\Admin\MediaService;
use App\Services\Admin\ProgramService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class ProgramController extends Controller
{
  use AuthorizesRequests;
  protected ProgramService $programService;
  protected ManagerService $managerService;
  protected MediaService $mediaService;

  public function __construct(ProgramService $programService, ManagerService $managerService, MediaService $mediaService) 
  {
    $this->programService = $programService;
    $this->managerService = $managerService;
    $this->mediaService = $mediaService;
  }
  
  public function index() 
  {
    // 목록 권한 (관리자/강사만)
    $this->authorize('viewAny', \App\Models\Program::class);
    $programs = $this->programService->getPrograms();
    $user = auth()->user();
    if ($user && $user->role === 'manager') {
      // 강사는 자신의 프로그램만
      $programs = $programs->filter(fn($p) => $p->manager && $p->manager->user_id === $user->id);
    }
    return view('admin.program.index', compact('programs'));
  }
  
  public function create() 
  {
    $managers = $this->managerService->getManagersWithUser();
    return view('admin.program.create', compact('managers'));
  }

  public function store(StoreProgramRequest $request) 
  {
    try {
      $this->authorize('create', \App\Models\Program::class);
      $validated = $request->validated();
      $files = $request->file('attachments', []);
      unset($validated['attachments']);
      $user = auth()->user();
      $program = $this->programService->createProgramWithMedia($validated, $files, $user);

      // $this->mediaService->storeMedia($program, $mediaValidate);

      return redirect()->route('admin.program.index')->with(['status' => 0, 'message' => '프로그램이 성공적으로 생성되었습니다.']);

    } catch (Exception $e) {
      Log::error('프로그램 생성 실패: ' . $e->getMessage());
      return back()->withErrors(['status' => '1', 'message' => '프로그램 생성 중 오류가 발생했습니다.'])->withInput();
    }
  }
  
  public function edit(int $id) 
  {
    $program = $this->programService->getProgram($id);    
    $this->authorize('view', $program);
    $managers = $this->managerService->getManagersWithUser();
    return view('admin.program.edit', compact('program', 'managers'));
  }
  
  public function update(UpdateProgramRequest $request, int $id) 
  {
    try {
      $validated = $request->validated();
      $program = $this->programService->updateProgram($id, $validated);
      $this->authorize('update', $program);
      return redirect()->route('admin.program.index', $id)->with(['status' => 0, 'message' => '프로그램 정보가 성공적으로 수정되었습니다.']);
    } catch (Exception $e) {
      Log::error('프로그램 수정 실패: ' . $e->getMessage());
      return back()->withErrors(['status' => 1, 'message' => $e->getMessage()])->withInput();
    }
  }
  
  public function destroy(int $id) 
  {
    try {
      $program = $this->programService->getProgram($id);
      $this->authorize('delete', $program);
      $this->programService->deleteProgram($id);
      return redirect()->route('admin.program.index')->with(['status' => 0, 'message' => '프로그램이 성공적으로 삭제되었습니다.']);
    } catch (Exception $e) {
      Log::error('프로그램 삭제 실패: ' . $e->getMessage());
      return back()->withErrors(['status' => 1, 'message' => '프로그램 삭제 중 오류가 발생했습니다.']);
    }
  }
  public function search(Request $request)
  {
    try {
      $keyword = $request->input('keyword', '');
      $programs = $this->programService->searchPrograms($keyword);
      $user = auth()->user();
      if ($user && $user->role === 'manager') {
        $programs = $programs->filter(fn($p) => $p->manager && $p->manager->user_id === $user->id)->values();
      }
      return response()->json(['status' => 0, 'data' => $programs]);
    } catch (Exception $e) {
      Log::error('프로그램 검색 실패: ' . $e->getMessage());
      return response()->json(['status' => 1, 'message' => '프로그램 검색 중 오류가 발생했습니다.']);
    }
  }
  public function destroyMany(Request $request)
  {
    try {
      $ids = $request->input('program_ids', []);
      // 개별 권한 체크: 소유하지 않은 항목 포함 시 403
      $user = auth()->user();
      if ($user && $user->role === 'manager') {
        $programs = \App\Models\Program::whereIn('id', $ids)->get();
        foreach ($programs as $p) {
          $this->authorize('delete', $p);
        }
      }
      $deletedCount = $this->programService->deletePrograms($ids);
      return response()->json(['status' => 0, 'message' => "프로그램 $deletedCount 개가 성공적으로 삭제되었습니다."]);
    } catch (Exception $e) {
      Log::error('프로그램 여러개 삭제 실패: ' . $e->getMessage());
      return response()->json(['status' => 1, 'message' => '프로그램 여러개 삭제 중 오류가 발생했습니다.']);
    }
  }
}
