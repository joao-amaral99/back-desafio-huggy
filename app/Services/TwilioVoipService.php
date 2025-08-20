<?php

namespace App\Services;

use App\Services\Contracts\VoipServiceInterface;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioVoipService implements VoipServiceInterface
{
    protected Client $twilio;
    protected string $fromNumber;

    public function __construct()
    {
        $this->twilio = new Client(
            env('TWILIO_SID'),
            env('TWILIO_AUTH_TOKEN')
        );
        
        $this->fromNumber = env('TWILIO_PHONE_NUMBER', '');
    }

    public function makeCall(string $toNumber): bool
    {
        try {
            $call = $this->twilio->calls->create(
                $toNumber,
                $this->fromNumber,
                [
                    'url' => 'https://demo.twilio.com/welcome/voice/',
                    'method' => 'POST'
                ]
            );

            Log::info('Ligação iniciada com sucesso', [
                'call_sid' => $call->sid,
                'to_number' => $toNumber,
                'from_number' => $this->fromNumber
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erro ao iniciar ligação', [
                'to_number' => $toNumber,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }
}
