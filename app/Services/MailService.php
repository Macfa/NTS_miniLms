<?php

namespace App\Services;
use App\Mail\StoredCourseMail;
use App\Models\Course;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailService
{
  public function send(Course $Course)
  {
    // 관리자에게 강사가 등록한 프로그램의 승인을 요청
    $recipients = \App\Models\User::where('role', 'admin')->pluck('email')->all();
    Log::info('MailService send method called for Course ID: ', ['Course_id' => $Course->id]);
    if (!empty($recipients)) {
      $mailable = new StoredCourseMail($Course);
      Log::info('mailable created for Course ID: ', ['Course_id' => $Course->id]);
      Mail::to($recipients)->send($mailable);
    }
  }
}