<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\EmailNotify;
use App\Events\SendEmailNotification;
use App\Jobs\SendEmailJob;
use Carbon\Carbon;

class payrollSeries
{
    public $name;
    public $data;
}

class Lumpsum
{
    public $name;
    public $from;
    public $toamount;
    public $created;
    public $createdby;
    public $id;
    public $status;
    public $company;
    public $department;
    public $site;
    public $remarks;
    public $employee_id;
    public $approvedby;
    public $approveddate;
}

class DashboardController extends Controller
{

    /*public function __construct()
    {
        $this->middleware('auth');
    }
    */
    public function getthelumpsum($check)
    {
        $the_lumpsum = $this->lumpsum($check);
        $lumpsum_count = DB::table('employee_change_lumpsum')->count();
        return response()->json([
            'lumpsums' => $the_lumpsum,
            'lumpsum_count' => $lumpsum_count
        ]);
    }

    public function updateLumpsum(Request $request)
    {
        DB::table('employee_change_lumpsum')->where('id', $request->id)->update([
            'approved_by' => $request->user_id,
            'approved_reason' => $request->remarks,
            'approved_date' => date('Y-m-d'),
            'status' => 1,
            // 'from' => $request->from,
        ]);

        DB::table('employee_salary_details')->where('employee_id', $request->employee_id)->update([
            'salary' => $request->to,
        ]);

        $user = DB::table('users')->where('id', $request->user_id)->first();
        $email = new EmailNotify();
        $email->email = $user->email;
        $email->user = $user->name;
        $email->confirmation = 1;
        $email->subject = "Lumpsum Approval Notification : " . number_format($request->to, 2);
        $email->body = "This is to notify you of approval of  lumpsum of    " . $request->name . "  from the sum of   " . number_format($request->from, 2) . " to " . number_format($request->to, 2);
        $date = date('Y-m-d H:m:s');
        $carbon_date = Carbon::parse($date);

        SendEmailJob::dispatch($email)->delay($carbon_date->addMinutes(5));
    }

    public function getChangeLumpsumByDates($fromdate, $todate, $check)
    {
        $lumpsums = DB::table('employee_change_lumpsum')->where('status', $check)->wherebetween('created', [$fromdate, $todate])->get();
        $the_lumpsum = array();
        if ($lumpsums) {
            foreach ($lumpsums as $item) {
                $lump = new Lumpsum();
                $lump->id = $item->id;
                $employee = DB::table('employee_personal_details')->where('employee_id', $item->employee_id)->first();
                $name = "";
                if ($employee) {
                    $name = $employee->firstname . ' ' . $employee->middlename . ' ' . $employee->lastname;
                }
                $lump->name = $name;
                $lump->from = $item->from;
                $lump->toamount = $item->amount;
                $lump->created = $item->created;
                $lump->status = $item->status;
                $lump->remarks = $item->reason;
                $lump->employee_id = $item->employee_id;
                $lump->approveddate = $item->approved_date;

                $emp = DB::table('users')->where('id', $item->approved_by)->first();
                if ($emp) {
                    $lump->approvedby = $emp->name;
                }

                $work = DB::table('employee_work_shedules')->where('employee_id', $item->employee_id)->first();
                if ($work) {
                    $lump->site = $work->site;
                    $lump->department = $work->department;
                    $lump->company = $work->company;
                }

                $user = DB::table('users')->where('id', $item->user_id)->first();
                if ($user) {
                    $lump->createdby = $user->name;
                }

                $the_lumpsum[] = $lump;
            }
        }

        return response()->json([
            'lumpsum' => $the_lumpsum,
        ]);
    }

    public function changeLumpsum(Request $request)
    {
        $beforeSalary = DB::table('employee_salary_details')->where('employee_id', $request->employee_id)->first();

        $employee = DB::table('employee_personal_details')->where('employee_id', $request->employee_id)->first();
        $name = "";
        $salary = "";
        if ($employee) {
            $name = $employee->firstname . ' ' . $employee->middlename . ' ' . $employee->lastname;
        }
        if ($beforeSalary) {
            $salary = $beforeSalary->salary;
        }
        DB::table('employee_change_lumpsum')->insert([
            'site_id' => $request->site_id,
            'employee_id' => $request->employee_id,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'user_id' => $request->user_id,
            'from' => $salary
        ]);

        $user = DB::table('users')->where('id', $request->user_id)->first();
        $email = new EmailNotify();
        $email->email = $user->email;
        $email->user = $user->name;
        $email->confirmation = 1;
        $email->subject = "Change in Lumpsum Notification : " . number_format($request->amount, 2);
        $email->body = "This is to notify you of change in lumpsum of    " . $name . "  from the sum of   " . number_format($salary, 2) . " to " . number_format($request->amount, 2);
        $date = date('Y-m-d H:m:s');
        $carbon_date = Carbon::parse($date);

        SendEmailJob::dispatch($email)->delay($carbon_date->addMinutes(5));

        $the_lumpsum = $this->lumpsum();

        $lumpsum_count = DB::table('employee_change_lumpsum')->count();

        return response()->json([
            'lumpsums' => $the_lumpsum,
            'lumpsum_count' =>  $lumpsum_count
        ]);
    }

    public function getEmployeeAsPerSite($site_id)
    {

        $selectedEmployees = DB::select("select CONCAT(firstname, ' ', middlename, ' ', lastname) as empname, employee_bvn.id, employee_salary_details.salary from employee_work_shedules join employee_personal_details on employee_personal_details.employee_id = employee_work_shedules.employee_id join employee_bvn on employee_bvn.id = employee_work_shedules.employee_id join employee_salary_details on employee_salary_details.employee_id = employee_bvn.id where site = {$site_id} and employee_bvn.active = 1");

        return response()->json([
            'employees' =>  $selectedEmployees
        ]);
    }

    public function lumpsum($check = 0)
    {
        $lumpsums = DB::table('employee_change_lumpsum')->where('status', $check)->get();
        $the_lumpsum = array();
        if ($lumpsums) {
            foreach ($lumpsums as $item) {
                $lump = new Lumpsum();
                $lump->id = $item->id;
                $employee = DB::table('employee_personal_details')->where('employee_id', $item->employee_id)->first();
                $name = "";
                if ($employee) {
                    $name = $employee->firstname . ' ' . $employee->middlename . ' ' . $employee->lastname;
                }
                $lump->name = $name;
                $lump->from = $item->from;
                $lump->toamount = $item->amount;
                $lump->created = $item->created;
                $lump->status = $item->status;
                $lump->remarks = $item->reason;
                $lump->employee_id = $item->employee_id;
                $lump->approveddate = $item->approved_date;

                $emp = DB::table('users')->where('id', $item->approved_by)->first();
                if ($emp) {
                    $lump->approvedby = $emp->name;
                }

                $user = DB::table('users')->where('id', $item->user_id)->first();
                if ($user) {
                    $lump->createdby = $user->name;
                }

                $work = DB::table('employee_work_shedules')->where('employee_id', $item->employee_id)->first();
                if ($work) {
                    $lump->site = $work->site;
                    $lump->department = $work->department;
                    $lump->company = $work->company;
                }


                $the_lumpsum[] = $lump;
            }
        }

        return $the_lumpsum;

        //$lumpsum_count = DB::table('employee_change_lumpsum')->count();
    }


    public function changeInLumpsum()
    {
        $departments = DB::table('departments')->get();
        $sites = DB::table('sites')->get();
        $companies = DB::table('companies')->get();
        $the_lumpsum = $this->lumpsum();

        $lumpsum_count = DB::table('employee_change_lumpsum')->count();

        return view('make_request.change_lumpsum', ['departments' => $departments, 'sites' => $sites, 'companies' => $companies, 'lumpsum' => $the_lumpsum, 'lumpsum_count' => $lumpsum_count]);
    }
    public function index()
    {
        $companies = DB::table('companies')->get();
        $labels = array();
        $series = [];
        if ($companies) {
            foreach ($companies as $company) {
                $emp = DB::table('employee_bvn')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'employee_bvn.id')
                    ->where('employee_bvn.active', 1)
                    ->where('employee_work_shedules.company', $company->id)
                    ->count();
                $labels[] = $company->name;
                $series[] = $emp;
            }
        }

        //payrolls
        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        $year = date('Y');

        $jan = DB::table('payrolls')->whereMonth('date', 1)->whereYear('date', $year)->sum('lumpsum');
        $feb = DB::table('payrolls')->whereMonth('date', 2)->whereYear('date', $year)->sum('lumpsum');
        $mar = DB::table('payrolls')->whereMonth('date', 3)->whereYear('date', $year)->sum('lumpsum');
        $apr = DB::table('payrolls')->whereMonth('date', 4)->whereYear('date', $year)->sum('lumpsum');
        $may = DB::table('payrolls')->whereMonth('date', 5)->whereYear('date', $year)->sum('lumpsum');
        $jun = DB::table('payrolls')->whereMonth('date', 6)->whereYear('date', $year)->sum('lumpsum');
        $jul = DB::table('payrolls')->whereMonth('date', 7)->whereYear('date', $year)->sum('lumpsum');
        $aug = DB::table('payrolls')->whereMonth('date', 8)->whereYear('date', $year)->sum('lumpsum');
        $sep = DB::table('payrolls')->whereMonth('date', 9)->whereYear('date', $year)->sum('lumpsum');
        $oct = DB::table('payrolls')->whereMonth('date', 10)->whereYear('date', $year)->sum('lumpsum');
        $nov = DB::table('payrolls')->whereMonth('date', 11)->whereYear('date', $year)->sum('lumpsum');
        $dec = DB::table('payrolls')->whereMonth('date', 12)->whereYear('date', $year)->sum('lumpsum');

        $payroll_data = [$jan, $feb, $mar, $apr, $may, $jun, $jul, $aug, $sep, $oct, $nov, $dec];

        $pay = new payrollSeries();
        $pay->name = "Current_year_payroll";
        $pay->data = $payroll_data;


        $pay_series = [$pay];



        return view('dashboard.index', ['labels' => $labels, 'series' => $series, 'months' => $months, "payroll_data" => $payroll_data, "pay_series" => $pay_series]);
    }

    public function employee_salary_increment($employee_id){

       
        $salarys = DB::table('employee_change_lumpsum')->where('employee_id',$employee_id)->get();
        
        
        

        
        
        return view('history.employee_salary_increment',compact('salarys'));
        
        
    }
}
