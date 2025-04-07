<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateTestEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-test-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crée un événement de test qui aura lieu dans les prochaines 24 heures';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Trouver un organisateur
        $organisateur = User::where('role', 'organisateur')->first();

        if (!$organisateur) {
            $this->error('Aucun organisateur trouvé dans la base de données.');
            return 1;
        }

        // Créer un événement qui aura lieu dans 12 heures
        $event = Event::create([
            'title' => 'Événement de test pour les rappels',
            'description' => 'Cet événement est créé pour tester la fonctionnalité de rappel',
            'event_date' => Carbon::now()->addHours(12),
            'status' => 'remplissage_en_cours',
            'max_participants' => 10,
            'organisateur_id' => $organisateur->id
        ]);

        $this->info("Événement créé avec ID: {$event->id}");

        // Trouver un client
        $client = User::where('role', 'client')->first();

        if (!$client) {
            $this->error('Aucun client trouvé dans la base de données.');
            return 1;
        }

        // Inscrire le client à l'événement
        $event->clients()->attach($client->id);

        $this->info("Le client {$client->name} a été inscrit à l'événement.");
        $this->info("Exécutez 'php artisan events:send-reminders' pour tester l'envoi des rappels.");

        return 0;
    }
}
