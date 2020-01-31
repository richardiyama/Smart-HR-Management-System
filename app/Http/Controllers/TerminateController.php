<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Events\ApprovedTermination;
use App\EmailNotify;
use Carbon\Carbon;
use App\Jobs\SendEmailJob;
use App\Notifications\TerminationBlacklisted;
use App\User;
use App\Message;

class TerminatedEmployee
{
    public $name;
    public $company;
    public $employee_no;
    public $site;
    public $department;
    public $employee_id;
    public $company_id;
    public $site_id;
    public $department_id;
    public $id;
    public $check_employee;
    public $terminated_date;
    public $reason;
    public $terminated_id;
}
class TerminateController extends Controller
{
    public function recallTermination(Request $request)
    {

        if (Auth::user()->role == 3 || Auth::user()->role == 1) {
            DB::table('employee_bvn')->where('id', $request->empid)->update([
                'active' => 1,
                'terminated' => 0,
                'is_terminated_request' => 0
            ]);

            DB::table('recall_terminations')->insert([
                'start_date' => $request->start_date,
                'comment' => $request->comment,
                'user_id' => Auth::user()->id,
                'employee_id' => $request->empid
            ]);

            return redirect('employee/' . $request->empid . '/edit')->with('status', 'Operation was successful');
        } else {
            return redirect()->back()->with('error', 'Access denied');
        }
    }
    public function rejectTermination(Request $request)
    {
        $termination = DB::table('terminated_employees')->where('id', $request->terminated_id)->first();
        if ($termination) {
            DB::table('rejected_terminated_employees')->insert([
                'employee_id' => $request->employee_id,
                'reason' => $request->reason,
                'rejected_by' => $request->user_id,
                'terminated_date' => $termination->date,
                'termination_by' => $termination->user_id,
                'termination_details' => $termination->details,
                'termination_reason' => $termination->terminated_reason,
                'employee_name' => $termination->employee_name
            ]);

            DB::table('employee_bvn')->where('id', $request->employee_id)->update([
                'is_terminated_request' => 0
            ]);
        }

        $employee_name = "";

        $emp = DB::table('employee_personal_details')->where('employee_id', $request->employee_id)->first();
        if ($emp) {
            $employee_name = $emp->firstname . " " . $emp->middlename . " " . $emp->lastname;
        }

        $user = DB::table('users')->where('id', $request->user_id)->first();
        $email = new EmailNotify();
        $email->email = $user->email;
        $email->user = $user->name;
        $email->confirmation = 1;
        // $email->bvn = $request->bvn;
        $email->subject = "Termination Rejection Notification";
        $email->body = "This is to notify you that termination of " . $employee_name . " was rejected in the smartHR with this reason: " . $request->reason;

        // $email->transfer_employees = $the_transfer;

        $setting = DB::table('settings_user')->where('user_id', $request->user_id)->first();

        // if ($setting->email_all_activities == 1 || $setting->email_confirmation_approval==1 ) {
        //event
        // event(new SendEmailNotification($email));
        //jobs
        //ispatch(new SendEmailJob($email));
        //notification will be delay for 10mins
        $date = date('Y-m-d H:m:s');
        $carbon_date = Carbon::parse($date);
        //$carbon_date->addHours(1);
        SendEmailJob::dispatch($email)->delay($carbon_date->addMinutes(5));
        //SendEmailJob::dispatch($email);
        // }

        return 1;
    }
    public function BulkApproval($terminate_date, $employee_id)
    {

        $now = date('Y-m-d');
        $future = strtotime($terminate_date);
        $datediff = $future - strtotime($now);
        $day = round($datediff / (60 * 60 * 24));
        $str = $this->generateRandomString(4);
        $terminated_datetime = 'CURRENT_TIMESTAMP + INTERVAL ' . $day . ' DAY';
        DB::unprepared(DB::raw(
            "CREATE EVENT IF NOT EXISTS teminate_" . $str . " 
             ON SCHEDULE AT " . $terminated_datetime . "
             DO
                UPDATE employee_bvn  SET `active` =2, `terminated` = 1 WHERE id = " . $employee_id

        ));
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function approveBulkTermination(Request $request)
    {
        if (count($request->employees) > 0) {
            $name = "";
            foreach ($request->employees as $item) {
                DB::table('terminated_employees')->where('id', $item['terminated_id'])->update([
                    'approve' => 1,
                    'date' => $item['terminated_date'],
                    'approved_date' => date('Y-m-d H:m:s'),
                    'approved_by' => $request->user_id
                ]);
                $employee = DB::table('terminated_employees')->where('id', $item['terminated_id'])->first();
                $name .= $employee->employee_name . ", ";
                $this->BulkApproval($item['terminated_date'], $item['employee_id']);
            }

            $user = DB::table('users')->where('id', $request->user_id)->first();

            //notify admin
            $email = new EmailNotify();
            $email->email = $user->email;
            $email->user = $user->name;
            $email->confirmation = 1;
            $email->subject = "Bulk Approval of termination of " . $name;
            $email->body = "This is to notify you of bulk approval of termination of  " . $name;
            $date = date('Y-m-d H:m:s');
            $carbon_date = Carbon::parse($date);
            SendEmailJob::dispatch($email)->delay($carbon_date->addMinutes(5));
        }

        return 1;
    }
    public function approveTermination(Request $request)
    {
        DB::table('terminated_employees')->where('id', $request->terminated_id)->update([
            'approve' => 1,
            'date' => $request->terminated,
            'approved_date' => date('Y-m-d H:m:s'),
            'approved_by' => $request->user_id
        ]);

        DB::table('employee_bvn')->where('terminate_id', $request->terminated_id)->update([
            'terminated' => 1
        ]);
      

        $employee = DB::table('terminated_employees')->where('id', $request->terminated_id)->first();

        event(new ApprovedTermination($request));

        //perform event here... 
        //send email notifucation here... 
        $user = DB::table('users')->where('id', $request->user_id)->first();

        //notify admin
        //$email = new EmailNotify();
        //$email->email = $user->email;
        //$email->user = $user->name;
        //$email->confirmation = 1;
        //$email->bvn = $request->bvn;
        //$email->subject = "Approval of termination of " . $employee->employee_name;
       //$email->body = "This is to notify you of approval of termination of  " . $employee->employee_name;

        //$setting = DB::table('settings_user')->where('user_id', $request->user_id)->first();
        //$date = date('Y-m-d H:m:s');
        //$carbon_date = Carbon::parse($date);
        //SendEmailJob::dispatch($email)->delay($carbon_date->addMinutes(5));


        
       
        //$terminated->$body = "This is to notify you of approval of termination of  ";
        $userss = new User();
        $userss->email = $user->email;   // This is the email you want to send to.
        $userss->notify(new TerminationBlacklisted($employee->employee_name));


        return 1;
    }

    public function editemployee($id)
    {
        $termination = DB::table('terminated_employees')->where('id', $id)->first();
        $employee_id = $termination->employee_id;
        $personal = DB::table('employee_personal_details')->where('employee_id', $employee_id)->first();
        $contact = DB::table('employee_work_shedules')->where('employee_id', $employee_id)->first();
        $salary = DB::table('employee_salary_details')->where('employee_id', $employee_id)->first();
        $emergency = DB::table('employee_emergency_contacts')->where('employee_id', $employee_id)->get();
        $documents = DB::table('employee_support_documents')->where('employee_id', $employee_id)->get();

        $terminated_documents = DB::table('terminated_employee_documents')->where('employee_id', $employee_id)->get();
        return view('terminate_employees.view_employee', ['employee_id' => $employee_id, 'personal' => $personal, 'contact' => $contact, 'salary' => $salary, 'emergency' => $emergency, 'documents' => $documents, 'termination' => $termination, 'terminated_documents' => $terminated_documents]);
    }

    public function searchByDate($fromdate, $todate)
    {
        $emp = DB::table('terminated_employees')
            //  ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'employee_bvn.id')
            // ->where('employee_bvn.active', 1)
            ->whereBetween('date', [$fromdate, $todate])
            ->select('terminated_employees.employee_id')->get();
        // return response()->json(['query' => $query]);
        $the_employee = array();
        $count = 0;
        if ($emp) {
            foreach ($emp as $value) {
                $count++;
                $employee = new TerminatedEmployee();
                $employee->employee_id = $value->employee_id;
                $personal = DB::table('employee_personal_details')->where('employee_id', $value->employee_id)->first();
                if ($personal) {
                    $employee->name = $personal->lastname . ' ' . $personal->middlename . ' ' . $personal->firstname;
                }
                $work = DB::table('employee_work_shedules')->where('employee_id', $value->employee_id)->first();
                if ($work) {
                    $company = DB::table('companies')->where('id', $work->company)->first();
                    $site = DB::table('sites')->where('id', $work->site)->first();
                    $department = DB::table('departments')->where('id', $work->department)->first();
                    if ($company) {
                        $employee->company = $company->name;
                    }
                    if ($site) {
                        $employee->site = $site->name;
                    }
                    if ($department) {
                        $employee->department = $department->name;
                    }
                    $employee->employee_no = $work->empno;
                }

                $the_employee[] = $employee;
            }
        }
        $employee_count = DB::table('terminated_employees')->count();
        $employee_count_awaiting = DB::table('terminated_employees')->where('approve', 0)->count();
        return response()->json([
            'employees' => $the_employee,
            'employee_count' => $employee_count,
            'employee_count_awaiting' => $employee_count_awaiting
        ]);
    }

    public function getEmployeeforCompany1($company_id, $check)
    {
        //$company = DB::table('employee_work_shedules')->where()
        if ($check == 1) {
            if ($company_id == "0") {
                $emp = DB::table('terminated_employees')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'terminated_employees.employee_id')
                    // ->where('employee_bvn.active', 1)
                    ->whereNotNull('company')
                    ->select('terminated_employees.employee_id')->get();
            } else {
                $emp = DB::table('terminated_employees')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'terminated_employees.employee_id')
                    //  ->where('employee_bvn.active', 1)
                    ->where('employee_work_shedules.company', $company_id)
                    ->select('terminated_employees.employee_id')->get();
            }
        } else if ($check == 2) {
            if ($company_id == "0") {
                $emp = DB::table('terminated_employees')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'terminated_employees.employee_id')
                    // ->where('employee_bvn.active', 1)
                    ->whereNotNull('site')
                    ->select('terminated_employees.employee_id')->get();
            } else {
                $emp = DB::table('terminated_employees')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'terminated_employees.employee_id')
                    // ->where('employee_bvn.active', 1)
                    ->where('employee_work_shedules.site', $company_id)
                    ->select('terminated_employees.employee_id')->get();
            }
        } else {
            if ($company_id == "0") {
                $emp = DB::table('terminated_employees')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'terminated_employees.employee_id')
                    // ->where('employee_bvn.active', 1)
                    ->whereNotNull('department')
                    ->select('terminated_employees.employee_id')->get();
            } else {
                $emp = DB::table('terminated_employees')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'terminated_employees.employee_id')
                    //->where('employee_bvn.active', 1)
                    ->where('employee_work_shedules.department', $company_id)
                    ->select('terminated_employees.employee_id')->get();
            }
        }

        // return response()->json([$emp]);
        $the_employee = array();
        $count = 0;
        if ($emp) {
            foreach ($emp as $value) {
                $count++;
                $employee = new TerminatedEmployee();
                $employee->employee_id = $value->employee_id;
                $personal = DB::table('employee_personal_details')->where('employee_id', $value->employee_id)->first();
                if ($personal) {
                    $employee->name = $personal->lastname . ' ' . $personal->middlename . ' ' . $personal->firstname;
                }
                $work = DB::table('employee_work_shedules')->where('employee_id', $value->employee_id)->first();
                if ($work) {
                    $company = DB::table('companies')->where('id', $work->company)->first();
                    $site = DB::table('sites')->where('id', $work->site)->first();
                    $department = DB::table('departments')->where('id', $work->department)->first();
                    if ($company) {
                        $employee->company = $company->name;
                    }
                    if ($site) {
                        $employee->site = $site->name;
                    }
                    if ($department) {
                        $employee->department = $department->name;
                    }
                    $employee->employee_no = $work->empno;
                }

                $the_employee[] = $employee;
            }
        }

        $employee_count = DB::table('terminated_employees')->count();
        $employee_count_awaiting = DB::table('terminated_employees')->where('approve', 0)->count();

        return response()->json([
            'employees' => $the_employee,
            'employee_count' => $employee_count,
            'employee_count_awaiting' => $employee_count_awaiting
        ]);
    }



    public function pendingTermination()
    {
        $emp = DB::table('employee_bvn')
            ->where([
                'terminated_employees.approve' => 0
            ])
            ->join("terminated_employees", "terminated_employees.id", "employee_bvn.terminate_id")
            ->select("employee_bvn.id", "employee_bvn.employee_number", "employee_bvn.terminate_id")
            ->get();
        $the_employee = array();
        $total_count = 0;
        if ($emp) {
            foreach ($emp as $value) {
                $total_count++;
                $employee = new TerminatedEmployee();
                $employee->employee_id = $value->id;
                $employee->employee_no = $value->employee_number;
                $employee->id = $value->id;
                $employee->check_employee = false;
                $employee->terminated_date = date('Y-m-d');
                $employee->reason = "";
                $employee->terminated_id = $value->terminate_id;
                // $employee->id = $value->id;

                $personal = DB::table('employee_personal_details')->where('employee_id', $value->id)->first();
                if ($personal) {
                    $employee->name = $personal->lastname . ' ' . $personal->middlename . ' ' . $personal->firstname;
                }

                $work = DB::table('employee_work_shedules')->where('employee_id', $value->id)->first();
                if ($work) {
                    $company = DB::table('companies')->where('id', $work->company)->first();
                    $site = DB::table('sites')->where('id', $work->site)->first();
                    $department = DB::table('departments')->where('id', $work->department)->first();
                    if ($company) {
                        $employee->company = $company->name;
                        $employee->company_id = $company->id;
                    }
                    if ($site) {
                        $employee->site = $site->name;
                        $employee->site_id = $site->id;
                    }
                    if ($department) {
                        $employee->department = $department->name;
                        $employee->department_id = $department->id;
                    }
                }

                $the_employee[] = $employee;
            }
        }

        $employee_count = $total_count;
        $companies = DB::table('companies')->get();
        $sites = DB::table('sites')->get();
        $departments = DB::table('departments')->get();

        return view('terminate_employees.pending_terminated', ['employees' => $the_employee, 'employee_count' => $employee_count, 'companies' => $companies, 'sites' => $sites, 'departments' => $departments]);
    }

    public function index()
    {
        $emp = DB::table('terminated_employees')->where('approve', 1)->get();
        $the_employee = array();
        if ($emp) {
            foreach ($emp as $value) {
                $employee = new TerminatedEmployee();
                $employee->employee_id = $value->employee_id;
                $theemp = DB::table('employee_bvn')->where('id', $employee->employee_id)->first();
                if ($theemp) {
                    $employee->employee_no = $theemp->employee_number;
                }

                $employee->id = $value->id;
                $personal = DB::table('employee_personal_details')->where('employee_id', $value->employee_id)->first();
                if ($personal) {
                    $employee->name = $personal->lastname . ' ' . $personal->middlename . ' ' . $personal->firstname;
                }

                $work = DB::table('employee_work_shedules')->where('employee_id', $value->employee_id)->first();
                if ($work) {
                    $company = DB::table('companies')->where('id', $work->company)->first();
                    $site = DB::table('sites')->where('id', $work->site)->first();
                    $department = DB::table('departments')->where('id', $work->department)->first();
                    if ($company) {
                        $employee->company = $company->name;
                        $employee->company_id = $company->id;
                    }
                    if ($site) {
                        $employee->site = $site->name;
                        $employee->site_id = $site->id;
                    }
                    if ($department) {
                        $employee->department = $department->name;
                        $employee->department_id = $department->id;
                    }
                }

                $the_employee[] = $employee;
            }
        }
        $employee_count = DB::table('terminated_employees')->where('approve', 1)->count();
        $employee_count_awaiting = DB::table('terminated_employees')->where('approve', 0)->count();
        $companies = DB::table('companies')->get();
        $sites = DB::table('sites')->get();
        $departments = DB::table('departments')->get();
        return view('terminate_employees.index', ['employees' => $the_employee, 'employee_count' => $employee_count, 'employee_count_awaiting' => $employee_count_awaiting, 'companies' => $companies, 'sites' => $sites, 'departments' => $departments]);
    }


    
}