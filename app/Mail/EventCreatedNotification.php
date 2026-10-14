<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventCreatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $client;

    public function __construct($event, $client)
    {
        $this->event = $event;
        $this->client = $client;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Event Created - ' . $this->event->event_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-created',
        );
    }
}