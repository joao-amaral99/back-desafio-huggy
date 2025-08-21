<?php

namespace App\Services;

use App\Models\Contact;
use App\Services\Contracts\ContactServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use App\Services\ExternalWebhookService;
use App\Jobs\SendWelcomeEmail;

class ContactService implements ContactServiceInterface
{
    protected ExternalWebhookService $externalWebhookService;

    public function __construct(ExternalWebhookService $externalWebhookService)
    {
        $this->externalWebhookService = $externalWebhookService;
    }

    public function create(array $data): Contact
    {
        $contact = Contact::create($data);

        $this->externalWebhookService->notify($contact, 'contact.created');
        SendWelcomeEmail::dispatch($contact)->delay(now()->addMinutes(1));

        return $contact;
    }

    public function findById(int $id): ?Contact
    {
        return Contact::find($id);
    }

    public function update(int $id, array $data): Contact
    {
        $contact = Contact::find($id);

        if (!$contact) {
            throw new ModelNotFoundException('Contato não encontrado.');
        }

        $contact->update($data);
        $this->externalWebhookService->notify($contact, 'contact.updated');
        return $contact;
    }

    public function delete(int $id): void
    {
        $contact = Contact::find($id);

        if (!$contact) {
            throw new ModelNotFoundException('Contato não encontrado.');
        }

        $contact->delete();
    }

    public function getAll(?string $search = null, string $sortBy = 'name', string $sortOrder = 'asc'): Collection
    {
        $query = Contact::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('email', 'ILIKE', "%{$search}%")
                  ->orWhere('phone', 'ILIKE', "%{$search}%")
                  ->orWhere('mobile', 'ILIKE', "%{$search}%")
                  ->orWhere('city', 'ILIKE', "%{$search}%")
                  ->orWhere('state', 'ILIKE', "%{$search}%");
            });
        }
    
        $sortOrder = strtolower($sortOrder) === 'desc' ? 'desc' : 'asc';

        return $query->orderBy($sortBy, $sortOrder)->get();
    }

    public function findByAny(array $data): ?Contact
    {
        return Contact::where(function ($query) use ($data) {
            if (!empty($data['email'])) {
                $query->orWhere('email', $data['email']);
            }
            if (!empty($data['mobile'])) {
                $query->orWhere('mobile', $data['mobile']);
            }
            if (!empty($data['phone'])) {
                $query->orWhere('phone', $data['phone']);
            }
            if (!empty($data['name'])) {
                $query->orWhere('name', $data['name']);
            }
        })->first();
    }
}