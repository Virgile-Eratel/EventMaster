<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_register_to_free_event()
    {
        $client = User::factory()->create([
            'role' => Role::Client->value
        ]);

        $event = Event::factory()->create([
            'status' => 'remplissage_en_cours',
            'is_free' => true,
            'price' => 0.00
        ]);

        $response = $this->actingAs($client)
                         ->get(route('payment.checkout', $event));

        $response->assertRedirect();

        $this->assertDatabaseHas('event_user', [
            'event_id' => $event->id,
            'user_id' => $client->id
        ]);
    }

    public function test_client_cannot_register_to_full_event()
    {
        $client = User::factory()->create([
            'role' => Role::Client->value
        ]);

        $event = Event::factory()->create([
            'status' => 'complet',
            'is_free' => true
        ]);

        $response = $this->actingAs($client)
                         ->post(route('event.register', $event));

        $response->assertRedirect();

        $this->assertDatabaseMissing('event_user', [
            'event_id' => $event->id,
            'user_id' => $client->id
        ]);
    }

    public function test_client_can_unregister_from_event()
    {
        $client = User::factory()->create([
            'role' => Role::Client->value
        ]);

        $event = Event::factory()->create();

        $event->clients()->attach($client->id);

        $response = $this->actingAs($client)
                         ->delete(route('event.unregister', $event));

        $response->assertRedirect();

        $this->assertDatabaseMissing('event_user', [
            'event_id' => $event->id,
            'user_id' => $client->id
        ]);
    }
}
