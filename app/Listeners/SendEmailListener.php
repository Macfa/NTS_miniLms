<?php

namespace App\Listeners;

use App\Events\Admin\Mail\StoreProgramEvent;
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
    public function handle(StoreProgramEvent $event): void
    {
      // Log::info('SendEmailListener triggered for Program ID: ', ['program_id' => $event->program->id]);
      $this->mailService->send($event->program);
    }
    public function failed(StoreProgramEvent $event, \Throwable $exception): void
    {
      // 실패 시 로깅 등 추가 처리 가능
      Log::error('SendEmailListener failed for Program ID: ', ['program_id' => $event->program->id, 'error' => $exception->getMessage()]);
    }
}
