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
                        'name' => 'João Teste',
                        'email' => 'joao.teste@example.com',
                        'phone' => '123456789',
                        'mobile' => '987654321',
                        'photo' => 'http://imageurl.com/photo.jpg',
                    ]
                ]
            ]
        ];

        $response = $this->postJson('/api/huggy/webhook', $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('contacts', [
            'email' => 'joao.teste@example.com',
            'name' => 'João Teste',
            'photo' => 'http://imageurl.com/photo.jpg',
        ]);
    }

    public function test_webhook_updates_contact()
    {
        Contact::factory()->create([
            'name' => 'João Teste',
            'email' => 'joao.teste@example.com',
            'phone' => '123456789',
            'mobile' => '987654321',
            'photo' => 'http://imageurl.com/photo.jpg',
        ]);

        $payload = [
            'messages' => [
                'updatedCustomer' => [
                    [
                        'name' => 'João Teste Atualizado',
                        'email' => 'joao.teste@example.com',
                        'phone' => '111111111',
                        'mobile' => '222222222',
                        'photo' => 'http://imageurl.com/photo.jpg',
                    ]
                ]
            ]
        ];

        $response = $this->postJson('/api/huggy/webhook', $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('contacts', [
            'email' => 'joao.teste@example.com',
            'name' => 'João Teste Atualizado',
            'phone' => '111111111',
            'mobile' => '222222222',
            'photo' => 'http://imageurl.com/photo.jpg',
        ]);
    }
}
