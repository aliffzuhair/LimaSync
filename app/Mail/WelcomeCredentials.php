<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $plainPassword;
    public $loginUrl;

    public function __construct($user, $plainPassword)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
        $this->loginUrl = url('/login');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to LimaSync - Your Login Credentials',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-credentials',
        );
    }
}