<?php

namespace App\Http\Controllers;

use App\PreEmployement;
use App\Position;
use App\PositionStatus;
use App\Department;
use App\Site;
//use App\PreEmployment;
use Illuminate\Support\Facades\Validator;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\EmailNotify;
use App\Events\SendEmailNotification;
use App\Jobs\SendEmailJob;
use Carbon\Carbon;
use App\Notifications\PreEmploymentNotification;
use App\User;

class PreEmployementController extends Controller
{


    public function addPreEmployementCodeView()
    {
        

        $pre_employments = DB::table('pre_employments')->where('project_manager_approval',1)->where('hr_manager_approval',1)->get();
      
        return view('smart_employees.addPreEmployementCodeView',compact('pre_employments'));
       
    }

 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$pre_employments = PreEmployement::all();

        $pre_employments = DB::table('pre_employments')->where('project_manager_approval',0)->get();
       
        return view('pre_employment.index', compact('pre_employments'));
        
    
    }

    public function approvedPre_employment_request()
    {
        //$pre_employments = PreEmployement::all();

        $pre_employments = DB::table('pre_employments')->where('project_manager_approval',1)->where('hr_manager_approval',1)->get();
       
        return view('pre_employment.approvedPre_employment_request', compact('pre_employments'));
        
    
    }



    public function hr_manager_approval()
    {

        $hr_pre_employments = DB::table('pre_employments')->where('hr_manager_approval',0)->get();
       
            return view('pre_employment.hr_manager_approval', compact('hr_pre_employments'));
        
            
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $job_title =  Position::all();
        $site = Site::all();
        $section = Department::all();
        $position_status = PositionStatus::all();
        

        return view('pre_employment.create',compact('job_title','site','section','position_status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
        'job_title'=> 'required',
        'section'=> 'required',
        'site'=> 'required',
        'position_status'=> 'required',
        'start_date'=> 'required',
        'amount'=> 'required',
        
         
    ]);
   
    $randomid = mt_rand(10,99); 
    $initial = 2002;
    $code = $initial.$randomid;
    
    $pre_employment = new PreEmployement([
           'job_title' => $request->get('job_title'),
           'section' => $request->get('section'),
           'site' => $request->get('site'),
           'position_status' => $request->get('position_status'),
           'start_date' => $request->get('start_date'),
           'amount' => $request->get('amount'),
           'request_supervisor' => Auth::user()->name,
           'pre_emp_code' => $code,
           'project_manager_approval' => 0,
           'hr_manager_approval' => 0


    ]);
    
    $pre_employment->save();
    //$userId = Auth::user()->id;
    
    $createDate = DB::table('pre_employments')->where('id', $pre_employment->id)->first();
    if($createDate){
        $date_value = $createDate->created_at;
    }

$current_date = Carbon::createFromFormat("Y-m-d H:i:s", Carbon::now());
//$when = Carbon::createFormFormat("Y-m-d H:i:s",$date_value)->toDateTimeString();
$when = Carbon::parse($date_value)->toDateTimeString();
$weekDay = date('w', strtotime($date_value));
if($weekDay == 0 || $weekDay == 6)
{
    $saturday = $weekDay;
}

  $to = Carbon::parse($current_date);
   //$from =Carbon::parse($date_value);

  // $diff_in_hours = $to->diffInHours($from);
    
  //$date = strtotime($date_value);
    //$remaining = $date - time();

   // $hours_remaining = floor(($remaining % 86400) / 3600);

  // $pre_employ = DB::table('pre_employments')->where('id', $pre_employment->id)->first();
   //if($pre_employ)
  // {
      // $pre_employ->hr_manager_approval = 1;
       //$pre_employ->project_manager_approval = 1;
       //$pre_employ->update()->delay($when->toDateTimeString()->addSeconds(10));
  // }


  
    

  //DB::table('pre_employments')->where('id', $pre_employment->id)->update([
       //'hr_manager_approval' => 1,
       //'project_manager_approval' => 1
        
    //])->delay($when)->toDateTimeString();
    
  
  
    //$pre_employ = delay(PreEmployement::find($pre_employment->id));
    //$pre_employ->$when->addSeconds(10);
    //$pre_employ->hr_manager_approval = 1;
   //$pre_employ->project_manager_approval = 1;
    //$pre_employ->save();


   //if($weekDay == 0 || $weekDay == 6)
   //{
   // DB::table('pre_employments')->where('id',  $pre_employment->id)->update([
      //  'hr_manager_approval' => 1,
      // 'project_manager_approval' => 1
        
   // ]);
  // }
  

    $approval_one = DB::table('role')->where('id', 13)->first();
    $approval_two = DB::table('role')->where('id', 3)->first();




    if($approval_one){
        $user = DB::table('users')->where('role',$approval_one->id)->first();

        $userss = new User();
        $userss->email = $user->email;   // This is the email you want to send to.
        $userss->notify(new PreEmploymentNotification($code));
    }


    if($approval_two){
        $second_user = DB::table('users')->where('role',$approval_two->id)->first();

        //notify Project Manager
        $userss = new User();
        $userss->email = $second_user->email;   // This is the email you want to send to.
        $userss->notify(new PreEmploymentNotification($code));;
    }

    

    return redirect()->back()->with('success','Pre employment request sent for approval!');
    
    
    }


    public function pre_employment_hr_manager_approval($id)
    {
        $current_date = Carbon::createFromFormat("Y-m-d H:i:s", Carbon::now());

        //DB::table('pre_employments')->where('id', $id)->update([
           // 'hr_manager_approval' => 1
            
        //]);
        
       
      
        DB::table('pre_employments')->where('id', $id)->update([
            'hr_manager_approval' => 1,
            'hr_manager_approval_date'=> $current_date
            
        ]);
       
        //$employee = DB::table('terminated_employees')->where('id', $id)->first();
       //if($employee)
       //{
        return redirect()->back()->with('success','Pre employment approved!');
       // }
    }

    public function pre_employment_project_manager_approval($id)
    {


        $current_date = Carbon::createFromFormat("Y-m-d H:i:s", Carbon::now());

      
        DB::table('pre_employments')->where('id', $id)->update([
            'project_manager_approval' => 1,
            'project_manager_approval_date' => $current_date
            
        ]);
       
        //$employee = DB::table('terminated_employees')->where('id', $id)->first();
       //if($employee)
      // {
        return redirect()->back()->with('success','Pre employment approved!');
        //}

        
    }

   

    /**
     * Display the specified resource.
     *
     * @param  \App\PreEmployement  $preEmployement
     * @return \Illuminate\Http\Response
     */
    public function show(PreEmployement $preEmployement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PreEmployement  $preEmployement
     * @return \Illuminate\Http\Response
     */
    public function edit(PreEmployement $preEmployement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PreEmployement  $preEmployement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PreEmployement $preEmployement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PreEmployement  $preEmployement
     * @return \Illuminate\Http\Response
     */
    public function destroy(PreEmployement $preEmployement)
    {
        //
    }


    
}

