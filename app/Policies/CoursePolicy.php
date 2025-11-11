<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    // viewAny: 목록 조회 권한 - 관리자/강사 허용 (관리자는 Gate::before)
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin','manager']);
    }

    public function view(User $user, Course $Course): bool
    {
        if ($user->role === 'manager') {
            return $Course->manager && $Course->manager->user_id === $user->id;
        }
        return true; // admin 은 before 로 처리
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin','manager']);
    }

    public function update(User $user, Course $Course): bool
    {
        if ($user->role === 'manager') {
            return $Course->manager && $Course->manager->user_id === $user->id;
        }
        return true;
    }

    public function delete(User $user, Course $Course): bool
    {
        if ($user->role === 'manager') {
            return $Course->manager && $Course->manager->user_id === $user->id;
        }
        return true;
    }
}
