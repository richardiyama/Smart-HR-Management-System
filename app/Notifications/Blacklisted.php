<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Blacklisted extends Notification
{
    use Queueable;
    public $blacklisted_employee;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->blacklisted_employee = $name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user_name = $this->blacklisted_employee;
        return (new MailMessage)
                    
                    ->level('info')
                    ->line('This is to notify you that we need approval for re-employing a blacklisted employee with name:  '.$user_name)
                    ->action('Click here for Approval', url('/'))
                    ->line('We hope to hear from you soon!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
