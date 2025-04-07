<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Event::factory()->count(20)->create();

        User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'name'  => 'Admin1',
            'password' => Hash::make('admin'),
        ]);

        User::factory()->organisateur()->create([
            'email' => 'organisateur@example.com',
            'name'  => 'Organisateur1'
        ]);

        User::factory()->client()->create([
            'email' => 'marty.virgile@gmail.com',
            'name'  => 'Client1'
        ]);

        // Exécuter le seeder d'événements payants
        $this->call([
            PaidEventSeeder::class,
        ]);
    }
}
