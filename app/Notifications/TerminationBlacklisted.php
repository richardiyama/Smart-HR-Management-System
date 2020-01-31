<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TerminationBlacklisted extends Notification
{
    use Queueable;
    public $terminated_employee;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->terminated_employee = $name;
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
        $user_name = $this->terminated_employee;
        return (new MailMessage)
                    
                    ->level('info')
                    ->line('This is to notify you of termination of  '.$user_name)
                    ->action('Click here to confirm', url('/'))
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
