<?php

namespace App\Services\Contracts;

interface VoipServiceInterface
{
    public function makeCall(string $toNumber): string;
}
