<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Contact;

class HuggyWebhookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_webhook_creates_contact()
    {
        $payload = [
            'messages' => [
                'createdCustomer' => [
                    [
                        'name' => 'Test User',
                        'email' => 'test@example.com',
                        'phone' => '123456789',
                        'mobile' => '987654321',
                        'photo' => 'http://example.com/photo.jpg',
                    ]
                ]
            ]
        ];

        $response = $this->postJson('/api/huggy/webhook', $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('contacts', [
            'email' => 'test@example.com',
            'name' => 'Test User',
            'photo' => 'http://example.com/photo.jpg',
        ]);
    }

    public function test_webhook_updates_contact()
    {
        Contact::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '123456789',
            'mobile' => '987654321',
            'photo' => 'http://example.com/photo.jpg',
        ]);

        $payload = [
            'messages' => [
                'updatedCustomer' => [
                    [
                        'name' => 'Test User Updated',
                        'email' => 'test@example.com',
                        'phone' => '111111111',
                        'mobile' => '222222222',
                        'photo' => 'http://example.com/photo2.jpg',
                    ]
                ]
            ]
        ];

        $response = $this->postJson('/api/huggy/webhook', $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('contacts', [
            'email' => 'test@example.com',
            'name' => 'Test User Updated',
            'phone' => '111111111',
            'mobile' => '222222222',
            'photo' => 'http://example.com/photo2.jpg',
        ]);
    }
}
