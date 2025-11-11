<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Services\Admin\CourseService;
use Illuminate\Http\Request;

class MainController extends Controller
{
  protected CourseService $courseService;

  public function __construct(CourseService $courseService)
  {
      $this->courseService = $courseService;
  }
  public function index()
  {
    $courses = $this->courseService->getCourseCards();

    return view('main.index', compact('courses'));
  }
  public function show(Request $request)
  {
    $request->validate([
      'id' => 'required|integer|exists:courses,id',
    ]);
    $course = $this->courseService->getCourse($request->id);
    return view('main.course.show', compact('course'));
  }
}