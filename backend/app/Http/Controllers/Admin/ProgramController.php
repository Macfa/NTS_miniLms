<?php

namespace App\Http\Controllers\Admin;

use App\Events\Admin\Mail\StoreProgramEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgramRequest;
// use App\Http\Requests\UpdateProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use App\Services\Admin\ManagerService;
use App\Services\Admin\ProgramService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Program;
use Illuminate\Support\Facades\Log;
use Exception;

class ProgramController extends Controller
{
    protected ProgramService $programService;
    protected ManagerService $managerService;

    public function __construct(ProgramService $programService, ManagerService $managerService) 
    {
        $this->programService = $programService;
        $this->managerService = $managerService;
    }

    public function index() 
    {
        $programs = $this->programService->getPrograms();
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
          $validated = $request->validated();
            $this->programService->createProgram($validated);
            return redirect()->route('admin.program.index')->with(['status' => 0, 'message' => '프로그램이 성공적으로 생성되었습니다.']);
        } catch (Exception $e) {
            Log::error('프로그램 생성 실패: ' . $e->getMessage());
            return back()->withErrors(['status' => '1', 'message' => '프로그램 생성 중 오류가 발생했습니다.'])->withInput();
        }
    }

    public function edit(int $id) 
    {
        $program = $this->programService->getProgram($id);
        $managers = $this->managerService->getManagersWithUser();
        return view('admin.program.edit', compact('program', 'managers'));
    }

    public function update(UpdateProgramRequest $request, int $id) 
    {
      try {
          $validated = $request->validated();
            $this->programService->updateProgram($id, $validated);
            return redirect()->route('admin.program.index', $id)->with(['status' => 0, 'message' => '프로그램 정보가 성공적으로 수정되었습니다.']);
        } catch (Exception $e) {
            Log::error('프로그램 수정 실패: ' . $e->getMessage());
            return back()->withErrors(['status' => 1, 'message' => '프로그램 수정 중 오류가 발생했습니다.'])->withInput();
        }
    }

    public function destroy(int $id) 
    {
        try {
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
        return response()->json(['status' => 0, 'data' => $programs]);
      } catch (Exception $e) {
        Log::error('프로그램 검색 실패: ' . $e->getMessage());
        return response()->json(['status' => 1, 'message' => '프로그램 검색 중 오류가 발생했습니다.']);
      }
    }
}
