<?php

namespace App\Services;
use App\Mail\StoredProgramMail;
use App\Models\Program;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class MailService
{
  protected Program $program;

  // public function __construct(Program $program) {
  //   $this->program = $program;
  // }
  public function send(Program $program)
  {
    $this->program = $program;
    // 이메일 전송 로직 구현
    $recipients = $this->program->user()->pluck('email')->toArray();
    \Mail::to($recipients)->queue(new StoredProgramMail($this->program));
  }
}