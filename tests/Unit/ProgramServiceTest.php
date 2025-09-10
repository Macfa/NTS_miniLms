<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;

use App\Services\Admin\ProgramService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Program;
use App\Models\Chapter;

class ProgramServiceTest extends TestCase
{
    protected ProgramService $programService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->programService = new ProgramService(new Program(), new Chapter());
    }
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }
    public function test_create_program_and_chapters()
    {
        $data = [
            'category' => 'program',
            'name' => '테스트프로그램',
            'description' => '테스트 프로그램 설명',
            'manager_id' => '1',
            'total_week' => '4',
            'limit_count' => '10',
            'total_price' => 100000,
            'status' => '1',
            'chapters' => [
                [
                    'start' => '2024-07-01 10:00:00',
                    'end' => '2024-07-01 12:00:00',
                    'week_days' => 'Mon,Wed,Fri',
                    'status' => 'scheduled',
                ],
                [
                    'start' => '2024-07-03 10:00:00',
                    'end' => '2024-07-03 12:00:00',
                    'week_days' => 'Tue,Thu',
                    'status' => 'scheduled',
                ],
            ],
        ];

        $program = $this->programService->createProgramWithChapters($data);

        $this->assertDatabaseHas('programs', [
          'category' => $data['category'],
          'name' => $data['name'],
          'description' => $data['description'],
          'manager_id' => $data['manager_id'],
          'total_week' => $data['total_week'],
          'limit_count' => $data['limit_count'],
          'total_price' => $data['total_price'],
          'status' => $data['status'],
        ]);

        foreach ($data['chapters'] as $chapter) {
            $this->assertDatabaseHas('chapters', [
                'program_id' => $program->id,
                'start' => $chapter['start'],
                'end' => $chapter['end'],
                'week_days' => $chapter['week_days'],
                'status' => $chapter['status'],
            ]);
        }
    }
}
