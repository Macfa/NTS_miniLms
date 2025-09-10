<?php

namespace App\Services;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailService implements ShouldQueue
{
  public function send()
  {
    // 이메일 전송 로직 구현
  }
}