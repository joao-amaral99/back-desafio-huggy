<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HuggyWebhookService;
use Illuminate\Support\Facades\Log;

class HuggyWebhookController extends Controller
{
    protected HuggyWebhookService $webhookService;

    public function __construct(HuggyWebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function handleHuggy(Request $request)
    {
        if ($request->has('token') && $request->has('validToken')) {
            return env('HUGGY_WEBHOOK_TOKEN');
        }

        $body = $request->all();

        try {
            $this->webhookService->handleEvent($body);

        } catch (\Throwable $e) {
            Log::error('Erro no webhook Huggy: ' . $e->getMessage(), [
                'exception' => $e,
                'body' => $body
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
        return response()->json(['status' => 'ok']);
    }
}