<?php

namespace App\Services\Contracts;

use App\Models\Contact;
use Illuminate\Support\Collection;

interface ContactServiceInterface
{
  public function create(array $data): Contact;

    public function findById(int $id): ?Contact;

    public function update(int $id, array $data): Contact;

    public function delete(int $id): void;

    public function getAll(): Collection;
    
}