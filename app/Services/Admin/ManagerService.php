<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Manager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManagerService
{
    /**
     * 강사 정보를 생성하고 저장합니다.
     * @param array $data
     * @return \App\Models\User
     */
    public function createManager(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            Manager::create([
                'user_id' => $user->id,
            ]);

            return $user;
        });
    }

    /**
     * 강사 정보를 수정합니다.
     *
     * @param int $id 학생의 ID
     * @param array $data 수정할 데이터
     * @return Manager 수정된 Manager 모델 인스턴스
     */
    public function updateManager(int $id, array $data): Manager
    {
        return DB::transaction(function () use ($id, $data) {
            $manager = Manager::where('user_id', $id)->firstOrFail();
            $user = $manager->user;

            $userData = [
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
            ];

            if (isset($data['password']) && $data['password'] !== null) {
                $userData['password'] = Hash::make($data['password']);
            }
            
            $user->update($userData);

            return $manager;
        });
    }

    /**
     * 강사 정보를 삭제합니다.
     *
     * @param int $id 학생의 ID
     * @return bool 삭제 성공 여부
     */ 
    public function deleteManager(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $manager = Manager::where('user_id', $id)->firstOrFail();
            $user = $manager->user;

            $manager->delete();
            $user->delete();

            return true;
        });
    }
    public function getStudentsWithUsers()
    {
      return Manager::with('user')->get();
    }
}
