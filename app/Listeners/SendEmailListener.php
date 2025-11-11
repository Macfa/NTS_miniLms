<?php

namespace App\Listeners;

use App\Events\Admin\Mail\StoreCourseEvent;
use App\Services\MailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendEmailListener implements ShouldQueue
{
  protected MailService $mailService;
  public $tries = 3; // 최대 시도 횟수

    /**
     * Create the event listener.
     */
    public function __construct(MailService $mailService)
    {
        // 메일 서비스 주입
        $this->mailService = $mailService;
    }

    /**
     * Handle the event.
     */
    public function handle(StoreCourseEvent $event): void
    {
      // Log::info('SendEmailListener triggered for Course ID: ', ['Course_id' => $event->Course->id]);
      $this->mailService->send($event->Course);
    }
    public function failed(StoreCourseEvent $event, \Throwable $exception): void
    {
      // 실패 시 로깅 등 추가 처리 가능
      Log::error('SendEmailListener failed for Course ID: ', ['Course_id' => $event->Course->id, 'error' => $exception->getMessage()]);
    }
}
