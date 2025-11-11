<?php

namespace App\Services\Admin;

use App\Events\Admin\Mail\StoreCourseEvent;
use App\Domains\CourseAggregate;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CourseService
{
  protected Course $courseModel;
  protected MediaService $mediaService;
  protected CourseAggregate $CourseAggregate;

  public function __construct(Course $courseModel, MediaService $mediaService, CourseAggregate $CourseAggregate) {
    $this->courseModel = $courseModel;
    $this->mediaService = $mediaService;
    $this->CourseAggregate = $CourseAggregate;
  }
  public function createCourseWithMedia(array $data, array $files, User $user)
  {
    // 도메인 Aggregate에 위임 (트랜잭션/이벤트 포함)
    return $this->CourseAggregate->createCourseWithMedia($data, $files, $user);
  }
   public function updateCourse(int $id, array $data)
  {
    return DB::transaction(function () use ($id, $data) {
      $Course = $this->courseModel->findOrFail($id);
      $updateData = [
        'category' => $data['category'],
        'name' => $data['name'],
        'description' => $data['description'],
        // manager_id는 관리자만 변경 가능. 강사는 기존 소유자 유지
        'manager_id' => $Course->manager_id,
        'total_week' => $data['total_week'],
        'limit_count' => $data['limit_count'],
        'total_price' => $data['total_price'],
        'status' => $data['status'],
        'updated_at' => now(),
      ];

      // 관리자면 승인 상태/소유자(manager_id) 변경 허용
      if (Gate::allows('is-admin')) {
        if (isset($data['manager_id'])) {
          $updateData['manager_id'] = (int) $data['manager_id'];
        }
        if (isset($data['approval_status'])) {
        $updateData['approval_status'] = $data['approval_status'];
        }
      }
      $Course->update($updateData);
      return $Course;
    });
  }
  public function getCourses()
  {
    $Courses = $this->courseModel->with(['user'])->get();
    return $Courses;
  }
  public function getCourse(int $id)
  {
    return $this->courseModel->with(['user'])->findOrFail($id);
  }
  public function deleteCourse(int $id)
  {
    $Course = $this->courseModel->findOrFail($id);
    return $Course->delete();
  }
  public function getCourseCards()
  {
    $Courses = $this->courseModel->activeCourse()->with(['manager.user', 'media'])->get();

    return $Courses;
  }
  public function searchCourses(string $keyword)
  {
    return $this->courseModel->whereHas('manager.user', function($query) use ($keyword){
      $query->where('name', 'LIKE', "%{$keyword}%");
    })->where('name', 'LIKE', "%{$keyword}%")
    ->get();
  }
  public function deleteCourses(array $ids): int
  {
    return DB::transaction(function () use ($ids) {
      $Courses = $this->courseModel->whereIn('id', $ids)->get();
      $count = 0;
      foreach ($Courses as $Course) {
        $Course->delete();
        $count++;
      }
      return $count;
    });
  }
  public function approveCourses(array $ids, int $approval_status): int
  {
    return DB::transaction(function () use ($ids, $approval_status) {
      $Courses = $this->courseModel->whereIn('id', $ids)->get();
      $count = 0;
      foreach ($Courses as $Course) {
        $Course->approval_status = $approval_status;
        $Course->save();
        $count++;
      }
      return $count;
    });
  }
  public function rejectCourses(array $ids, int $approval_status): int
  {
    return DB::transaction(function () use ($ids, $approval_status) {
      $Courses = $this->courseModel->whereIn('id', $ids)->get();
      $count = 0;
      foreach ($Courses as $Course) {
        $Course->approval_status = $approval_status;
        $Course->save();
        $count++;
      }
      return $count;
    });
  }
}
