<?php

namespace App\Policies;

use App\Models\Chapter;
use App\Models\User;

class ChapterPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin','manager']);
    }

    public function view(User $user, Chapter $chapter): bool
    {
        if ($user->role === 'manager') {
            return $chapter->program && $chapter->program->manager && $chapter->program->manager->user_id === $user->id;
        }
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin','manager']);
    }

    public function update(User $user, Chapter $chapter): bool
    {
        if ($user->role === 'manager') {
            return $chapter->program && $chapter->program->manager && $chapter->program->manager->user_id === $user->id;
        }
        return true;
    }

    public function delete(User $user, Chapter $chapter): bool
    {
        if ($user->role === 'manager') {
            return $chapter->program && $chapter->program->manager && $chapter->program->manager->user_id === $user->id;
        }
        return true;
    }
}
