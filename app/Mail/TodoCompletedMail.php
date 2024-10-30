<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TodoCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $todo;
    public function __construct($todo)
    {
        $this->todo = $todo;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Todo has been completed!',
        );
    }

    public function build()
    {
        return $this->view('emails.todo_completed_notification')
            ->with(['todo' => $this->todo]);
    }
}
