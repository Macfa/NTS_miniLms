<?php

namespace App\Policies;

use App\Models\Curriculum;
use App\Models\User;

class CurriculumPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin','manager']);
    }

    public function view(User $user, Curriculum $curriculum): bool
    {
        if ($user->role === 'manager') {
            return $curriculum->Course && $curriculum->Course->manager && $curriculum->Course->manager->user_id === $user->id;
        }
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin','manager']);
    }

    public function update(User $user, Curriculum $curriculum): bool
    {
        if ($user->role === 'manager') {
            return $curriculum->Course && $curriculum->Course->manager && $curriculum->Course->manager->user_id === $user->id;
        }
        return true;
    }

    public function delete(User $user, Curriculum $curriculum): bool
    {
        if ($user->role === 'manager') {
            return $curriculum->Course && $curriculum->Course->manager && $curriculum->Course->manager->user_id === $user->id;
        }
        return true;
    }
}
