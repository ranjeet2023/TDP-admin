<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $body;
    public $from_email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $body, $from_email)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->from_email = $from_email;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'sendemail',
        );
    }

    public function build()
    {
            return $this->withSwiftMessage(function ($message) {
            $message->setSubject($this->subject);
            $message->setBody($this->body);
            $message->setFrom($this->from_email);
        });
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
