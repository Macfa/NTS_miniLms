<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Services\Admin\StudentService;
use Illuminate\Http\Request;
use App\Models\User; // User 모델 추가
use App\Models\Student; // Student 모델 추가

class StudentController extends Controller
{
    protected StudentService $studentService;

    public function __construct(StudentService $studentService) 
    {
      $this->studentService = $studentService;
    }
    public function index() 
    {
      // 사용자 목록을 가져와서 뷰에 전달
      $students = $this->studentService->getStudentsWithUsers();
      return view('admin.student.index', compact('students'));
    }
    public function create() 
    {
      return view('admin.student.create');
    }
    public function store(StoreStudentRequest $request) 
    {
      try {
        $validated = $request->validated();
        $this->studentService->createStudent($validated);
  
        return redirect()->route('admin.student.index')->with(['status' => 0, 'message' => '학생이 성공적으로 생성되었습니다.']);
      } catch (\Exception $e) {
        \Log::error('학생 생성 실패: ' . $e->getMessage());
        return back()->withErrors(['status' => 1, 'message' => '학생 생성 중 오류가 발생했습니다.'])->withInput();
      }
    }
    public function show(int $id) 
    {
      // show는 보통 특정 학생의 상세 정보를 보여줌
      $student = Student::with('user')->findOrFail($id);
      return view('admin.student.show', compact('student'));
    }
    public function edit(int $id) 
    {
      // 수정 폼을 보여주기 위해 학생 정보 로드
      $student = Student::with('user')->findOrFail($id);
      return view('admin.student.edit', compact('student'));
    }
    public function update(UpdateStudentRequest $request, int $id) 
    {
      try {
        $validated = $request->validated();
        $this->studentService->updateStudent($id, $validated);

        return redirect()->route('admin.student.index', $id)->with(['status' => 0, 'message' => '학생 정보가 성공적으로 수정되었습니다.']);
      } catch (\Exception $e) {
        \Log::error('학생 수정 실패: ' . $e->getMessage());
        return back()->withErrors(['status' => 1, 'message' => '학생 수정 중 오류가 발생했습니다.'])->withInput();
      }
    }
    /**
     * 단일 학생 삭제 (기존 destroy)
     */
    public function destroy(int $id)
    {
      try {
        $this->studentService->deleteStudent($id);
        return redirect()->route('admin.student.index')->with(['status' => 0, 'message' => '학생이 성공적으로 삭제되었습니다.']);
      } catch (\Exception $e) {
        \Log::error('학생 삭제 실패: ' . $e->getMessage());
        return back()->withErrors(['status' => 1, 'message' => '학생 삭제 중 오류가 발생했습니다.']);
      }
    }

    /**
     * 선택된 여러 학생을 삭제 (POST /student/destroy-many)
     */
    public function destroyMany(Request $request)
    {
      try {
        $ids = $request->input('user_ids', []);
        $deletedCount = $this->studentService->deleteStudents($ids);
        return response()->json(['status' => 0, 'message' => "학생 $deletedCount 명이 성공적으로 삭제되었습니다."]);
      } catch (\Exception $e) {
        \Log::error('학생 여러명 삭제 실패: ' . $e->getMessage());
        return response()->json(['status' => 1, 'message' => '학생 여러명 삭제 중 오류가 발생했습니다.']);
      }
    }
  public function search(Request $request)
  {
    try {
      $keyword = $request->input('keyword');
      $students = $this->studentService->searchStudents($keyword);
      return response()->json(['status' => 0, 'message' => '검색 성공', 'data' => $students]);
    } catch (\Exception $e) {
      \Log::error('학생 검색 실패: ' . $e->getMessage());
      return response()->json(['status' => 1, 'message' => '학생 검색 중 오류가 발생했습니다.']);
    }
  }
}
