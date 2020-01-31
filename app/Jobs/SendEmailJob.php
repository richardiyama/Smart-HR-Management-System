<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\NotificationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 300;

    //public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $email;
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //send emails as regards to settings....
        if ($this->email->confirmation == 1) {
            $accounts = DB::table('settings_user')->where('email_confirmation_approval', 1)->get();
            if ($accounts) {
                foreach ($accounts as $item) {
                    $user = DB::table('users')->where('id', $item->user_id)->first();
                    if ($user) {
                        Mail::to($user->email)->send(new NotificationEmail($this->email));
                    }
                }
            }
        }

        if ($this->email->confirmation == 1 || $this->email->confirmation == 0) {
            $accounts = DB::table('settings_user')->where('email_all_activities', 1)->get();
            if ($accounts) {
                foreach ($accounts as $item) {
                    $user = DB::table('users')->where('id', $item->user_id)->first();
                    if ($user) {
                        Mail::to($user->email)->send(new NotificationEmail($this->email));
                    }
                }
            }
        }
    }

    public function retryUntil()
    {
        $date = date('Y-m-d H:m:s');
        $carbon_date = Carbon::parse($date);
        //$carbon_date->addHours(1);
        return $carbon_date->addMinutes(10);
    }
}
