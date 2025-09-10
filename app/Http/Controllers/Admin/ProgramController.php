<?php

namespace App\Http\Controllers\Admin;

use App\Events\StoreProgramEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgramRequest;
// use App\Http\Requests\UpdateProgramRequest;
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
        $programs = $this->programService->getProgramWithUsers();
        StoreProgramEvent::dispatch($programs[0]);
        return view('admin.program.index', compact('programs'));
    }

    public function create() 
    {
        $managers = $this->managerService->getManagersWithUser();
        return view('admin.program.create', compact('managers'));
    }

    public function store(StoreProgramRequest $request) 
    {
        $validated = $request->validated();
        try {
            $this->programService->createProgramWithChapters($validated);
            return redirect()->route('admin.program.index')->with('success', '프로그램이 성공적으로 생성되었습니다.');
        } catch (Exception $e) {
            Log::error('프로그램 생성 실패: ' . $e->getMessage());
            return back()->withErrors(['error' => '프로그램 생성 중 오류가 발생했습니다.'])->withInput();
        }
    }

    public function edit(int $id) 
    {
        $program = Program::with(['user', 'chapters'])->findOrFail($id);
        $managers = $this->managerService->getManagersWithUser();
        return view('admin.program.edit', compact('program', 'managers'));
    }

    public function update(UpdateProgramRequest $request, int $id) 
    {
        $validated = $request->validated();
        try {
            $this->programService->updateProgram($id, $validated);
            return redirect()->route('admin.program.index', $id)->with('success', '프로그램 정보가 성공적으로 수정되었습니다.');
        } catch (Exception $e) {
            Log::error('프로그램 수정 실패: ' . $e->getMessage());
            return back()->withErrors(['error' => '프로그램 수정 중 오류가 발생했습니다.'])->withInput();
        }
    }

    public function destroy(int $id) 
    {
        try {
            $this->programService->deleteProgram($id);
            return redirect()->route('admin.program.index')->with('success', '프로그램이 성공적으로 삭제되었습니다.');
        } catch (Exception $e) {
            Log::error('프로그램 삭제 실패: ' . $e->getMessage());
            return back()->withErrors(['error' => '프로그램 삭제 중 오류가 발생했습니다.']);
        }
    }
}
