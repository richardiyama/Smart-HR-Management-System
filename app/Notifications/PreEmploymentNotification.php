<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PreEmploymentNotification extends Notification
{
    use Queueable;

    public $employee_code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($code)
    {
        $this->employee_code = $code;
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
        $cod = $this->employee_code;
        return (new MailMessage)
                    
                    ->level('info')
                    ->line('This is to notify you of new employee with pre_employment code of ' . $cod . ' waiting for your approval')
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
