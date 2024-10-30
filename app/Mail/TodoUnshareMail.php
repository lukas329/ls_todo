<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TodoUnshareMail extends Mailable
{
    use Queueable, SerializesModels;

    public $todo;

    public function __construct($todo)
    {
        $this->todo = $todo;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Someone has unshared a ToDo with you!',
        );
    }

    public function build()
    {
        return $this->view('emails.todo_unshare_notification')
            ->with(['todo' => $this->todo]);
    }
}
