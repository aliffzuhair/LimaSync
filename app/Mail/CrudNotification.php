<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CrudNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $action;
    public $modelType;
    public $modelName;
    public $userName;
    public $description;
    public $timestamp;

    public function __construct($action, $modelType, $modelName, $userName, $description)
    {
        $this->action = $action;
        $this->modelType = $modelType;
        $this->modelName = $modelName;
        $this->userName = $userName;
        $this->description = $description;
        $this->timestamp = now()->format('d M Y H:i:s');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[LimaSync] ' . ucfirst($this->action) . ' - ' . $this->modelType,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.crud-notification',
        );
    }
}