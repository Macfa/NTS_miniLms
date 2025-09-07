<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\storeManagerRequest;
use App\Http\Requests\UpdateManagerRequest;
use App\Services\Admin\ManagerService;
use Illuminate\Http\Request;
use App\Models\User; // User 모델 추가
use App\Models\Manager; // Manager 모델 추가

class ManagerController extends Controller
{
    protected ManagerService $managerService;

    public function __construct(ManagerService $managerService) 
    {
      $this->managerService = $managerService;
    }
    public function index() 
    {
      // 사용자 목록을 가져와서 뷰에 전달
      $managers = $this->managerService->getStudentsWithUsers();
      return view('admin.manager.index', compact('managers'));
    }
    public function create() 
    {
      return view('admin.manager.create');
    }
    public function store(storeManagerRequest $request) 
    {
      $validated = $request->validated();
      $this->managerService->createManager($validated);

      return redirect()->route('admin.manager.index')->with('success', '학생이 성공적으로 생성되었습니다.');
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
      $validated = $request->validated();
      $this->managerService->updateManager($manager->user_id, $validated);

      return redirect()->route('admin.manager.index')->with('success', '학생 정보가 성공적으로 수정되었습니다.');
    }
    public function destroy(int $id) 
    {
      $this->managerService->deleteManager($id);
      return redirect()->route('admin.manager.index')->with('success', '학생이 성공적으로 삭제되었습니다.');
    }
}
