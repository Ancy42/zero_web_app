<?php

namespace App\Services;

use App\Models\SMSConfig;
use App\Services\Contracts\SmsGatewayInterface;
use Illuminate\Support\Facades\App;

class SmsGatewayService
{
    protected $gateway;

    public function __construct()
    {
        $smsConfig = SMSConfig::where('status', true)->first();

        if ($smsConfig) {
            $config = json_decode($smsConfig->data);
            $this->gateway = $this->resolveGateway($smsConfig->provider, $config);
        }
    }

    protected function resolveGateway(string $provider, $config): ?SmsGatewayInterface
    {
        switch ($provider) {
            case 'twilio':
                return App::makeWith(TwilioService::class, ['config' => $config]);
            case 'message_bird':
                return App::makeWith(MessageBirdService::class, ['config' => $config]);
            case 'nexmo':
                return App::makeWith(NexmoService::class, ['config' => $config]);
            case 'telesign':
                return App::makeWith(TelesignService::class, ['config' => $config]);
            default:
                return null;
        }
    }

    public function sendSMS($phoneCode, $phoneNumber, $message)
    {
        if (! $this->gateway) {
            return false;
        }

        $to = $phoneCode.$phoneNumber;

        // $phoneNumber = '+91'.substr($to, -10);
        $response = $this->gateway->sendMessage($to, $message);

        return $response;
    }
}
