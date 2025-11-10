<?php

namespace App\Providers;

use App\Services\Contracts\SmsGatewayInterface;
use App\Services\MessageBirdService;
use App\Services\NexmoService;
use App\Services\TelesignService;
use App\Services\TwilioService;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->when(TwilioService::class)
            ->needs(SmsGatewayInterface::class)
            ->give(TwilioService::class);

        $this->app->when(MessageBirdService::class)
            ->needs(SmsGatewayInterface::class)
            ->give(MessageBirdService::class);

        $this->app->when(NexmoService::class)
            ->needs(SmsGatewayInterface::class)
            ->give(NexmoService::class);

        $this->app->when(TelesignService::class)
            ->needs(SmsGatewayInterface::class)
            ->give(TelesignService::class);
    }
}
