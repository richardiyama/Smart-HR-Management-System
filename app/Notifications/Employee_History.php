<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Employee_History extends Notification
{
    use Queueable;

    public $employee_bvn;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($bvn)
    {
        
        $this->employee_bvn = $bvn;
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
        
        $emp_bvn = $this->employee_bvn;

        return (new MailMessage)
                    ->line('This is to notify you of new employee with BVN of ' . $emp_bvn)
                    ->action('Click here to confirm', url('/'))
                    ->line('waiting for approval!');
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
