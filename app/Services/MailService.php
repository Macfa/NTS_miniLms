<?php

namespace App\Services;
use App\Mail\StoredProgramMail;
use App\Models\Program;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailService
{
  public function send(Program $program)
  {
    // 관리자에게 강사가 등록한 프로그램의 승인을 요청
    $recipients = \App\Models\User::where('role', 'admin')->pluck('email')->all();
    Log::info('MailService send method called for Program ID: ', ['program_id' => $program->id]);
    if (!empty($recipients)) {
      $mailable = new StoredProgramMail($program);
      Log::info('mailable created for Program ID: ', ['program_id' => $program->id]);
      Mail::to($recipients)->send($mailable);
    }
  }
}