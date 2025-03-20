<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'title'            => $this->faker->sentence(6),
            'banner_image'     => 'images/events/iGZLZM8Ci6dd2rwOFBBtu3EoCPoUqRNWj7DhLfcw.jpg',
            'description'      => $this->faker->paragraph(3),
            'event_date'       => $this->faker->dateTimeBetween('now', '+1 year'),
            'address'          => $this->faker->address(),
            'latitude'         => $this->faker->latitude(),
            'longitude'        => $this->faker->longitude(),
            'status'           => $this->faker->randomElement(['complet', 'annule', 'remplissage_en_cours']),
            'max_participants' => $this->faker->numberBetween(10, 200),
            'organisateur_id'  => User::factory()->organisateur(),
        ];
    }
}
