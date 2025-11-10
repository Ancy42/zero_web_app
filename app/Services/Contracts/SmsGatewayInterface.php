<?php

namespace App\Services\Contracts;

interface SmsGatewayInterface
{
    public function sendMessage(string $to, string $message);
}
