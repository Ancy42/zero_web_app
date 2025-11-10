<?php

namespace App\Listeners;

use App\Mail\OrderMail;
use Illuminate\Support\Facades\Mail;

class OrderMailListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        if (config('app.mail_username') && config('app.mail_password') && config('app.mail_encryption')) {
            Mail::to($event->email)->send(new OrderMail($event->email, $event->order));
        }
    }
}
