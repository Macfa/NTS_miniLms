<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Manager;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManagerService
{
  protected User $user;
  protected Manager $manager;
  protected MediaService $mediaService;
  
  public function __construct(User $user, Manager $manager, MediaService $mediaService)
  {
    $this->user = $user;
    $this->manager = $manager;
    $this->mediaService = $mediaService;
  }
  /**
  * 강사 검색 (이름/이메일)
  */
  public function searchManagers($keyword)
  {
    return $this->manager->with('user')
    ->whereHas('user', function ($query) use ($keyword) {
      $query->where('name', 'like', "%$keyword%")
      ->orWhere('email', 'like', "%$keyword%");
    })
    ->get();
  }
  /**
  * 강사 일괄 삭제
  */
  public function deleteManagers(array $userIds)
  {
    return \DB::transaction(function () use ($userIds) {
      $managers = $this->manager->with('user')->whereIn('user_id', $userIds)->get();
      $count = 0;
      // if(!$managers) {
      //  예외 규칙/구조가 필요
      //   return new NotFoundQueryException('삭제할 강사가 없습니다.');
      // }
      foreach ($managers as $manager) {
        $manager->delete();
        // $user = $manager->user;
        // $user->delete();
        $count++;
      }
      return $count;
    });
  }
  /**
  * 강사 정보를 생성하고 저장합니다.
  * @param array $data
  * @return \App\Models\User
  */
  public function createManagerWithMedia(array $data, array $files): User
  {
    return DB::transaction(function () use ($data, $files) {
      $user = $this->user->create([
        'name' => $data['name'],
        'email' => $data['email'],
        'status' => $data['status'],
        'role' => 'manager',
        'password' => Hash::make($data['password']),
      ]);
      
      $this->manager->create([
        'user_id' => $user->id,
      ]);
      
      $this->mediaService->storePrivateMedia($user->manager, $files);
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
  public function updateManagerWithMedia(int $id, array $data, array $files): Manager
  {
    return DB::transaction(function () use ($id, $data, $files) {
      $manager = $this->manager->with('user')->where('user_id', $id)->firstOrFail();
      $user = $manager->user;
      
      $userData = [
        'name' => $data['name'] ?? $user->name,
        'email' => $data['email'] ?? $user->email,
        'status' => $data['status'] ?? $user->email,
        'updated_at' => now(),
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
      $manager = $this->manager->with('user')->where('user_id', $id)->firstOrFail();
      $user = $manager->user;
      
      $manager->delete();
      $user->delete();
      
      // need to adding relation models ( programs, chapters ... )
      return true;
    });
  }
  public function getManagersWithUser()
  {
    return $this->manager->with('user')->get();
  }
}
