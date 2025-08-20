<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ExternalWebhookService;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class ExternalWebhookServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ExternalWebhookService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ExternalWebhookService();
    }

    public function test_notify_sends_webhook_successfully()
    {
        putenv('EXTERNAL_WEBHOOK_URL=https://webhook.example.com');
        Http::fake([
            'https://webhook.example.com' => Http::response(['status' => 'ok'], 200)
        ]);

        $contact = Contact::factory()->make([
            'id' => 1,
            'name' => 'João Silva',
            'email' => 'joao@example.com'
        ]);

        $result = $this->service->notify($contact, 'contact.created');

        $this->assertTrue($result);
        
        Http::assertSent(function ($request) use ($contact) {
            $data = $request->data();
            return $request->url() === 'https://webhook.example.com' &&
                   $data['event'] === 'contact.created' &&
                   $data['contact']['name'] === 'João Silva' &&
                   $data['contact']['email'] === 'joao@example.com' &&
                   isset($data['timestamp']);
        });
    }

    public function test_notify_constructs_correct_payload()
    {
        putenv('EXTERNAL_WEBHOOK_URL=https://webhook.example.com');
        Http::fake();

        $contact = Contact::factory()->make([
            'id' => 123,
            'name' => 'Maria Santos',
            'email' => 'maria@example.com',
            'phone' => '11999999999'
        ]);

        $this->service->notify($contact, 'contact.updated');

        Http::assertSent(function ($request) {
            $data = $request->data();
            
            return $data['event'] === 'contact.updated' &&
                   $data['contact']['id'] === 123 &&
                   $data['contact']['name'] === 'Maria Santos' &&
                   $data['contact']['email'] === 'maria@example.com' &&
                   $data['contact']['phone'] === '11999999999' &&
                   isset($data['timestamp']) &&
                   !empty($data['timestamp']);
        });
    }

}
