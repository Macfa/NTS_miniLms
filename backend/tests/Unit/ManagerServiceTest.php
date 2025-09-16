<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Manager;
use App\Services\Admin\ManagerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagerServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_manager_creates_user_and_manager()
    {
        $service = new ManagerService(new Manager());

        $data = [
            'name' => '테스트강사',
            'email' => 'testmanager@example.com',
            'password' => 'password123',
        ];

        $user = $service->createManager($data);

        $this->assertDatabaseHas('users', [
            'email' => 'testmanager@example.com',
            'name' => '테스트강사',
        ]);

        $this->assertDatabaseHas('managers', [
            'user_id' => $user->id,
        ]);
    }
}
