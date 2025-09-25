<?php

namespace App\Policies;

use App\Models\Program;
use App\Models\User;

class ProgramPolicy
{
    // viewAny: 목록 조회 권한 - 관리자/강사 허용 (관리자는 Gate::before)
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin','manager']);
    }

    public function view(User $user, Program $program): bool
    {
        if ($user->role === 'manager') {
            return $program->manager && $program->manager->user_id === $user->id;
        }
        return true; // admin 은 before 로 처리
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin','manager']);
    }

    public function update(User $user, Program $program): bool
    {
        if ($user->role === 'manager') {
            return $program->manager && $program->manager->user_id === $user->id;
        }
        return true;
    }

    public function delete(User $user, Program $program): bool
    {
        if ($user->role === 'manager') {
            return $program->manager && $program->manager->user_id === $user->id;
        }
        return true;
    }
}
