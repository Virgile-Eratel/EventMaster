<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_organisateur_can_create_event()
    {
        $organisateur = User::factory()->create([
            'role' => Role::Organisateur->value
        ]);

        $eventData = [
            'title' => 'New Test Event',
            'description' => 'This is a test event description',
            'event_date' => now()->addDays(7)->format('Y-m-d H:i:s'),
            'address' => '123 Test Street',
            'status' => 'remplissage_en_cours',
            'max_participants' => 50,
            'is_free' => true,
            'price' => 0.00,
            'organisateur_id' => $organisateur->id
        ];

        $response = $this->actingAs($organisateur)
                         ->post(route('events.store'), $eventData);

        $response->assertRedirect();

        $this->assertDatabaseHas('events', [
            'title' => 'New Test Event',
            'description' => 'This is a test event description'
        ]);
    }

    public function test_client_cannot_access_event_create_page()
    {
        $client = User::factory()->create([
            'role' => Role::Client->value
        ]);

        $response = $this->actingAs($client)
                         ->get(route('events.create'));

        $response->assertStatus(200);
    }

    public function test_organisateur_can_update_own_event()
    {
        $organisateur = User::factory()->create([
            'role' => Role::Organisateur->value
        ]);

        $event = Event::factory()->create([
            'organisateur_id' => $organisateur->id,
            'title' => 'Original Title'
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'description' => $event->description,
            'event_date' => $event->event_date,
            'address' => $event->address,
            'status' => $event->status,
            'max_participants' => $event->max_participants,
            'is_free' => $event->is_free,
            'price' => $event->price
        ];

        $response = $this->actingAs($organisateur)
                         ->put(route('event.update', $event), $updateData);

        $response->assertRedirect();

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Updated Title'
        ]);
    }

    public function test_organisateur_cannot_update_other_organisateur_event()
    {
        $organisateur1 = User::factory()->create([
            'role' => Role::Organisateur->value
        ]);

        $organisateur2 = User::factory()->create([
            'role' => Role::Organisateur->value
        ]);

        $event = Event::factory()->create([
            'organisateur_id' => $organisateur1->id,
            'title' => 'Original Title'
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'description' => $event->description,
            'event_date' => $event->event_date,
            'address' => $event->address,
            'status' => $event->status,
            'max_participants' => $event->max_participants,
            'is_free' => $event->is_free,
            'price' => $event->price
        ];

        $response = $this->actingAs($organisateur2)
                         ->put(route('event.update', $event), $updateData);

        $response->assertStatus(403);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Original Title'
        ]);
    }

    public function test_admin_can_update_any_event()
    {
        $admin = User::factory()->create([
            'role' => Role::Admin->value
        ]);

        $organisateur = User::factory()->create([
            'role' => Role::Organisateur->value
        ]);

        $event = Event::factory()->create([
            'organisateur_id' => $organisateur->id,
            'title' => 'Original Title'
        ]);

        $updateData = [
            'title' => 'Admin Updated Title',
            'description' => $event->description,
            'event_date' => $event->event_date,
            'address' => $event->address,
            'status' => $event->status,
            'max_participants' => $event->max_participants,
            'is_free' => $event->is_free,
            'price' => $event->price
        ];

        $response = $this->actingAs($admin)
                         ->put(route('event.update', $event), $updateData);

        $response->assertRedirect();

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Admin Updated Title'
        ]);
    }
}
