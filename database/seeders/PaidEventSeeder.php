<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PaidEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organisateur = User::where('role', 'organisateur')->first();

        if (!$organisateur) {
            $this->command->error('Aucun organisateur trouvé dans la base de données.');
            return;
        }

        $conference = Event::create([
            'title' => 'Conférence sur l\'Intelligence Artificielle',
            'description' => 'Une conférence passionnante sur les dernières avancées en matière d\'intelligence artificielle et leurs implications pour notre futur.',
            'event_date' => Carbon::now()->addDays(30)->setHour(9)->setMinute(0),
            'status' => 'remplissage_en_cours',
            'max_participants' => 200,
            'organisateur_id' => $organisateur->id,
            'price' => 120.00,
            'is_free' => false,
            'address' => 'Centre de Conférences International, 7 Place de Fontenoy, Paris'
        ]);

        $this->command->info('Événement payant créé avec succès !');
        $this->command->info('Conférence: ' . $conference->id . ' - ' . $conference->price . ' €');
    }
}
