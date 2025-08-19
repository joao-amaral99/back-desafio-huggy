<?php

namespace App\Services;

use App\Models\Contact;
use App\Services\Contracts\ContactServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class ContactService implements ContactServiceInterface
{
    public function create(array $data): Contact
    {
        return Contact::create($data);
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
}