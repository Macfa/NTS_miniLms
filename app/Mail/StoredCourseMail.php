<?php

namespace App\Mail;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;


class StoredCourseMail extends Mailable
{
    use Queueable, SerializesModels;
    public Course $Course;

    /**
     * Create a new message instance.
     */
    public function __construct(Course $Course)
    {
        $this->Course = $Course;
        // 리스너를 큐에 넣으므로 주석
        // $this->onQueue('emails');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
      return new Envelope(
          from: new Address('service@yourdomain.com', '관리자'),
          // replyTo: [
          //     new Address('test@gmail.com', '관리자'),
          // ],
          subject: '프로그램 등록 승인요청',
      );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.mail.Course.store',
            with: [
                'manager' => $this->Course->user->name,
                'title' => $this->Course->name,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
            // $path = storage_path('app/public/media/Course/25/images/hzn.me.jpeg');
            // if (is_file($path)) {
            //     $image = Attachment::fromPath($path);
            //     return [$image];
            // }
            return [];
    }
}
