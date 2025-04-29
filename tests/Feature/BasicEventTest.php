<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BasicEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_events_page_requires_authentication()
    {
        $response = $this->get(route('events'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_events_page()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->get(route('events'));
        
        $response->assertStatus(200);
    }

    public function test_events_are_displayed_on_events_page()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'title' => 'Test Event Title'
        ]);
        
        $response = $this->actingAs($user)
                         ->get(route('events'));
        
        $response->assertStatus(200)
                 ->assertSee('Test Event Title');
    }

    public function test_user_can_view_event_details()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'title' => 'Test Event Title',
            'description' => 'Test Event Description'
        ]);
        
        $response = $this->actingAs($user)
                         ->get(route('event.show', $event));
        
        $response->assertStatus(200)
                 ->assertSee('Test Event Title')
                 ->assertSee('Test Event Description');
    }
}
