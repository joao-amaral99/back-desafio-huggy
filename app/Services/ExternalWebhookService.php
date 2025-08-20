<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalWebhookService
{
    public function notify(Contact $contact, string $event): bool
    {
        $url = env('EXTERNAL_WEBHOOK_URL');

        if (!$url) {
            Log::warning('URL do webhook externo não configurada');
            return false;
        }

        try {
            $payload = [
                'event' => $event,
                'contact' => $contact->toArray(),
                'timestamp' => now()->toISOString()
            ];

            $response = Http::timeout(30)->post($url, $payload);

            if ($response->successful()) {
                Log::info("Webhook externo enviado com sucesso para {$event}", [
                    'contact_id' => $contact->id
                ]);
                return true;
            }

            Log::error("Falha no webhook externo", [
                'status' => $response->status(),
                'contact_id' => $contact->id
            ]);

        } catch (\Exception $e) {
            Log::error("Erro ao enviar webhook externo: " . $e->getMessage(), [
                'contact_id' => $contact->id
            ]);
        }

        return false;
    }
}