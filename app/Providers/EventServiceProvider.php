<?php

namespace App\Providers;

use App\Events\OrderMailEvent;
use App\Events\SendOTPMail;
use App\Events\SendTestMailEvent;
use App\Listeners\OrderMailListener;
use App\Listeners\SendOTPMailNotification;
use App\Listeners\TestMailListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        SendOTPMail::class => [
            SendOTPMailNotification::class,
        ],

        OrderMailEvent::class => [
            OrderMailListener::class,
        ],

        SendTestMailEvent::class => [
            TestMailListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
