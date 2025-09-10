<?php

namespace Tests\Feature;

use App\Events\StoreProgramEvent;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class StoreProgramEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_program_event_is_dispatched()
    {
        Event::fake();

        // 유저, 매니저, 프로그램, 챕터 생성
        $user = User::factory()->create();
        $manager = \App\Models\Manager::factory()->create(['user_id' => $user->id]);
        $program = Program::factory()->create([
            'manager_id' => $manager->id,
        ]);
        $chapter = \App\Models\Chapter::factory()->create([
            'program_id' => $program->id,
            'week_days' => '0,2,4',
            'start' => now(),
            'end' => now()->addHour(),
        ]);

        // 이벤트 발생
        StoreProgramEvent::dispatch($program);

        // 이벤트가 정상적으로 발생했는지 검증
        Event::assertDispatched(StoreProgramEvent::class, function ($event) use ($program) {
            return $event->program->id === $program->id;
        });
    }
}