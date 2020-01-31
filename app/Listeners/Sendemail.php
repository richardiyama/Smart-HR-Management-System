<?php

namespace App\Listeners;

use App\Events\SendEmailNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\NotificationEmail;
use Illuminate\Support\Facades\Mail;


class Sendemail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendEmailNotification  $event
     * @return void
     */
    public function handle(SendEmailNotification $event)
    {
        //perform the event
        Mail::to($event->email->email)->send(new NotificationEmail($event->email));


    }
}
