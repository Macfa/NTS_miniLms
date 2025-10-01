<?php

namespace Tests\Feature;

use App\Events\Admin\Mail\StoreProgramEvent;
use App\Mail\StoredProgramMail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Manager;
use App\Models\User;
use App\Models\Program;
use App\Models\Media;

class ProgramControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_store_program_dispatches_event()
    {
        Event::fake();

        $user = User::factory()->create(['role' => 'manager']);
        $manager = Manager::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $data = [
            'category' => 'category1',
            'name' => 'Test Program',
            'description' => 'This is a test program.',
            'manager_id' => $manager->id,
            'total_week' => 4,
            'limit_count' => 20,
            'total_price' => 100000,
            'status' => 1,
        ];

        $response = $this->post(route('admin.program.store'), $data);
        $response->assertRedirect(route('admin.program.index'));

        $this->assertDatabaseHas('programs', [
            'name' => 'Test Program',
            'manager_id' => $manager->id,
            'approval_status' => 0,
        ]);

        Event::assertDispatched(StoreProgramEvent::class, function ($event) use ($data) {
            return $event->program->name === $data['name'];
        });
    }

    public function test_store_program_queues_mail()
    {
        Mail::fake();

        $user = User::factory()->create(['role' => 'manager']);
        $manager = Manager::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $data = [
            'category' => 'category1',
            'name' => 'Test Program',
            'description' => 'This is a test program.',
            'manager_id' => $manager->id,
            'total_week' => 4,
            'limit_count' => 20,
            'total_price' => 100000,
            'status' => 1,
        ];

        $response = $this->post(route('admin.program.store'), $data);
        $response->assertRedirect(route('admin.program.index'));

        $this->assertDatabaseHas('programs', [
            'name' => 'Test Program',
            'manager_id' => $manager->id,
            'approval_status' => 0,
        ]);

        Mail::assertSent(StoredProgramMail::class, function ($mailable) use ($data) {
            return $mailable->program->name === $data['name'];
        });
    }
}