<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TodoNotificationMail extends Mailable
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
            subject: 'Someone has shared a ToDo with you!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->view('emails.todo_notification')
            ->with(['todo' => $this->todo]);
    }
}
