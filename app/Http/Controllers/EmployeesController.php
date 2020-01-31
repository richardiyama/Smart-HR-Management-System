<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DateTime;
use App\Day;
use App\Company;
use App\Site;
use App\Department;
use App\Position;
use App\Bank;
use App\PreEmployement;
use App\PositionStatus;
use App\PensionAdministrator;
use Illuminate\Support\Facades\DB;
use Auth;
use App\EmailNotify;
use App\Events\SendEmailNotification;
use App\Jobs\SendEmailJob;
use Carbon\Carbon;
use App\History;
use App\User;
use App\EmployeeHistory;
use App\Notifications\Employee_History;
use App\Notifications\Blacklisted;
use App\BlacklistedEmployeeApprovalComments;



class Activeemployee
{
    public $name;
    public $company;
    public $employee_no;
    public $site;
    public $department;
    public $position;
    public $position_id;
    public $employee_id;
    public $check_employee;
    public $site_id;
    public $company_id;
    public $department_id;
    public $rejection;
    public $is_under_termination;
    public $active;
    public $approve;
}

class Transfer
{
    public $name;
    public $from_company;
    public $to_company;
    public $from_site;
    public $to_site;
    public $from_department;
    public $to_department;
    public $from_position;
    public $to_position;
}

class EmployeesController extends Controller
{
    
 //protected $dates =['date_of_birth'];
    
    public function rejectNewEmployee(Request $request)
    {
        DB::table('employee_bvn')->where('id', $request->employee_id)->update([
            'rejection' => 1,
        ]);
        DB::table('employee_rejected')->insert([
            'employee_id' => $request->employee_id,
            'remarks' => $request->remarks,
            'rejected_by' => $request->user_id,
            'rejected_date' => date('Y-m-d'),
        ]);

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
        $email->subject = "Employee Rejection Notification";
        $email->body = "This is to notify you of rejection of   " . $employee_name . "  in the smartHR with this reason:  " . $request->remarks;

        // $email->transfer_employees = $the_transfer;

        $setting = DB::table('settings_user')->where('user_id', $request->user_id)->first();

        // if ($setting->email_all_activities == 1 || $setting->email_confirmation_approval == 1) {
        $date = date('Y-m-d H:m:s');
        $carbon_date = Carbon::parse($date);
        //$carbon_date->addHours(1);
        SendEmailJob::dispatch($email)->delay($carbon_date->addMinutes(5));
        //SendEmailJob::dispatch($email);
        //  }
        return 1;
    }
    public function confirmNewEmployee(Request $request)
    {
        DB::table('employee_bvn')->where('id', $request->employee_id)->update([
            'active' => 1,
            'pending' => 0,
            'approved_by' => $request->user_id,
            'approved_date' => date('Y-m-d')
        ]);

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
        $email->subject = "Employee Confirmation Notification";
        $email->body = "This is to notify you of confirmation of   " . $employee_name . "  in the smartHR. ";

        // $email->transfer_employees = $the_transfer;

        $setting = DB::table('settings_user')->where('user_id', $request->user_id)->first();

        // if ($setting->email_all_activities == 1 || $setting->email_confirmation_approval == 1) {
        $date = date('Y-m-d H:m:s');
        $carbon_date = Carbon::parse($date);
        //$carbon_date->addHours(1);
        SendEmailJob::dispatch($email)->delay($carbon_date->addMinutes(5));
        //SendEmailJob::dispatch($email);
        // }





        return 1;
    }
    public function getNewEmpMonth()
    {
        $this_month = date('n');
        $emp = DB::table('employee_bvn')->whereMonth('created', $this_month)->get();
        $new_employees_count = DB::table('employee_bvn')->whereMonth('created', $this_month)->count();
        $the_employee = array();
        if ($emp) {
            foreach ($emp as $value) {
                $employee = new Activeemployee();
                $employee->check_employee = false;
                $employee->rejection = $value->rejection;
                $employee->employee_id = $value->id;
                $employee->active = $value->active;
                $employee->employee_no = $value->employee_number;

                $personal = DB::table('employee_personal_details')->where('employee_id', $value->id)->first();
                if ($personal) {
                    $employee->name = $personal->lastname . ' ' . $personal->middlename . ' ' . $personal->firstname;
                }

                $work = DB::table('employee_work_shedules')->where('employee_id', $value->id)->first();
                if ($work) {
                    $company = DB::table('companies')->where('id', $work->company)->first();
                    $site = DB::table('sites')->where('id', $work->site)->first();
                    $department = DB::table('departments')->where('id', $work->department)->first();
                    $position = DB::table('positions')->where('id', $work->job_position)->first();
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

                    if($postion){
                        $employee->postion = $position->name;
                        $employee->position_id = $position->id;
                    }
                }

                $the_employee[] = $employee;
            }
        }

        /*return response()->json([
            $the_employee
        ]);
        */

        return response()->json([
            'employees' => $the_employee,
            'employee_counting' => $new_employees_count
        ]);
    }
    public function getPending()
    {
        $emp = DB::table('employee_bvn')->where('pending', 1)->get();
        $total_pending = DB::table('employee_bvn')->where('pending', 1)->count();
        $this_month = date('n');
        $new_employees = DB::table('employee_bvn')->whereMonth('created', $this_month)->get();
        $the_employee = array();
        if ($emp) {
            foreach ($emp as $value) {
                $employee = new Activeemployee();
                $employee->check_employee = false;
                $employee->rejection = $value->rejection;
                $employee->employee_id = $value->id;
                $employee->employee_no = $value->employee_number;
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

        /*return response()->json([
            $the_employee
        ]);
        */

        $employee_count = DB::table('employee_bvn')->where('active', 1)->count();
        $current_year = date('Y');
        $employee_year_count = DB::table('employee_bvn')->where('active', 1)->whereYear('created', $current_year)->count();
        $companies = DB::table('companies')->get();
        $sites = DB::table('sites')->get();
        $departments = DB::table('departments')->get();
        $jobs = DB::table('positions')->get();
        return view('smart_employees.pending', ['employees' => $the_employee, 'employee_count' => $employee_count, 'employee_year_count' => $employee_year_count, 'companies' => $companies, 'sites' => $sites, 'departments' => $departments, 'jobs' => $jobs, 'total_pending' => $total_pending, 'new_employees' => $new_employees]);
    }
    public function submitTransferEmployees(Request $request)
    {
        $the_transfer = array();
        foreach ($request->employees as $item) {
            //before transfer
            $transfer = new Transfer();
            $employee = DB::table('employee_personal_details')->where('employee_id', $item['employee_id'])->first();
            $name = "";
            if ($employee) {
                $name = $employee->firstname . ' ' . $employee->middlename . ' ' . $employee->lastname;
            }
            $transfer->name = $name;
            $company = DB::table('companies')->where('id', $request->company)->first();
            $site = DB::table('sites')->where('id', $request->site)->first();
            $department = DB::table('departments')->where('id', $request->department)->first();
            $position = DB::table('positions')->where('id', $request->job)->first();
            if ($company) {
                $transfer->from_company = $company->name;
            }
            if ($site) {
                $transfer->from_site = $site->name;
            }
            if ($department) {
                $transfer->from_department = $department->name;
            }
            if ($position) {
                $transfer->from_position = $position->name;
            }

            DB::table('employee_work_shedules')->where('employee_id', $item['employee_id'])->update([
                'company' => $request->company,
                'site' => $request->site,
                'department' => $request->department,
                //'job_position' => $request->job
            ]);

            $company = DB::table('companies')->where('id', $request->company)->first();
            $site = DB::table('sites')->where('id', $request->site)->first();
            $department = DB::table('departments')->where('id', $request->department)->first();
            $position = DB::table('positions')->where('id', $request->job)->first();
            if ($company) {
                $transfer->to_company = $company->name;
            }
            if ($site) {
                $transfer->to_site = $site->name;
            }
            if ($department) {
                $transfer->to_department = $department->name;
            }
            if ($position) {
                $transfer->to_position = $position->name;
            }
            
            $type = "Transfer";
            $employee_history = new EmployeeHistory([
            'type' => $type,
            'emp_id'=>$employee->employee_id,
            'employee_name'=> $name,
            'employee_company'=> $transfer->to_company,
            'employee_department' => $transfer->to_department,
            'employee_site'=>  $transfer->to_site


     
     
     
         ]);
         
           
         
         
        
         $employee_history->save();
            $the_transfer[] = $transfer;
        }
        $employee_name = "";
        $j = 0;
        foreach ($the_transfer as $item) {
            if ($j > 0) {
                $name .= ",";
            }
            $employee_name = $item->name;
            
            

            $j++;
        }
        
       
        $user = DB::table('users')->where('id', $request->user_id)->first();
        $email = new EmailNotify();
        $email->email = $user->email;
        $email->user = $user->name;
        //$email->bvn = $request->bvn;
        $email->subject = "Transfer Employee Notification";
        $email->confirmation = 0;
        $email->body = "This is to notify you of employee ( " . $employee_name . ") transfer in the smartHR. ";

        // $email->transfer_employees = $the_transfer;

        $setting = DB::table('settings_user')->where('user_id', $request->user_id)->first();

        // if ($setting && $setting->email_all_activities == 1) {
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
        //}



        return 1;
    }
    public function terminateContract(Request $request)
    {
        $terminate_id = DB::table('terminated_employees')->insertGetId([
            'employee_id' => $request->employee_id,
            'reason' => $request->reason,
            'details' => $request->details,
            'date' => $request->date,
            'user_id' => $request->user_id,
            'employee_name' => $request->employee_name,
            'terminated_reason'  => $request->terminated_reason
        ]);

        DB::table('employee_bvn')->where('id', $request->employee_id)->update([
            'is_terminated_request' => 1,
            'terminate_request_date' => $request->date,
            'terminate_id' => $terminate_id
        ]);

        $name = "";
        $staff = DB::table('employee_personal_details')->where('employee_id', $request->employee_id)->first();
        if ($staff) {
            $name = $staff->firstname . " " . $staff->middlename . " " . $staff->lastname;
        }

        //send the notificattion
        $user = DB::table('users')->where('id', $request->user_id)->first();

        //notify admin
        $email = new EmailNotify();
        $email->email = $user->email;
        $email->user = $user->name;
        $email->confirmation = 1;
        $email->subject = "Employee Termination Request";
        $email->body = "This is to notify you of employee (" . $name . ") termination request on this date " . $request->date . "  waiting for approval.";

        $setting = DB::table('settings_user')->where('user_id', $request->user_id)->first();

        //if ($setting->email_all_activities == 1 || $setting->email_confirmation_approval == 1) {
        $date = date('Y-m-d H:m:s');
        $carbon_date = Carbon::parse($date);
        //$carbon_date->addHours(1);
        SendEmailJob::dispatch($email)->delay($carbon_date->addMinutes(5));
        //  }



        return 1;
    }
    public function searchByDate($fromdate, $todate)
    {
        $emp = DB::table('employee_bvn')
            ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'employee_bvn.id')
            ->where('employee_bvn.active', 1)
            ->whereBetween('start_date', [$fromdate, $todate])
            ->select('employee_bvn.id')->get();
        // return response()->json(['query' => $query]);
        $the_employee = array();
        $count = 0;
        if ($emp) {
            foreach ($emp as $value) {
                $count++;
                $employee = new Activeemployee();
                $employee->employee_id = $value->id;
                $employee->check_employee = false;
                $employee->employee_no = $value->employee_number;
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
                    }
                    if ($site) {
                        $employee->site = $site->name;
                    }
                    if ($department) {
                        $employee->department = $department->name;
                    }
                }

                $the_employee[] = $employee;
            }
        }
        return response()->json([
            'employees' => $the_employee,
            'employee_count' => $count
        ]);
    }
    public function getEmployeeforCompany($company_id, $check)
    {
        //$company = DB::table('employee_work_shedules')->where()
        if ($check == 1) {
            if ($company_id == "0") {
                $emp = DB::table('employee_bvn')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'employee_bvn.id')
                    ->where('employee_bvn.active', 1)
                    ->whereNotNull('company')
                    ->select('employee_bvn.id')->get();
            } else {
                $emp = DB::table('employee_bvn')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'employee_bvn.id')
                    ->where('employee_bvn.active', 1)
                    ->where('employee_work_shedules.company', $company_id)
                    ->select('employee_bvn.id')->get();
            }
        } else if ($check == 2) {
            if ($company_id == "0") {
                $emp = DB::table('employee_bvn')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'employee_bvn.id')
                    ->where('employee_bvn.active', 1)
                    ->whereNotNull('site')
                    ->select('employee_bvn.id')->get();
            } else {
                $emp = DB::table('employee_bvn')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'employee_bvn.id')
                    ->where('employee_bvn.active', 1)
                    ->where('employee_work_shedules.site', $company_id)
                    ->select('employee_bvn.id')->get();
            }
        } else {
            if ($company_id == "0") {
                $emp = DB::table('employee_bvn')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'employee_bvn.id')
                    ->where('employee_bvn.active', 1)
                    ->whereNotNull('department')
                    ->select('employee_bvn.id')->get();
            } else {
                $emp = DB::table('employee_bvn')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'employee_bvn.id')
                    ->where('employee_bvn.active', 1)
                    ->where('employee_work_shedules.department', $company_id)
                    ->select('employee_bvn.id')->get();
            }
        }

        // return response()->json([$emp]);
        $the_employee = array();
        $count = 0;
        if ($emp) {
            foreach ($emp as $value) {
                $count++;
                $employee = new Activeemployee();
                $employee->employee_id = $value->id;
                $employee->check_employee = false;
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
        return response()->json([
            'employees' => $the_employee,
            'employee_count' => $count
        ]);
    }
    public function updateEmployee1(Request $request)
    {

        $driver_license = $request->driver_license == 'on' ? 1 : 0;
        DB::table('employee_personal_details')->updateOrInsert(
            ['employee_id' => $request->employee_id],
            [
                'firstname' => strtoupper($request->firstname),
                'middlename' => strtoupper($request->middlename),
                'lastname' => strtoupper($request->lastname),
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'driver_license' => $driver_license,
                'phone' => $request->phone,
                'driver_license_no' => $request->driver_license_no,
                'updated_at' => date('Y-m-d H:m:s'),
                'updated_by' => Auth::user()->id,
                'employee_id' => $request->employee_id
            ]
        );

        $work_time = $request->work_time == 'on' ? 1 : 0;
        $overtime = $request->overtime == 'on' ? 1 : 0;

        DB::table('employee_work_shedules')->updateOrInsert(
            ['employee_id' => $request->employee_id],
            [
                'company' => $request->company,
                'site' => $request->site,
                'department' => $request->department,
                'job_position' => $request->position,
                'empno' => $request->empno,
                'start_date' => $request->start_date,
                'work_time' => $work_time,
                'moday_morning' => $request->moday_morning,
                'moday_evening' => $request->moday_evening,
                'tuesday_morning' => $request->tuesday_morning,
                'tuesday_evening' => $request->tuesday_evening,
                'wednesday_morning' => $request->wednesday_morning,
                'wednesday_evening' => $request->wednesday_evening,
                'thurseday_morning' => $request->thurseday_morning,
                'thurseday_evening' => $request->thurseday_evening,
                'friday_morning' => $request->friday_morning,
                'friday_evening' => $request->friday_evening,
                'saturday_morning' => $request->saturday_morning,
                'saturday_evening' => $request->saturday_evening,
                'sunday_morning' => $request->sunday_morning,
                'sunday_evening' => $request->sunday_evening,
                'overtime' => $overtime,
                'employee_id' => $request->employee_id
            ]
        );

        DB::table('employee_salary_details')->updateOrInsert(
            ['employee_id' => $request->employee_id],
            [
                'salary' => $request->salary,
                'bank' => $request->bank,
                'pfa' => $request->pfa,
                'account_number' => $request->account_number,
                'pension_number' => $request->pension_number,
                'next_kin_name' => $request->next_kin_name,
                'next_kin_phone' => $request->next_kin_phone,
                'next_kin_relationship' => $request->next_kin_relationship,
                'next_kin_address' => $request->next_kin_address,
                'employee_id' => $request->employee_id
            ]
        );

        DB::table('employee_bvn')->where('id', $request->employee_id)->update([
            'updated' => date('Y-m-d H:m:s'),
            'updated_by' => Auth::user()->id
        ]);

        $name = $request->firstname . " " . $request->middlename . " " . $request->lastname;


        return redirect('employee/' . $request->employee_id . '/edit')->with('status', 'Employee : ' . $name . ' record updated successfully');
    }
    public function addemergency(Request $request)
    {
        DB::table('employee_emergency_contacts')->insert([
            'name' => $request->name,
            'phone' => $request->phone,
            'employee_id' => $request->employee_id
        ]);
        $contacts = DB::table('employee_emergency_contacts')->where('employee_id', $request->employee_id)->orderby('id', 'asc')->get();
        return response()->json([
            'contacts' => $contacts
        ]);
    }
    public function deleteemergencyContact($id, $employee_id)
    {
        DB::table('employee_emergency_contacts')->where('id', $id)->delete();
        $contacts = DB::table('employee_emergency_contacts')->where('employee_id', $employee_id)->get();
        return response()->json([
            'contacts' => $contacts
        ]);
    }
    public function editemergencyContact($id, $name, $phone, $employee_id)
    {
        DB::table('employee_emergency_contacts')->where('id', $id)->update([
            'name' => $name,
            'phone' => $phone
        ]);
        $contacts = DB::table('employee_emergency_contacts')->where('employee_id', $employee_id)->get();
        return response()->json([
            'contacts' => $contacts
        ]);
    }
    public function emergencyContact($employee_id)
    {
        $contacts = DB::table('employee_emergency_contacts')->where('employee_id', $employee_id)->get();
        return response()->json([
            'contacts' => $contacts
        ]);
    }
    public function updateemployee($employee_id)
    {
        // return view('smart_employees.update_employee');
        $personal = DB::table('employee_personal_details')->where('employee_id', $employee_id)->first();
        $contact = DB::table('employee_work_shedules')->where('employee_id', $employee_id)->first();
        $salary = DB::table('employee_salary_details')->where('employee_id', $employee_id)->first();
        $emergency = DB::table('employee_emergency_contacts')->where('employee_id', $employee_id)->get();
        $documents = DB::table('employee_support_documents')->where('employee_id', $employee_id)->get();
        return view('smart_employees.update_employee', ['employee_id' => $employee_id, 'personal' => $personal, 'contact' => $contact, 'salary' => $salary, 'emergency' => $emergency, 'documents' => $documents]);
    }
    public function approveemployee($employee_id)
    {
        $personal = DB::table('employee_personal_details')->where('employee_id', $employee_id)->first();
        $contact = DB::table('employee_work_shedules')->where('employee_id', $employee_id)->first();
        $salary = DB::table('employee_salary_details')->where('employee_id', $employee_id)->first();
        $emergency = DB::table('employee_emergency_contacts')->where('employee_id', $employee_id)->get();
        $documents = DB::table('employee_support_documents')->where('employee_id', $employee_id)->get();
        return view('smart_employees.approve_employee', ['employee_id' => $employee_id, 'personal' => $personal, 'contact' => $contact, 'salary' => $salary, 'emergency' => $emergency, 'documents' => $documents]);
    }

    public function employeeworkview($employee_id){
        $personal = DB::table('employee_personal_details')->where('employee_id', $employee_id)->first();
        $contact = DB::table('employee_work_shedules')->where('employee_id', $employee_id)->first();
        $salary = DB::table('employee_salary_details')->where('employee_id', $employee_id)->first();
        $emergency = DB::table('employee_emergency_contacts')->where('employee_id', $employee_id)->get();
        $documents = DB::table('employee_support_documents')->where('employee_id', $employee_id)->get();
        return view('smart_employees.employee_workschedule',['employee_id' => $employee_id, 'personal' => $personal, 'contact' => $contact, 'salary' => $salary, 'emergency' => $emergency, 'documents' => $documents]);
    }

    public function employeesalaryview($employee_id){
        $personal = DB::table('employee_personal_details')->where('employee_id', $employee_id)->first();
        $contact = DB::table('employee_work_shedules')->where('employee_id', $employee_id)->first();
        $salary = DB::table('employee_salary_details')->where('employee_id', $employee_id)->first();
        $emergency = DB::table('employee_emergency_contacts')->where('employee_id', $employee_id)->get();
        $documents = DB::table('employee_support_documents')->where('employee_id', $employee_id)->get();
        return view('smart_employees.employee_salary_info',['employee_id' => $employee_id, 'personal' => $personal, 'contact' => $contact, 'salary' => $salary, 'emergency' => $emergency, 'documents' => $documents]);
    }

    public function employeeemergencyview($employee_id){
        $personal = DB::table('employee_personal_details')->where('employee_id', $employee_id)->first();
        $contact = DB::table('employee_work_shedules')->where('employee_id', $employee_id)->first();
        $salary = DB::table('employee_salary_details')->where('employee_id', $employee_id)->first();
        $emergency = DB::table('employee_emergency_contacts')->where('employee_id', $employee_id)->get();
        $documents = DB::table('employee_support_documents')->where('employee_id', $employee_id)->get();
        return view('smart_employees.emergency_contact',['employee_id' => $employee_id, 'personal' => $personal, 'contact' => $contact, 'salary' => $salary, 'emergency' => $emergency, 'documents' => $documents]);
    }
    public function employeeterminatedview($employee_id){
        $personal = DB::table('employee_personal_details')->where('employee_id', $employee_id)->first();
        $contact = DB::table('employee_work_shedules')->where('employee_id', $employee_id)->first();
        $salary = DB::table('employee_salary_details')->where('employee_id', $employee_id)->first();
        $emergency = DB::table('employee_emergency_contacts')->where('employee_id', $employee_id)->get();
        $documents = DB::table('employee_support_documents')->where('employee_id', $employee_id)->get();
        return view('smart_employees.employee_termination_details',['employee_id' => $employee_id, 'personal' => $personal, 'contact' => $contact, 'salary' => $salary, 'emergency' => $emergency, 'documents' => $documents]);
    }

    
    public function editemployee($employee_id)
    {
        //$terminated2 = DB::table('terminated_employees')->where('employee_id', $employee_id)->first();

        $personal = DB::table('employee_personal_details')->where('employee_id', $employee_id)->first();
        $contact = DB::table('employee_work_shedules')->where('employee_id', $employee_id)->first();
        $salary = DB::table('employee_salary_details')->where('employee_id', $employee_id)->first();
        $emergency = DB::table('employee_emergency_contacts')->where('employee_id', $employee_id)->get();
        $documents = DB::table('employee_support_documents')->where('employee_id', $employee_id)->get();
        //if($terminated2){
            // $value = $terminated2->approve;
       //}
        ///if($value == 0){
        return view('smart_employees.edit_employee', ['employee_id' => $employee_id, 'personal' => $personal, 'contact' => $contact, 'salary' => $salary, 'emergency' => $emergency, 'documents' => $documents]);
       // }
    
    }
    public function deleteEmployeeDocuments($id, $employee_id)
    {
        DB::table('employee_support_documents')->where('id', $id)->delete();
        $employee_documents = DB::table('employee_support_documents')->where('employee_id', $employee_id)->get();

        return view('smart_employees.employee_emergency_detail', ['id' => $employee_id, 'success' => 'Employee documents deleted successfully', 'employee_documents' => $employee_documents]);
    }
    public function deleteEmployeeDocuments1($id, $employee_id)
    {
        DB::table('employee_support_documents')->where('id', $id)->delete();
        $employee_documents = DB::table('employee_support_documents')->where('employee_id', $employee_id)->get();

        return redirect()->back()->with('status', 'Employee documents deleted successfully');

        //return view('smart_employees.employee_emergency_detail', ['id' => $employee_id, 'success' => 'Employee documents deleted successfully', 'employee_documents' => $employee_documents]);
    }
    public function deleteTerminatedDocument($id, $employee_id)
    {
        DB::table('terminated_employee_documents')->where('id', $id)->delete();
        $employee_documents = DB::table('terminated_employee_documents')->where('employee_id', $employee_id)->get();

        return response()->json([
            'employee_documents' => $employee_documents
        ]);
    }
    public function updateTerminatedDocument(Request $request)
    {
        //terminated_employee_documents
        $my_default_file = "empty_file.png";
        if ($files = $request->file('file')) {
            $destinationPath = 'storage/assets/'; // upload path
            $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);
            // $insert['image'] = "$profileImage";
            $my_default_file = $profileImage;
        }
        DB::table('terminated_employee_documents')->insert([
            'employee_id' => $request->employee_id,
            'file' => $my_default_file,
            'document_title' => $request->document_title
        ]);

        $employee_documents = DB::table('terminated_employee_documents')->where('employee_id', $request->employee_id)->get();

        return response()->json([
            'employee_documents' => $employee_documents
        ]);
    }
    public function add_employee_support_document1(Request $request)
    {
        $my_default_file = "empty_file.png";
        if ($files = $request->file('file')) {
            $destinationPath = 'storage/assets/'; // upload path
            $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);
            // $insert['image'] = "$profileImage";
            $my_default_file = $profileImage;
        }
        DB::table('employee_support_documents')->insert([
            'employee_id' => $request->employee_id,
            'user_id' => $request->user_id,
            'file' => $my_default_file,
            'title' => $request->title
        ]);

        return redirect()->back()->with('status', 'Employee documents has been added successfully');
    }

    public function add_employee_support_document(Request $request)
    {
        $my_default_file = "empty_file.png";
        if ($files = $request->file('file')) {
            $destinationPath = 'storage/assets/'; // upload path
            $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);
            // $insert['image'] = "$profileImage";
            $my_default_file = $profileImage;
        }
        DB::table('employee_support_documents')->insert([
            'employee_id' => $request->employee_id,
            'user_id' => $request->user_id,
            'file' => $my_default_file,
            'title' => $request->title
        ]);

        $employee_documents = DB::table('employee_support_documents')->where('employee_id', $request->employee_id)->get();

        $emergencies = DB::table('employee_emergency_contacts')->where('employee_id', $request->employee_id)->get();



        return view('smart_employees.employee_emergency_detail', ['id' => $request->employee_id, 'success' => 'Employee documents has been added successfully', 'documents' => $employee_documents, 'emergencies' => $emergencies]);
    }
    public function add_employee_emergency(Request $request)
    {
        DB::table('employee_emergency_contacts')->insert($request->all());
        //return back();

        $employee_documents = DB::table('employee_support_documents')->where('employee_id', $request->employee_id)->get();

        $emergencies = DB::table('employee_emergency_contacts')->where('employee_id', $request->employee_id)->get();

        return view('smart_employees.employee_emergency_detail', ['id' => $request->employee_id, 'success' => 'Employee emergency details added successfully, you can add another if there is', 'documents' => $employee_documents, 'emergencies' => $emergencies]);
    }
    public function add_employee_salary(Request $request)
    {
        $check = DB::table('employee_salary_details')->where('employee_id', $request->employee_id)->count();
        if ($check == 0) {
            DB::table('employee_salary_details')->insert($request->all());
        } else {
            DB::table('employee_salary_details')->where('employee_id', $request->employee_id)->update($request->all());
        }

        $emergencies = DB::table('employee_emergency_contacts')->where('employee_id', $request->employee_id)->get();

        if ($emergencies) {
            $documents = DB::table('employee_support_documents')->where('employee_id', $request->employee_id)->get();
            return view('smart_employees.employee_emergency_detail', ['id' => $request->employee_id, 'emergencies' => $emergencies, 'documents' => $documents]);
        } else {
            return view('smart_employees.employee_emergency_detail', ['id' => $request->employee_id]);
        }
    }
    public function addWorkshedule(Request $request)
    {
        //return $request->all();
        $check = DB::table('employee_work_shedules')->where('employee_id', $request->employee_id)->count();
        $employee = DB::table('employee_personal_details')->where('employee_id',$request->employee_id)->first();
        $employee_position = DB::table('employee_work_shedules')->where('employee_id',$request->employee_id)->first();

        if($employee){
        $name = $employee->firstname . ' ' . $employee->middlename . ' ' . $employee->lastname;
        }
        if($employee_position){
           // $pos = DB::table('positions')->where('id',$employee_position->job_position)->first();
           $pos = $employee_position->job_position;
        }
        if($pos){
            $job_pos = (int)$pos;

            $changed_designation = DB::table('positions')->where('id',$job_pos)->first();
        }



        if ($check == 0) {
            DB::table('employee_work_shedules')->insert($request->all());
        } else {
            DB::table('employee_work_shedules')->where('employee_id', $request->employee_id)->update($request->all());
            $type = "Designation Changed";
            $history = new History([
            'type' => $type,
            'emp_id'=>$employee->employee_id,
            'employee_name'=> $name,
            'employee_position'=> $changed_designation->name
            ]);
         
            
         
        
            $history->save();
        }

        $salary = DB::table('employee_salary_details')->where('employee_id', $request->employee_id)->first();
        if ($salary) {
            return view('smart_employees.employee_salary', ['id' => $request->employee_id, 'salary' => $salary]);
        } else {
            return view('smart_employees.employee_salary', ['id' => $request->employee_id]);
        }
    }
    public function add_employee_position($employee_id)
    {
        return view('smart_employees.company_position', ['id' => $employee_id]);
    }


    public function employee_designation_change_history($employe_id){

       
        $designations = DB::table('histories')->where('emp_id',$employe_id)->get();
        
        
        

        
        
        return view('history.employee_designation_change_history',compact('designations'));
        
        
    }

    public function employee_transfer_history($employe_id){

       
        $transfers = DB::table('employee_histories')->where('emp_id',$employe_id)->get();
        
        
        

        
        
        return view('history.employee_transfer_history',compact('transfers'));
        
        
    }

public function send_blacklisted_approval(Request $request)
{
    $blacklist = DB::table('terminated_employees')->where('employee_id', $request->employee_id)->first();
    $check = DB::table('employee_personal_details')->where('employee_id', $request->employee_id)->first();
    if($check){
        $name = $check->lastname . ' ' . $check->middlename . ' ' . $check->firstname;
    }

    $user = DB::table('users')->get();

    if($blacklist)
    {
        
        $approve = $blacklist->approve;
        if($approve == 1)
    {

    foreach($user as $official)
    {
        if($official->role == 2)
        {
        $userss = new User();
        $userss->email = $official->email;   // This is the email you want to send to.
        $userss->notify(new Blacklisted($name));
        }
    }
        return redirect()->back()->with('status','Notification sent to management for re-employement');
    }
    }

    
}

public function blacklisted_employee_approval_view()
{
    $blacklists = DB::table('terminated_employees')->where('approve',1)->get();
    
       
            return view('pre_employment.blacklisted_employee_approval_view', compact('blacklists'));
        
    

    
}
public function blacklisted_employee_approval($id)
{

    DB::table('terminated_employees')->where('id', $id)->update([
        'approve' => 0
        
    ]);

    DB::table('employee_bvn')->where('terminate_id', $id)->update([
        'terminated' => 0
    ]);
  
    $employee = DB::table('terminated_employees')->where('id', $id)->first();
   if($employee)
   {
    return view ('pre_employment.comment',['id' => $id, 'employee_name'=> $employee->employee_name]);
    }
}

public function add_comments(Request $request)
{
    $employee = DB::table('terminated_employees')->where('id', $request->id)->first();
    
    $this->validate($request,[
        'comment' => 'required'
        
     
]);

if($employee)
{
$blacklist = new BlacklistedEmployeeApprovalComments([
       'comment' =>strip_tags(preg_replace('/\s+/', ' ', $request->get('comment'))),
       'employee_name' => $employee->employee_name



]);

$blacklist->save();
}
return redirect()->back()->with('status','Blacklisted employee have been approved for re-employement');

}
    public function add_employee_detail(Request $request)
    {

        Validator::extend('olderThan', function($attribute, $value, $parameters)
        {
            $minAge = ( ! empty($parameters)) ? (int) $parameters[0] : 18;
            return (new DateTime)->diff(new DateTime($value))->y >= $minAge;
        
            // or the same using Carbon:
            // return Carbon\Carbon::now()->diff(new Carbon\Carbon($value))->y >= $minAge;
        });

        
        
        $this->validate($request,[
           'firstname' => 'required|min:2|max:25',
           'lastname' => 'required|min:2|max:25',
           'middlename' => 'required|min:2|max:25',
           'nationality' => 'required|min:2|max:25',
           'email'    => 'required|email',
           'country_code' => 'required|regex:/^\+\d{1,3}$/',
           'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
           'date_of_birth' => 'required|date|olderThan',
            
        ],
    [
        'date_of_birth.older_than' => 'The Employee must be 18 years and above to be employed',
    ]);
    
    //$request->date_of_birth = Carbon::createFromFormat('Y-m-d')

        $check = DB::table('employee_personal_details')->where('employee_id', $request->employee_id)->count();
        if ($check == 0) {
            DB::table('employee_personal_details')->insert($request->all());
        } else {
            DB::table('employee_personal_details')->where('employee_id', $request->employee_id)->update($request->all());
        }
        //return 1;
        $name = $request->lastname . ' ' . $request->middlename . ' ' . $request->firstname;
        $username = Auth::user()->name;
        $logs = "New employee (" . $name . ") were added into the system by " . $username;
        DB::table('logs')->insert([
            'logs' => $logs
        ]);
        
        
        // dd($personal);


        $work = DB::table('employee_work_shedules')->where('employee_id', $request->employee_id)->first();
        if ($work) {
            return view('smart_employees.company_position', ['id' => $request->employee_id, 'work' => $work]);
        }
       
        else {
            return view('smart_employees.company_position', ['id' => $request->employee_id]);
        }
    }
    public function employeeDetails($id)
    {
        $personal = DB::table('employee_personal_details')->where('employee_id', $id)->first();

        $blacklist = DB::table('terminated_employees')->where('employee_id', $id)->first();
        $user = DB::table('users')->where('role', 2)->first();

        if($blacklist)
        {
            
            $approve = $blacklist->approve;
            
        }
        // dd($personal);

        if ($personal) {
            //$name = $personal->firstname .' ' . $personal->middlename .' '. $personal->lastname;
            return view('smart_employees.employee_personal_detail', ['id' => $id, 'personal' => $personal,'approve'=>$approve]);
            
        } 
        else {
            return view('smart_employees.new_employee_personal_detail', ['id' => $id]);
        }
    }
   public function addEmployeeBvn(Request $request)
    {
        $Pre_employement = DB::table('pre_employments')->where('pre_emp_code', $request->code)->first();

        if($Pre_employement)
        {

            
    
            $employee = DB::table('employee_bvn')->where('bvn', $request->bvn)->first();

            
      
        
            if ($employee) {
    
    
                $personal = DB::table('employee_personal_details')->where('employee_id', $employee->id)->first();

            $blacklist = DB::table('terminated_employees')->where('employee_id',$employee->id)->first();
              
                $emp = DB::table('employee_personal_details')->where('employee_id', $employee->id)->first();
                $name = "";
                $approve = "";
                if ($emp) {
                    $name = $emp->lastname . ' ' . $emp->middlename . ' ' . $emp->firstname;
                   
                }
               
               
                //return response()->json([
                   // 'check' => 1,
                    //'employee_id' => $employee->id,
                   // 'name' => $name,
               // ]);
                
               if($blacklist)
               {
                   
                   $approve = $blacklist->approve;
                   
               }
               // dd($personal);
       
               if ($personal) {
                

                $companies = DB::table('companies')->get();
              
                foreach ($companies as $company) {
                    $comp = new Company();
                    $comp->email = $company->email;   // This is the email you want to send to.
                     $comp->notify(new Employee_History($request->bvn));
                }
                   //$name = $personal->firstname .' ' . $personal->middlename .' '. $personal->lastname;

               DB::table('employee_bvn')->where('bvn', $request->bvn)->update([
                    'pre_emp_code' => $request->code,
                ]);
                DB::table('pre_employments')->where('pre_emp_code', $request->code)->delete([ 
                    'pre_emp_code' => $request->code,
                ]);
                   return view('smart_employees.employee_personal_detail', ['id' => $employee->id, 'personal' => $personal,'approve'=>$approve]);
                   
               } 
        }else {
                $last = DB::table('employee_bvn')->latest('id')->first();
                $initial = 0000007;
                if ($last && $last->employee_number) {
                    $new_emp = (float) $last->employee_number + 7;
                } else {
                    $new_emp = $initial;
                }
                $the_employee_number = sprintf("%07d", $new_emp);
    
                $employee_id = DB::table('employee_bvn')->insertGetId([
                    'bvn' => $request->bvn,
                    'user_id' => $request->user_id,
                    'employee_number' => $the_employee_number,
                ]);
    
                //add default work period,
                DB::table('employee_work_shedules')->insert([
                    'empno' => $the_employee_number,
                    'work_time' => 1,
                    'moday_morning' => 1,
                    'moday_evening' => 1,
                    'tuesday_morning' => 1,
                    'tuesday_evening' => 1,
                    'wednesday_morning' => 1,
                    'wednesday_evening' => 1,
                    'thurseday_morning' => 1,
                    'thurseday_evening' => 1,
                    'friday_morning' => 1,
                    'friday_evening' => 1,
                    'saturday_morning' => 1,
                    'saturday_evening' => 1,
                    'sunday_morning' =>1,
                    'sunday_evening' => 1,
                    'overtime' => 0,
                    'employee_id' => $employee_id
                ]);
    
    
                $user = DB::table('users')->where('id', $request->user_id)->first();
    
                
    
                //notify admin
               // $email = new EmailNotify();
               // $email->email = $user->email;
                //$email->user = $user->name;
                //$email->bvn = $request->bvn;
                //$email->confirmation = 1;
                //$email->subject = "New Employee Notification with BVN: " . $request->bvn;
                //$email->body = "This is to notify you of new employee with BVN of " . $request->bvn . " waiting for approval. <br> <br> Regards <br /> " . $user->name;
    
                //$setting = DB::table('settings_user')->where('user_id', $request->user_id)->first();
    
                //  if ($setting && $setting->email_all_activities == 1) {
                //event
                // event(new SendEmailNotification($email));
                //jobs
                //ispatch(new SendEmailJob($email));
                //notification will be delay for 10mins
                //$date = date('Y-m-d H:m:s');
               // $carbon_date = Carbon::parse($date);
                //$carbon_date->addHours(1);
                //SendEmailJob::dispatch($email)->delay($carbon_date->addMinutes(5));
                //  SendEmailJob::dispatch($email);
                // }
    
    
               
                    
                 
                $companies = DB::table('companies')->get();
              
                foreach ($companies as $company) {
                    $comp = new Company();
                    $comp->email = $company->email;   // This is the email you want to send to.
                     $comp->notify(new Employee_History($request->bvn));
                }
    
                DB::table('employee_bvn')->where('bvn', $request->bvn)->update([
                    'pre_emp_code' => $request->code,
                ]);
                DB::table('pre_employments')->where('pre_emp_code', $request->code)->delete([ 
                    'pre_emp_code' => $request->code,
                ]);
                return view('smart_employees.new_employee_personal_detail', ['id' => $employee_id]);
    
    
                //return response()->json([
                   // 'check' => 0,
                   // 'employee_id' => $employee_id,
                   // 'name' => "",
               // ]);
            }
            
       // $user = DB::table('users')->where('role', 2)->first();

        
       
            
        }



       
        else
        {
                $job_title =  Position::all();
                $site = Site::all();
                $section = Department::all();
                $position_status = PositionStatus::all();
                
                echo "<script>";
                echo "alert('This pre-employement code is not valid..Click Ok to proceed to Pre-Employement request process');";
                echo "</script>";
                return view('pre_employment.create',compact('job_title','site','section','position_status'));
        }

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emp = DB::table('employee_bvn')
            ->where([
                'active' => 1,
            ])->get();
        $the_employee = array();
        if ($emp) {
            foreach ($emp as $value) {
                $employee = new Activeemployee();
                $employee->check_employee = false;
                $employee->employee_id = $value->id;
                $employee->is_under_termination = $value->is_terminated_request;
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

                $employee->employee_no = $value->employee_number;

                $the_employee[] = $employee;
            }
        }

        /*return response()->json([
            $the_employee
        ]);
        */

        $employee_count = DB::table('employee_bvn')->where('active', 1)->count();
        $current_year = date('Y');
        $employee_year_count = DB::table('employee_bvn')->where('active', 1)->whereYear('created', $current_year)->count();
        $companies = DB::table('companies')->get();
        $sites = DB::table('sites')->get();
        $departments = DB::table('departments')->get();
        $jobs = DB::table('positions')->get();
        $pending = DB::table('employee_bvn')->where('pending', 1)->count();

        
        
    return view('smart_employees.index', ['pending' => $pending, 'employees' => $the_employee, 'employee_count' => $employee_count, 'employee_year_count' => $employee_year_count, 'companies' => $companies, 'sites' => $sites, 'departments' => $departments, 'jobs' => $jobs]);
        


       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employees.create.index');
    }




    public function personalDetails(Request $request)
    { }

    public function salary()
    {
        $banks = Bank::all();
        $pensions = PensionAdministrator::all();
        return view('employees.salary.index')->with('banks', $banks)->with('pensions', $pensions);
    }

    public function nextKin()
    {
        return view('employees.nextKin.index');
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

class Addemp
{
    public $user;
    public $bvn;
    public $subject;
    public $body;
    public $email;
}