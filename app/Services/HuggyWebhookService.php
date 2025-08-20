<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Services\ContactService;

class HuggyWebhookService
{
    public function handleEvent(array $body): void
    {
        if (isset($body['messages']['createdCustomer'])) {
            foreach ($body['messages']['createdCustomer'] as $customer) {
                $this->syncContact($customer);
            }
        }

        if (isset($body['messages']['updatedCustomer'])) {
            foreach ($body['messages']['updatedCustomer'] as $customer) {
                $this->syncContact($customer, true);
            }
        }
    }

    protected function syncContact(array $customer, bool $update = false): void
    {
        $contactService = app(ContactService::class);

        $data = [
            'name' => $customer['name'] ?? null,
            'email' => $customer['email'] ?? null,
            'phone' => $customer['phone'] ?? null,
            'mobile' => $customer['mobile'] ?? null,
            'photo' => $customer['photo'] ?? null,
        ];

        if ($update) {
            $contact = $contactService->findByAny($data);
            if ($contact) {
                $contactService->update($contact->id, $data);
            }
        } else {
            $contactService->create($data);
        }
    }

    public function notify(\App\Models\Contact $contact): void
    {
        $url = env('EXTERNAL_WEBHOOK_URL');

        if ($url) {
            $payload = $contact->toArray();

            Http::post($url, $payload);
        }
    }
}