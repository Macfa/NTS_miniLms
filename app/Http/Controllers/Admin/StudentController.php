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
      $validated = $request->validated();
      $this->studentService->createStudent($validated);

      return redirect()->route('admin.student.index')->with('success', '학생이 성공적으로 생성되었습니다.');
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
      $validated = $request->validated();
      $this->studentService->updateStudent($id, $validated);

      return redirect()->route('admin.student.index', $id)->with('success', '학생 정보가 성공적으로 수정되었습니다.');
    }
    public function destroy(int $id) 
    {
      $this->studentService->deleteStudent($id);
      return redirect()->route('admin.student.index')->with('success', '학생이 성공적으로 삭제되었습니다.');
    }
}
