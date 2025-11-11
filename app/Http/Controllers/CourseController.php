<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
  public function __construct() {
    
  }
  public function show(Course $course)
  {
      $course->load([
        'tags',           // 전역 태그 피벗
        'curriculums',    // 커리큘럼 목록
        'manager.user',   // 강사 -> 사용자 프로필
      ]);

      return view('main.course.show', compact('course'));
  }
}
