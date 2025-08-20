<?php

namespace App\Services;

use App\Models\Contact;
use App\Services\Contracts\ContactServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class ContactService implements ContactServiceInterface
{
    protected HuggyWebhookService $huggyWebhookService;

    public function __construct(HuggyWebhookService $huggyWebhookService)
    {
        $this->huggyWebhookService = $huggyWebhookService;
    }

    public function create(array $data): Contact
    {
        $contact = Contact::create($data);
        $this->huggyWebhookService->notify($contact);
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
        $this->huggyWebhookService->notify($contact);
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

    public function getAll(): Collection
    {
        return Contact::all();
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