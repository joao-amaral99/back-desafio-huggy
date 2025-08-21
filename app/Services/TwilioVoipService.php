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

    public function makeCall(string $toNumber): string
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
            
            return 'success';
            
        } catch (\Twilio\Exceptions\RestException $e) {
            Log::error('Erro Twilio ao iniciar ligação', [
                'to_number' => $toNumber,
                'error_message' => $e->getMessage(),
            ]);
            
            return $this->translateTwilioError($e->getMessage());
        }
    }

    private function translateTwilioError(string $originalMessage): string
    {
        $originalMessage = str_replace('[HTTP 400]', '', $originalMessage);

        $errorMap = [
            'is unverified. Trial accounts may only make calls to verified numbers' => 'não está verificado. Contas de teste só podem ligar para números verificados',
            'Unable to create record' => 'Não foi possível realizar a ligação',
            'The number' => 'O número',
        ];
        
        $translatedMessage = $originalMessage;
        
        foreach ($errorMap as $englishMessage => $portugueseMessage) {
            $translatedMessage = str_replace($englishMessage, $portugueseMessage, $translatedMessage);
        }

        if ($translatedMessage === $originalMessage) {
            return $translatedMessage;
        }
        
        return $translatedMessage;
    }
}
