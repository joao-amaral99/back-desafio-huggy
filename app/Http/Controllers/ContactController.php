<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreContactRequest;
use App\Services\Contracts\ContactServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\UpdateContactRequest;

class ContactController extends Controller
{
    protected ContactServiceInterface $contactService;

    public function __construct(ContactServiceInterface $contactService)
    {
        $this->contactService = $contactService;
    }

    public function getAll(): JsonResponse
    {
        $contacts = $this->contactService->getAll();
        return response()->json(['data' => $contacts]);
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        $contact = $this->contactService->create($request->validated());

        return response()->json([
            'message' => 'Contato criado com sucesso!',
            'data' => $contact
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $contact = $this->contactService->findById($id);

        if (!$contact) {
            return response()->json(['message' => 'Contato não encontrado.'], 404);
        }

        return response()->json(['data' => $contact]);
    }

    public function update(UpdateContactRequest $request, $id): JsonResponse
    {
        try {
            $contact = $this->contactService->update($id, $request->validated());
            return response()->json([
                'message' => 'Contato atualizado com sucesso!',
                'data' => $contact
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Contato não encontrado.'], 404);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $this->contactService->delete($id);
            return response()->json(['message' => 'Contato deletado com sucesso!']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Contato não encontrado.'], 404);
        }
    }
}