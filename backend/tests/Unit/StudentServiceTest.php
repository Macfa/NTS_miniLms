<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Student;
use App\Services\Admin\StudentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_student_creates_user_and_student()
    {
        $service = new StudentService();

        $data = [
            'name' => '테스트학생',
            'email' => 'teststudent@example.com',
            'password' => 'password123',
        ];

        $user = $service->createStudent($data);

        $this->assertDatabaseHas('users', [
            'email' => 'teststudent@example.com',
            'name' => '테스트학생',
        ]);

        $this->assertDatabaseHas('students', [
            'user_id' => $user->id,
        ]);
    }
}