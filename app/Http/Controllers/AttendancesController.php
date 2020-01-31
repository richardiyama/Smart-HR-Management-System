<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\EmailNotify;
use Carbon\Carbon;
use App\Jobs\SendEmailJob;

class Attendance2
{
    public $sno;
    public $name;
    public $company;
    public $present;
    public $absent;
    public $time_in;
    public $time_out;
    public $employee_id;
    public $employee_no;
    public $time_in2; // hold the initial value for me
    public $time_out2; //hold the initial value for time out
    public $department;
    public $site;
    public $id;
    public $attendance;
    public $site_work;
    public $department_work;
    public $total_emp_work;

    public $site_id;
    public $department_id;
    public $company_id;
}

class Download
{
    public $date;
    public $created_by;
    public $created;
    public $file;
}

class MyAttendance
{
    public $attendances;
    public $downloads;
}

class AttendancesController extends Controller
{
    public function attendancerecord()
    {
       
        $the_employee = array();
        $companies_count = DB::table('companies')->count();
        $companies = DB::table('companies')->get();
        $sites = DB::table('sites')->get();
        $departments = DB::table('departments')->get();
        return view('attendance.attendanceRecord', ['companies' => $companies, 'sites' => $sites, 'departments' => $departments, 'employees' => $the_employee, 'companies_count' => $companies_count]);
    }
    public function getattendancefromdate($fromdate, $todate)
    {
        $emp = DB::table('attendance_records')->join('attendances', 'attendances.id', 'attendance_records.attendance_id')->whereBetween('attendances.date', [$fromdate, $todate])->select('attendance_records.id', 'attendance_records.employee_id', 'attendance_records.present', 'attendance_records.absent', 'attendance_records.time_in', 'attendance_records.time_out', 'attendance_records.attendance_id', 'attendances.site_id', 'attendances.date')->get();
        $the_employee = array();
        $count = 0;
        $total_hr = 0;
        if ($emp) {
            foreach ($emp as $value) {
                $count++;
                $employee = new Attendance2();
                $employee->sno = $count;
                $employee->employee_id = $value->employee_id;
                $employee->id = $value->id;
                $employee->attendance = date('jS F, Y', strtotime($value->date));
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
                    if ($department) {
                        $employee->department = $department->name;
                        $employee->department_id = $department->id;
                    }
                    if ($site) {
                        $employee->site = $site->name;
                        $employee->site_id = $site->id;
                    }
                    $employee1 = DB::table('employee_bvn')->where('id', $value->employee_id)->first();

                    $employee->employee_no = $employee1->employee_number;
                }
                $employee->time_in = $value->time_in;
                $employee->time_out = $value->time_out;
                $employee->time_in2 = $value->time_in;
                $employee->time_out2 = $value->time_out;
                $employee->present = $value->present;
                $employee->absent = $value->absent;
                //time in and time out this employe


                if ($value->present == 1) {
                    $start = strtotime($value->time_in);
                    $end = strtotime($value->time_out);
                    $total = $end - $start;
                    $employee->total_emp_work = round(abs($total / 3600), 2);
                    $total_hr += $total;
                }

                $the_employee[] = $employee;
            }
        }
        $hours_worked = round(abs($total_hr / 3600), 2);

        return response()->json([
            'employees' => $the_employee,
            //'downloads' => $the_download,
            'total_worked' => $hours_worked
        ]);
    }
    public function getPastattendanceMonth(Request $request)
    {
        $attend = $this->getPastAttend($request);
        return response()->json([
            'employees' => $attend->attendances,
            'downloads' => $attend->downloads
        ]);
    }
    public function getPastattendanceDay(Request $request)
    {
        $attend = $this->getPastAttend($request);
        return response()->json([
            'employees' => $attend->attendances,
            'downloads' => $attend->downloads
        ]);
    }



    public function getPastAttend($request = null)
    {
        if ($request->day == null) {
            $emp = DB::table('attendance_records')
                ->join('attendances', 'attendances.id', 'attendance_records.attendance_id')
                ->whereYear('attendances.date', $request->year)
                ->whereMonth('attendances.date', $request->month)
                ->select('attendance_records.employee_id', 'attendance_records.id', 'attendances.date', 'time_in', 'time_out', 'present', 'absent', 'file', 'attendances.user_id', 'attendances.created')
                ->get();
        } else {
            $emp = DB::table('attendance_records')
                ->join('attendances', 'attendances.id', 'attendance_records.attendance_id')
                ->whereYear('attendances.date', $request->year)
                ->whereMonth('attendances.date', $request->month)
                ->whereDay('attendances.date', $request->day)
                ->select('attendance_records.employee_id', 'attendance_records.id', 'attendances.date', 'time_in', 'time_out', 'present', 'absent', 'file', 'attendances.user_id', 'attendances.created')
                ->get();
        }


        $the_employee = array();
        $the_download = array();
        $count = 0;
        if ($emp) {
            foreach ($emp as $value) {
                $count++;
                $employee = new Attendance2();
                $employee->sno = $count;
                $employee->employee_id = $value->employee_id;
                $employee->id = $value->id;
                $employee->attendance = date('jS F, Y', strtotime($value->date));
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
                    if ($department) {
                        $employee->department = $department->name;
                        $employee->department_id = $department->id;
                    }
                    if ($site) {
                        $employee->site = $site->name;
                        $employee->site_id = $site->id;
                    }
                    $employee->employee_no = $work->empno;
                }
                $employee->time_in = $value->time_in;
                $employee->time_out = $value->time_out;
                $employee->time_in2 = $value->time_in;
                $employee->time_out2 = $value->time_out;
                $employee->present = $value->present;
                $employee->absent = $value->absent;
                //time in and time out this employe
                $the_employee[] = $employee;

                $download = new Download();
                $download->date = $value->date;
                $user = DB::table('users')->where('id', $value->user_id)->first();
                if ($user) {
                    $download->created_by = $user->name;
                }
                $download->created = $value->created;
                $download->file = $value->file;
                $the_download[] = $download;
            }
        }


        $attendance = new MyAttendance();
        $attendance->attendances = $the_employee;
        $attendance->downloads = $the_download;

        return $attendance;
    }

    public function PastAttendance()
    {
        //$the_employee = array();

        //$attendance = $this->getPastAttend();
        $employees = array();
        $downloads = array();

        $companies = DB::table('companies')->get();
        $sites = DB::table('sites')->get();
        $departments = DB::table('departments')->get();
        return view('attendance.pastAttendance', ['companies' => $companies, 'sites' => $sites, 'departments' => $departments, 'employees' => $employees, 'downloads' => $downloads]);
    }
    public function updateTodayAttendance(Request $request)
    {
        //check if the attendance has already been uploaded
        $check_attendance = DB::table('attendances')->where('date', $request->date)->first();
        if ($check_attendance) {
            DB::table('attendance_records')->where('attendance_id', $check_attendance->id)->delete();
            DB::table('attendances')->where('id', $check_attendance->id)->delete();
        }

        // return $request->all();
        $my_default_file = "empty_file.png";
        if ($files = $request->file('file')) {
            $destinationPath = 'storage/assets/'; // upload path
            $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);
            // $insert['image'] = "$profileImage";
            $my_default_file = $profileImage;
        }
        $attendance_id = DB::table('attendances')->insertGetId([
            'date' => $request->date,
            'user_id' => $request->user_id,
            'file' => $my_default_file,
            'site_id' => $request->selected_site
        ]);

        $employees = json_decode($request->employees);
        foreach ($employees as $emp) {
            DB::table('attendance_records')->insert([
                'attendance_id' => $attendance_id,
                'employee_id' => $emp->employee_id,
                'present' => $emp->present,
                'absent' => $emp->absent,
                'time_in' => $emp->time_in,
                'time_out' => $emp->time_out
            ]);
        }

        //updates
        $user = DB::table('users')->where('id', $request->user_id)->first();
        $username = $user->name;
        $logs = "New attendance(" . $request->date . ") were created and uploaded by " . $username;
        DB::table('logs')->insert([
            'logs' => $logs
        ]);

        $site = DB::table('sites')->where('id', $request->selected_site)->first();
        $site_name = "";
        if ($site) {
            $site_name = $site->name;
        }

        //send email here...
        // $user = DB::table('users')->where('id', $request->user_id)->first();
        $email = new EmailNotify();
        $email->email = $user->email;
        $email->user = $user->name;
        $email->confirmation = 1;
        $email->subject = $site_name . " Attendance Notification";
        $email->body = "This is to notify you new attendance upload for " . $site_name . " in the smartHR. ";


        $setting = DB::table('settings_user')->where('user_id', $request->user_id)->first();
        $date = date('Y-m-d H:m:s');
        $carbon_date = Carbon::parse($date);
        SendEmailJob::dispatch($email)->delay($carbon_date->addMinutes(5));




        return 1;
    }

    public function getEmployeeforCompany2($company_id, $check)
    {
        //$company = DB::table('employee_work_shedules')->where()
        $total_site_hours2 = 0;
        $site_name = "";
        if ($check == 1) {
            if ($company_id == "0") {
                $emp = DB::table('attendance_records')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'attendance_records.employee_id')
                    //->where('employee_bvn.active', 1)
                    ->whereNotNull('company')->select('attendance_records.id', 'attendance_records.employee_id', 'attendance_records.present', 'attendance_records.absent', 'attendance_records.time_in', 'attendance_records.time_out', 'attendance_records.attendance_id')->get();
            } else {
                $emp = DB::table('attendance_records')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'attendance_records.employee_id')
                    //->where('employee_bvn.active', 1)
                    ->where('employee_work_shedules.company', $company_id)
                    ->select('attendance_records.id', 'attendance_records.employee_id', 'attendance_records.present', 'attendance_records.absent', 'attendance_records.time_in', 'attendance_records.time_out', 'attendance_records.attendance_id')->get();
            }
        } else if ($check == 2) {
            if ($company_id == "0") {
                $emp = DB::table('attendance_records')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'attendance_records.employee_id')
                    //  ->where('employee_bvn.active', 1)
                    ->whereNotNull('site')
                    ->select('attendance_records.id', 'attendance_records.employee_id', 'attendance_records.present', 'attendance_records.absent', 'attendance_records.time_in', 'attendance_records.time_out', 'attendance_records.attendance_id')->get();
            } else {
                $emp = DB::table('attendance_records')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'attendance_records.employee_id')
                    //->where('employee_bvn.active', 1)
                    ->where('employee_work_shedules.site', $company_id)
                    ->select('attendance_records.id', 'attendance_records.employee_id', 'attendance_records.present', 'attendance_records.absent', 'attendance_records.time_in', 'attendance_records.time_out', 'attendance_records.attendance_id')->get();

                $sitehours = DB::table('attendance_records')->join('attendances', 'attendances.id', 'attendance_records.attendance_id')->where('site_id', $company_id)->get();
                $total_site_hours = 0;
                if ($sitehours) {
                    foreach ($sitehours as $value) {
                        if ($value->absent == 0 && $value->time_out != null) {
                            $start = strtotime($value->time_in);
                            $end = strtotime($value->time_out);
                            $total = $end - $start;
                            $total_site_hours += $total;
                        }
                    }
                }
                $site = DB::table('sites')->where('id', $company_id)->first();
                $total_site_hours2 = round(abs($total_site_hours / 3600), 2);
                if ($site) {
                    $site_name = $site->name;
                }
            }
        } else {
            if ($company_id == "0") {
                $emp = DB::table('attendance_records')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'attendance_records.employee_id')
                    // ->where('employee_bvn.active', 1)
                    ->whereNotNull('department')
                    ->select('attendance_records.id', 'attendance_records.employee_id', 'attendance_records.present', 'attendance_records.absent', 'attendance_records.time_in', 'attendance_records.time_out', 'attendance_records.attendance_id')->get();
            } else {
                $emp = DB::table('attendance_records')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'attendance_records.employee_id')
                    // ->where('employee_bvn.active', 1)
                    ->where('employee_work_shedules.department', $company_id)
                    ->select('attendance_records.id', 'attendance_records.employee_id', 'attendance_records.present', 'attendance_records.absent', 'attendance_records.time_in', 'attendance_records.time_out', 'attendance_records.attendance_id')->get();
            }
        }


        $the_employee = array();
        $count = 0;
        if ($emp) {
            foreach ($emp as $value) {
                $count++;
                $employee = new Attendance2();
                $employee->sno = $count;
                $employee->id = $value->id;
                $employee->employee_id = $value->employee_id;
                $attendance_date = DB::table('attendances')->where('id', $value->attendance_id)->first();
                if ($attendance_date) {
                    $employee->attendance = date('jS F, Y', strtotime($attendance_date->date));
                }

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
                    if ($department) {
                        $employee->department = $department->name;
                    }
                    if ($site) {
                        $employee->site = $site->name;
                    }
                    $employee->employee_no = $work->empno;
                }

                //get total hour worked for employee
                $manhours =  DB::table('attendance_records')->join('attendances', 'attendances.id', 'attendance_records.attendance_id')->where('employee_id', $value->employee_id)->get();
                $total_manhour_worked = 0;
                if ($manhours) {
                    foreach ($manhours as $man) {
                        if ($man->absent == 0 && $man->time_out != null) {
                            $start = strtotime($man->time_in);
                            $end = strtotime($man->time_out);
                            $total = $end - $start;
                            $total_manhour_worked += $total;
                        }
                    }
                }
                $hours_worked = round(abs($total_manhour_worked / 3600), 2);
                $employee->total_emp_work = $hours_worked;

                if ($value->time_in) {
                    $employee->time_in = $value->time_in;
                    $employee->time_in2 = $value->time_in;
                }

                if ($value->time_out) {
                    $employee->time_out = $value->time_out;
                    $employee->time_out2 = $value->time_out;
                }


                $employee->present = $value->present;
                $employee->absent = $value->absent;
                //time in and time out this employe
                $the_employee[] = $employee;
            }
        }

        //get the hourse for the selected site
        $site = DB::table('sites')->where('id', $company_id)->first();
        if ($site) {
            $site_name = $site->name;
        }


        return response()->json([
            'employees' => $the_employee,
            //'employee_count' => $count
            'site_hours' => $total_site_hours2,
            'site_name' => $site_name,
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
                    ->select('employee_bvn.id', 'employee_number')->get();
            } else {
                $emp = DB::table('employee_bvn')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'employee_bvn.id')
                    ->where('employee_bvn.active', 1)
                    ->where('employee_work_shedules.company', $company_id)
                    ->select('employee_bvn.id', 'employee_number')->get();
            }
        } else if ($check == 2) {
            if ($company_id == "0") {
                $emp = DB::table('employee_bvn')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'employee_bvn.id')
                    ->where('employee_bvn.active', 1)
                    ->whereNotNull('site')
                    ->select('employee_bvn.id', 'employee_number')->get();
            } else {
                $emp = DB::table('employee_bvn')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'employee_bvn.id')
                    ->where('employee_bvn.active', 1)
                    ->where('employee_work_shedules.site', $company_id)
                    ->select('employee_bvn.id', 'employee_number')->get();
            }
        } else {
            if ($company_id == "0") {
                $emp = DB::table('employee_bvn')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'employee_bvn.id')
                    ->where('employee_bvn.active', 1)
                    ->whereNotNull('department')
                    ->select('employee_bvn.id', 'employee_number')->get();
            } else {
                $emp = DB::table('employee_bvn')
                    ->join('employee_work_shedules', 'employee_work_shedules.employee_id', 'employee_bvn.id')
                    ->where('employee_bvn.active', 1)
                    ->where('employee_work_shedules.department', $company_id)
                    ->select('employee_bvn.id', 'employee_number')->get();
            }
        }

        // return response()->json([$emp]);
        $the_employee = array();
        $count = 0;
        if ($emp) {
            foreach ($emp as $value) {
                $count++;
                $employee = new Attendance2();
                $employee->sno = $count;
                $employee->employee_id = $value->id;
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
                    if ($department) {
                        $employee->department = $department->name;
                    }
                    if ($site) {
                        $employee->site = $site->name;
                    }
                    $employee->employee_no = $value->employee_number;
                    $day = date('l');
                    switch ($day) {
                        case "Monday":
                            $employee->time_in = $this->gettime($work->moday_morning, 0);
                            $employee->time_in2 = $this->gettime($work->moday_morning, 0);
                            $employee->time_out = $this->gettime($work->moday_evening, 1);
                            $employee->time_out2 = $this->gettime($work->moday_evening, 1);
                            break;
                        case "Tuesday":
                            $employee->time_in = $this->gettime($work->tuesday_morning, 0);
                            $employee->time_out = $this->gettime($work->tuesday_evening, 1);
                            $employee->time_in2 = $this->gettime($work->tuesday_morning, 0);
                            $employee->time_out2 = $this->gettime($work->tuesday_evening, 1);
                            break;
                        case "Wednesday":
                            $employee->time_in = $this->gettime($work->wednesday_morning, 0);
                            $employee->time_out = $this->gettime($work->wednesday_evening, 1);
                            $employee->time_in2 = $this->gettime($work->wednesday_morning, 0);
                            $employee->time_out2 = $this->gettime($work->wednesday_evening, 1);
                            break;
                        case "Thursday":
                            $employee->time_in = $this->gettime($work->thurseday_morning, 0);
                            $employee->time_out = $this->gettime($work->thurseday_evening, 1);
                            $employee->time_in2 = $this->gettime($work->thurseday_morning, 0);
                            $employee->time_out2 = $this->gettime($work->thurseday_evening, 1);
                            break;
                        case "Friday":
                            $employee->time_in = $this->gettime($work->friday_morning, 0);
                            $employee->time_out = $this->gettime($work->friday_evening, 1);
                            $employee->time_in2 = $this->gettime($work->friday_morning, 0);
                            $employee->time_out2 = $this->gettime($work->friday_evening, 1);
                            break;
                        case "Saturday":
                            $employee->time_in = $this->gettime($work->saturday_morning, 0);
                            $employee->time_out = $this->gettime($work->saturday_evening, 1);
                            $employee->time_in2 = $this->gettime($work->saturday_morning, 0);
                            $employee->time_out2 = $this->gettime($work->saturday_evening, 1);
                            break;
                        case "Sunday":
                            $employee->time_in = $this->gettime($work->sunday_morning, 0);
                            $employee->time_out = $this->gettime($work->sunday_evening, 1);
                            $employee->time_in2 = $this->gettime($work->sunday_morning, 0);
                            $employee->time_out2 = $this->gettime($work->sunday_evening, 1);
                            break;
                    }
                }
                $employee->present = 1;
                $employee->absent = 0;
                //time in and time out this employe
                $the_employee[] = $employee;
            }
        }
        return response()->json([
            'employees' => $the_employee,
            'employee_count' => $count
        ]);
    }


    public function index()
    {

        /*   $emp = DB::table('employee_bvn')->where('active', 1)->get();
        $the_employee = array();
        $count = 0;
        if ($emp) {
            foreach ($emp as $value) {
                $count++;
                $employee = new Attendance2();
                $employee->employee_no = $value->employee_number;
                $employee->sno = $count;
                $employee->employee_id = $value->id;
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
                    if ($department) {
                        $employee->department = $department->name;
                    }
                    if ($site) {
                        $employee->site = $site->name;
                    }
                    // $employee->employee_no = $work->empno;
                    $day = date('l');
                    switch ($day) {
                        case "Monday":
                            if ($work->moday_morning) {
                                $employee->time_in = $this->gettime($work->moday_morning, 0);
                                $employee->time_in2 = $this->gettime($work->moday_morning, 0);
                            }

                            if ($work->moday_evening) {
                                $employee->time_out = $this->gettime($work->moday_evening, 1);
                                $employee->time_out2 = $this->gettime($work->moday_evening, 1);
                            }

                            break;
                        case "Tuesday":

                            if ($work->tuesday_morning) {
                                $employee->time_in = $this->gettime($work->tuesday_morning, 0);
                                $employee->time_in2 = $this->gettime($work->tuesday_morning, 0);
                            }

                            if ($work->tuesday_evening) {
                                $employee->time_out = $this->gettime($work->tuesday_evening, 1);
                                $employee->time_out2 = $this->gettime($work->tuesday_evening, 1);
                            }



                            break;
                        case "Wednesday":

                            if ($work->wednesday_morning) {
                                $employee->time_in = $this->gettime($work->wednesday_morning, 0);
                                $employee->time_in2 = $this->gettime($work->wednesday_morning, 0);
                            }

                            if ($work->wednesday_evening) {
                                $employee->time_out = $this->gettime($work->wednesday_evening, 1);

                                $employee->time_out2 = $this->gettime($work->wednesday_evening, 1);
                            }


                            break;
                        case "Thursday":

                            if ($work->thurseday_morning) {
                                $employee->time_in = $this->gettime($work->thurseday_morning, 0);
                                $employee->time_in2 = $this->gettime($work->thurseday_morning, 0);
                            }

                            if ($work->thurseday_evening) {
                                $employee->time_out = $this->gettime($work->thurseday_evening, 1);

                                $employee->time_out2 = $this->gettime($work->thurseday_evening, 1);
                            }



                            break;
                        case "Friday":

                            if ($work->friday_morning) {

                                $employee->time_in = $this->gettime($work->friday_morning, 0);
                                $employee->time_in2 = $this->gettime($work->friday_morning, 0);
                            }

                            if ($work->friday_evening) {

                                $employee->time_out = $this->gettime($work->friday_evening, 1);

                                $employee->time_out2 = $this->gettime($work->friday_evening, 1);
                            }


                            break;
                        case "Saturday":

                            if ($work->saturday_morning) {

                                $employee->time_in = $this->gettime($work->saturday_morning, 0);
                                $employee->time_in2 = $this->gettime($work->saturday_morning, 0);
                            }

                            if ($work->saturday_evening) {

                                $employee->time_out = $this->gettime($work->saturday_evening, 1);
                                $employee->time_out2 = $this->gettime($work->saturday_evening, 1);
                            }


                            break;
                        case "Sunday":

                            if ($work->sunday_morning) {
                                $employee->time_in = $this->gettime($work->sunday_morning, 0);
                                $employee->time_in2 = $this->gettime($work->sunday_morning, 0);
                            }

                            if ($work->sunday_evening) {
                                $employee->time_out = $this->gettime($work->sunday_evening, 1);

                                $employee->time_out2 = $this->gettime($work->sunday_evening, 1);
                            }



                            break;
                    }
                }
                $employee->present = 1;
                $employee->absent = 0;
                //time in and time out this employe
                $the_employee[] = $employee;
            }
        }

        /*return response()->json([
            'employee' => $the_employee,
            'day' => $day
        ]);
        */

        $the_employee = array();

        $companies = DB::table('companies')->get();
        $sites = DB::table('sites')->get();
        $departments = DB::table('departments')->get();
        return view('attendance.index', ['companies' => $companies, 'sites' => $sites, 'departments' => $departments, 'employees' => $the_employee]);
    }

    public function gettime($time_id, $period)
    {
        if ($time_id) {
            if ($period == 0) { //morning
                $morning =  DB::table('morning_period')->where('id', $time_id)->first();
                return $morning->time;
            } else {
                $evening =  DB::table('evening_period')->where('id', $time_id)->first();
                return $evening->time;
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
