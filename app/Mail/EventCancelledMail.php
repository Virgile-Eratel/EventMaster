<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventCancelledMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $event;
    public $client;

    public function __construct(Event $event, User $client)
    {
        $this->event = $event;
        $this->client = $client;
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Annulation de l\'événement : ' . $this->event->title)
            ->markdown('emails.event_cancelled');
    }
}
