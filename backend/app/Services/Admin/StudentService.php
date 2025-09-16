<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentService
{
        protected User $user;
        protected Student $student;

        public function __construct(User $user, Student $student)
        {
                $this->user = $user;
                $this->student = $student;
        }
    /**
     * 학생 정보를 수정합니다.
     *
     * @param int $id 학생의 ID
     * @param array $data 수정할 데이터
     * @return Student 수정된 Student 모델 인스턴스
     */
    public function updateStudent(int $id, array $data): Student
    {
        return DB::transaction(function () use ($id, $data) {
                                $student = $this->student->where('user_id', $id)->with('user')->firstOrFail();
            $user = $student->user;

            $userData = [
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
                'status' => $data['status'] ?? $user->status,
                'updated_at' => now(),
            ];

            if (isset($data['password']) && $data['password'] !== null) {
                $userData['password'] = Hash::make($data['password']);
            }

            $user->update($userData);

            return $student;
        });
    }

    /**
     * 학생 및 관련된 사용자 정보를 삭제합니다.
     *
     * @param int $id 학생의 ID
     * @return bool 삭제 성공 여부
     */
    public function deleteStudent(int $id): bool
    {
        return DB::transaction(function () use ($id) {
                                $student = $this->student->with('user')->where('user_id', $id)->firstOrFail();
            $user = $student->user;

            $student->delete();
            $user->delete();

            return true;
        });
    }
    /**
     * 여러 학생 및 관련된 사용자 정보를 삭제합니다.
     *
     * @param array $ids 학생의 user_id 배열
     * @return int 삭제된 학생 수
     */
    public function deleteStudents(array $ids): int
    {
      return DB::transaction(function () use ($ids) {
                            $students = $this->student->with('user')->whereIn('user_id', $ids)->get();
          $count = 0;
          foreach ($students as $student) {
              $user = $student->user;
              $student->delete();
              $user->delete();
              $count++;
          }
          return $count;
      });
    }
    public function getStudentsWithUsers()
    {
                        return $this->student->with('user')->get();
    }
        public function searchStudents($keyword)
    {
                        return $this->student->with('user')->whereHas('user', function ($query) use ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%")
            ->orWhere('email', 'LIKE', "%{$keyword}%");
        })->get();
    }
    /**
     * 학생 정보를 생성하고 저장합니다.
     *
     * @param array $data 학생 생성에 필요한 데이터
     * @return User 생성된 User 모델 인스턴스
     */
    public function createStudent(array $data): User
    {
        return DB::transaction(function () use ($data) {
                                $user = $this->user->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'status' => $data['status'],
                'role' => 'student',
                'password' => Hash::make($data['password']),
            ]);

                                $this->student->create([
                'user_id' => $user->id,
            ]);

            return $user;
        });
    }

}
