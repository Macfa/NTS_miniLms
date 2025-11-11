<?php

namespace Tests\Feature;

use App\Events\Admin\Mail\StoreCourseEvent;
use App\Mail\StoredCourseMail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Manager;
use App\Models\User;
use App\Models\Course;
use App\Models\Media;

class AdminCourseControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_store_Course_dispatches_event()
    {
        Event::fake();

        $user = User::factory()->create(['role' => 'manager']);
        $manager = Manager::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $data = [
            'category' => 'category1',
            'name' => 'Test Course',
            'description' => 'This is a test Course.',
            'manager_id' => $manager->id,
            'total_week' => 4,
            'limit_count' => 20,
            'total_price' => 100000,
            'status' => 1,
        ];

        $response = $this->post(route('admin.Course.store'), $data);
        $response->assertRedirect(route('admin.Course.index'));

        $this->assertDatabaseHas('Courses', [
            'name' => 'Test Course',
            'manager_id' => $manager->id,
            'approval_status' => 0,
        ]);

        Event::assertDispatched(StoreCourseEvent::class, function ($event) use ($data) {
            return $event->Course->name === $data['name'];
        });
    }

    public function test_store_Course_queues_mail()
    {
        Mail::fake();

        $user = User::factory()->create(['role' => 'manager']);
        $manager = Manager::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $data = [
            'category' => 'category1',
            'name' => 'Test Course',
            'description' => 'This is a test Course.',
            'manager_id' => $manager->id,
            'total_week' => 4,
            'limit_count' => 20,
            'total_price' => 100000,
            'status' => 1,
        ];

        $response = $this->post(route('admin.Course.store'), $data);
        $response->assertRedirect(route('admin.Course.index'));

        $this->assertDatabaseHas('Courses', [
            'name' => 'Test Course',
            'manager_id' => $manager->id,
            'approval_status' => 0,
        ]);

        Mail::assertSent(StoredCourseMail::class, function ($mailable) use ($data) {
            return $mailable->Course->name === $data['name'];
        });
    }
}