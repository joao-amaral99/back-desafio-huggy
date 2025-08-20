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
                        'name' => 'Unit User',
                        'email' => 'unit@example.com',
                        'phone' => '123456789',
                        'mobile' => '987654321',
                        'photo' => 'http://example.com/photo.jpg',
                    ]
                ]
            ]
        ];
        
        $this->service->handleEvent($payload);

        $this->assertDatabaseHas('contacts', [
            'email' => 'unit@example.com',
            'name' => 'Unit User',
        ]);
    }

    public function test_handle_event_updates_contact()
    {
        Contact::factory()->create([
            'name' => 'Unit User',
            'email' => 'unit@example.com',
            'phone' => '123456789',
            'mobile' => '987654321',
            'photo' => 'http://example.com/photo.jpg',
        ]);

        $payload = [
            'messages' => [
                'updatedCustomer' => [
                    [
                        'name' => 'Unit User Updated',
                        'email' => 'unit@example.com',
                        'phone' => '111111111',
                        'mobile' => '222222222',
                        'photo' => 'http://example.com/photo2.jpg',
                    ]
                ]
            ]
        ];

        $this->service->handleEvent($payload);

        $this->assertDatabaseHas('contacts', [
            'email' => 'unit@example.com',
            'name' => 'Unit User Updated',
            'phone' => '111111111',
            'mobile' => '222222222',
            'photo' => 'http://example.com/photo2.jpg',
        ]);
    }
}
