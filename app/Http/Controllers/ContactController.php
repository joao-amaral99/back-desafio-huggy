<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreContactRequest;
use App\Services\Contracts\ContactServiceInterface;
use App\Services\Contracts\VoipServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\UpdateContactRequest;

class ContactController extends Controller
{
    protected ContactServiceInterface $contactService;
    protected VoipServiceInterface $voipService;

    public function __construct(ContactServiceInterface $contactService, VoipServiceInterface $voipService)
    {
        $this->contactService = $contactService;
        $this->voipService = $voipService;
    }

    public function getAll(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'name');
        $sortOrder = $request->query('sort_order', 'asc');

        $contacts = $this->contactService->getAll($search, $sortBy, $sortOrder);
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

    public function makeCall($id): JsonResponse
    {
        $contact = $this->contactService->findById($id);
        if (!$contact) {
            return response()->json(['message' => 'Contato não encontrado.'], 404);
        }
        
        $phoneNumber = $contact->mobile;
        if (empty($phoneNumber)) {
            $phoneNumber = $contact->phone;
        }
        
        if (!$phoneNumber) {
            return response()->json([
                'message' => 'O contato não possui número de telefone.'
            ], 400);
        }
        
        $result = $this->voipService->makeCall($phoneNumber);
        
        if ($result === 'success') {
            return response()->json([
                'message' => 'Sucesso! Aguarde a ligação.',
            ]);
        }
        
        return response()->json([
            'message' => $result
        ], 500);
    }
}