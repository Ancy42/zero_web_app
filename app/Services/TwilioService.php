<?php

namespace App\Services;

use Twilio\Rest\Client;
use App\Services\Contracts\SmsGatewayInterface;

class TwilioService
{
    protected Client $client;

    protected string $from;
    protected string $whatsappFrom;

    public function __construct(array $config = [])
    {
        $sid   = $config['twilio_sid'] ?? null;
        $token = $config['twilio_token'] ?? null;
        $this->from = $config['twilio_from'] ?? '';
        $this->whatsappFrom = 'whatsapp:+'.$config['twilio_from'] ?? '';

        if ($sid && $token) {
            $this->client = new Client($sid, $token);
        }
    }

    /**
     * Send a standard SMS message.
     */
    public function sendSms(string $to, string $message)
    {
        return $this->client->messages->create($to, [
            'from' => $this->from,
            'body' => $message,
        ]);
    }

    /**
     * Send a WhatsApp message using a template and variables.
     */
    public function sendWhatsAppMessage(string $to, $data, string $template)
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }

        return $this->client->messages->create("whatsapp:$to", [
            'from' => $this->whatsappFrom,
            'contentSid' => $template,
            'contentVariables' =>$data,
        ]);
    }


    /**
     * Send a plain WhatsApp message without a template.
     */
    public function sendWhatsAppText(string $to, string $message)
    {
        return $this->client->messages->create("whatsapp:$to", [
            'from' => $this->whatsappFrom,
            'body' => $message,
        ]);
    }


}
