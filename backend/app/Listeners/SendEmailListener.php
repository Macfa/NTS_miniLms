<?php

namespace App\Listeners;

use App\Events\Admin\Mail\StoreProgramEvent;
use App\Models\Program;
use App\Services\MailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailListener
{
  protected MailService $mailService;
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
      $this->mailService->send($event->program);
    }
}
