<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Manager;
use App\Models\User;
use App\Models\Program;
use App\Models\Media;

class ProgramControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_store_program_row()
    {
      $user = User::factory()->create(['role' => 'manager']);
      $this->actingAs($user);

      // 강사 생성 검증
      $this->assertDatabaseHas('managers', [
        'user_id' => $user->id,
      ]);

      $data = [
        'category' => 'category1',
        'name' => 'Test Program',
        'description' => 'This is a test program.',
        'manager_id' => $user->manager()->id,
        'total_week' => 4,
        'limit_count' => 20,
        'total_price' => 100000,
        'status' => 1,
      ];

      $response = $this->post(route('admin.program.store'), $data);
      $response->assertRedirect(route('admin.program.index'));

      $this->assertDatabaseHas('programs', [
        'name' => 'Test Program',
        'manager_id' => $user->manager()->id,
        'approval_status' => 2, // 매니저가 생성했으므로 승인 대기 상태여야 함
      ]);

    }
  }