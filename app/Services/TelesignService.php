<?php

namespace App\Services;

use App\Services\Contracts\SmsGatewayInterface;
use telesign\sdk\messaging\MessagingClient;

class TelesignService implements SmsGatewayInterface
{
    protected $client;

    public function __construct($config)
    {
        $customerId = $config->customer_id;
        $apiKey = $config->api_key;
        $this->client = new MessagingClient($customerId, $apiKey);
    }

    public function sendMessage($to, $message)
    {
        return $this->client->message($to, $message, 'ARN');
    }
}
