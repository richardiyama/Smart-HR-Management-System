<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\PreEmployement;
use Carbon\Carbon;

class UpdatePremployment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:pre_employment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating all pending approval.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

  

        $current_date = Carbon::createFromFormat("Y-m-d H:i:s", Carbon::now());
        $weekDay = date('w', strtotime($current_date));
    

        PreEmployement::where('created_at', '=', ($weekDay == 0 || $weekDay == 6))->where('created_at', '<', Carbon::now()->subDays(2)->toDateTimeString())->update([
            'hr_manager_approval' => 1,
            'project_manager_approval' => 1,
            'project_manager_approval_date' => $current_date,
            'hr_manager_approval_date'=> $current_date
             
         ]);

       

        PreEmployement::where('created_at', '<', Carbon::now()->subDays(1)->toDateTimeString())->update([
            'hr_manager_approval' => 1,
            'project_manager_approval' => 1,
            'project_manager_approval_date' => $current_date,
            'hr_manager_approval_date'=> $current_date
             
         ]);

        $this->info('All pending approvals are updated successfully!');
    }
}
