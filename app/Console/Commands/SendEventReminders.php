<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventReminderMail;

class SendEventReminders extends Command
{
    protected $signature = 'events:send-reminders';
    protected $description = 'Envoyer des rappels 24h avant l\'événement aux participants';

    public function handle()
    {
        $now = Carbon::now();

        // Sélectionne tous les événements dont la date de début est entre maintenant et dans 24 heures.
        $events = Event::with('clients')
            ->where('event_date', '>', $now)
            ->where('event_date', '<=', $now->copy()->addDay())
            ->get();

        $this->info('Événements trouvés : ' . $events->count());

        foreach ($events as $event) {
            $this->info('Traitement de l\'événement : ' . $event->title . ' (ID: ' . $event->id . ')');
            $clientCount = $event->clients->count();
            $this->info('Nombre de participants : ' . $clientCount);

            if ($clientCount > 0) {
                foreach ($event->clients as $client) {
                    $this->info('Envoi d\'un email à : ' . $client->email);
                    try {
                        Mail::to($client->email)->send(new EventReminderMail($event, $client));
                    } catch (\Exception $e) {
                        $this->error('Erreur lors de l\'envoi de l\'email à ' . $client->email . ' : ' . $e->getMessage());
                    }
                }
            } else {
                $this->info('Aucun participant pour cet événement.');
            }
        }

        $this->info('Rappels envoyés pour les événements dans les 24h.');
        return 0;
    }
}
