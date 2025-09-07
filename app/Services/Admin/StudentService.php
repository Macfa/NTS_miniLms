<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentService
{
    /**
     * 학생 정보를 생성하고 저장합니다.
     *
     * @param array $data 학생 생성에 필요한 데이터
     * @return User 생성된 User 모델 인스턴스
     */
    public function createStudent(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            Student::create([
                'user_id' => $user->id,
            ]);

            return $user;
        });
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
            $student = Student::where('user_id', $id)->firstOrFail();
            $user = $student->user;

            $userData = [
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
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
            $student = Student::where('user_id', $id)->firstOrFail();
            $user = $student->user;

            $student->delete();
            $user->delete();

            return true;
        });
    }
    public function getStudentsWithUsers()
    {
      return Student::with('user')->get();
    }
}
