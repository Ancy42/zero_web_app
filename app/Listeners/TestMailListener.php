<?php

namespace App\Listeners;

use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

class TestMailListener
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
        Mail::to($event->email)->send(new TestMail($event->email, $event->messageContent));
    }
}
