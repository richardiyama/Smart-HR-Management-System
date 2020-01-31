<?php

namespace App\Listeners;

use App\Events\ApprovedTermination;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class TerminateEmployee
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    { }

    /**
     * Handle the event.
     *
     * @param  ApprovedTermination  $event
     * @return void
     */
    public function handle(ApprovedTermination $event)
    {
        // \Log::info('Terminate employee', ['Approval' => $event->Approval]);
        //run event
        $now = date('Y-m-d');
        $future = strtotime($event->Approval->terminated);
        $datediff = $future - strtotime($now);
        $day = round($datediff / (60 * 60 * 24));
        $str = $this->generateRandomString(4);
        $terminated_datetime = 'CURRENT_TIMESTAMP + INTERVAL '.$day.' DAY';
        DB::unprepared(DB::raw(
            "CREATE EVENT IF NOT EXISTS teminate_" . $str . " 
             ON SCHEDULE AT " . $terminated_datetime . "
             DO
                UPDATE employee_bvn  SET `active` =2, `terminated` = 1 WHERE id = " . $event->Approval->employee_id
            
        ));
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
