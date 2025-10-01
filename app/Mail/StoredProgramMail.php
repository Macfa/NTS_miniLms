<?php

namespace App\Mail;

use App\Models\Program;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;


class StoredProgramMail extends Mailable
{
    use Queueable, SerializesModels;
    public Program $program;

    /**
     * Create a new message instance.
     */
    public function __construct(Program $program)
    {
        $this->program = $program;
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
          replyTo: [
              new Address('kombo67688@gmail.com', '관리자'),
          ],
          subject: '프로그램 등록 승인요청',
      );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.mail.program.store',
            with: [
                'manager' => $this->program->user->name,
                'title' => $this->program->name,
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
            $path = storage_path('app/public/media/program/25/images/hzn.me.jpeg');
            if (is_file($path)) {
                $image = Attachment::fromPath($path);
                return [$image];
            }
            return [];
    }
}
