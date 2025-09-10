<?php

namespace App\Services;
use App\Mail\StoredProgramMail;
use App\Models\Program;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailService implements ShouldQueue
{
  protected Program $program;

  public function __construct(Program $program) {
    $this->program = $program;
  }
  public function send()
  {
    // 이메일 전송 로직 구현
    $recipients = $this->program->users->pluck('email')->toArray();
    \Mail::to($recipients)->send(new StoredProgramMail($this->program));
  }
}