<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Services\Contracts\ContactServiceInterface;

class ReportController extends Controller
{
    protected ContactServiceInterface $contactService;

    public function __construct(ContactServiceInterface $contactService)
    {
        $this->contactService = $contactService;
    }

    public function contactsByCity(): JsonResponse
    {
        $contactsByCity = $this->contactService->contactsByCity();
        return response()->json(['data' => $contactsByCity]);
    }

    public function contactsByState(): JsonResponse
    {
        $contactsByState = $this->contactService->contactsByState();
        return response()->json(['data' => $contactsByState]);
    }

}