<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Contact;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_contacts_by_city_api()
    {
        Contact::factory()->create(['city' => 'São Paulo']);
        Contact::factory()->create(['city' => 'São Paulo']);
        Contact::factory()->create(['city' => 'Rio de Janeiro']);

        $response = $this->getJson('/api/reports/contacts-by-city');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => [['city', 'count'], ['city', 'count']]])
                 ->assertJsonCount(2, 'data')
                 ->assertJsonFragment(['city' => 'São Paulo', 'count' => 2])
                 ->assertJsonFragment(['city' => 'Rio de Janeiro', 'count' => 1]);
    }

    public function test_returns_contacts_by_state_api()
    {
        Contact::factory()->create(['state' => 'SP']);
        Contact::factory()->create(['state' => 'SP']);
        Contact::factory()->create(['state' => 'RJ']);

        $response = $this->getJson('/api/reports/contacts-by-state');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => [['state', 'count'], ['state', 'count']]])
                 ->assertJsonCount(2, 'data')
                 ->assertJsonFragment(['state' => 'SP', 'count' => 2])
                 ->assertJsonFragment(['state' => 'RJ', 'count' => 1]);
    }
}