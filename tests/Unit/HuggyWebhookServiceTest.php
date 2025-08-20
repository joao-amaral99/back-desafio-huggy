<?php

namespace Tests\Unit;

use App\Models\Contact;
use App\Services\HuggyWebhookService;
use App\Services\ContactService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HuggyWebhookServiceTest extends TestCase
{
    use RefreshDatabase;

    protected HuggyWebhookService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(HuggyWebhookService::class);
    }

    public function test_handle_event_creates_contact()
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
        
        $this->service->handleEvent($payload);

        $this->assertDatabaseHas('contacts', [
            'email' => 'joao.teste@example.com',
            'name' => 'João Teste',
        ]);
    }

    public function test_handle_event_updates_contact()
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

        $this->service->handleEvent($payload);

        $this->assertDatabaseHas('contacts', [
            'email' => 'joao.teste@example.com',
            'name' => 'João Teste Atualizado',
            'phone' => '111111111',
            'mobile' => '222222222',
            'photo' => 'http://imageurl.com/photo.jpg',
        ]);
    }
}
