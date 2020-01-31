<?php
        if($personal) {
            $name = $personal->firstname. ' '. $personal->middlename . ' '. $personal->lastname;
        }else {
            $name = "";
        }
    ?>

<a href="{{url('employees')}}" style="" title="Back to active employee"><span class="fa fa-arrow-left"
        style="color:green; font-size:15px;"> Back to Active Employee</span></a> <br><br>
<?php 
    $is_under_termination = DB::table('employee_bvn')->where('id', $employee_id)->first();
    if($is_under_termination->is_terminated_request == 0) {

     ?>
@if(Auth::user()->role != 2)
<Terminateemployee :employee_name="'{{$name}}'" :employee_id="'{{$employee_id}}'" :user_id="'{{Auth::user()->id}}'">
</Terminateemployee>
@endif

<?php
    }else {
        $terminated2 = DB::table('terminated_employees')->where('id', $is_under_termination->terminate_id)->first();

        if($terminated2->approve == 0) {
            ?>
@if(Auth::user()->role != 2)
<Approvetermination :terminated_id="'{{$is_under_termination->terminate_id}}'"
    :termination_date="'{{$is_under_termination->terminate_request_date}}'" :employee_name="'{{$name}}'"
    :employee_id="'{{$employee_id}}'" :user_id="'{{Auth::user()->id}}'"></Approvetermination>
@endif

<?php
        }else {
            $approved = DB::table('users')->where('id', $terminated2->approved_by)->first();
            $requested = DB::table('users')->where('id', $terminated2->user_id)->first();
            ?>
<span>Termination Approved by : {{$approved->name}} on {{$terminated2->approved_date}}</span> <br> <br>
<span>Termination Requested by {{$requested->name}}</span> <br> <br>

<span style="font-weight:bold; color:red; font-size:14px">This Employee's Contract will be terminated on this date :
    {{$terminated2->date}}</span> <br>
<?php
        }
        ?>





<br>
<a style="color:green; font-size:15px;" href="#terminated"><span class="fa fa-eye"></span> View Termination Details</a>

<?php
    }
    ?>



<nav class="navbar navbar-dark bg-dark" style="background-color: #EDEDEC;">
    <div class="container">
        <h2> Employee Profile Information({{$personal->firstname}} {{$personal->middlename}} {{$personal->lastname}}-<?php 
                        $employeeno = DB::table('employee_bvn')->where('id', $personal->employee_id)->first();
                     ?>
            <?php if($employeeno){
                     echo $employeeno->employee_number; 
                } ?> )</h2>

    </div>
</nav>

<br>

<nav class="navbar navbar-dark bg-dark" style="background-color: #EDEDEC;">

    <ul class="nav  navbar-right  panel_toolbox">
        <li class="nav-item"><a  style="color:black;" class="nav-link" href="{{action('EmployeesController@employee_transfer_history', $employee_id)}}">
                    Transfer History </a></li>
        
        <li class="nav-item"><a style="color:black;" class="nav-link"
                href="{{action('EmployeesController@employee_designation_change_history', $employee_id)}}">
                    Designation Change History</a></li>
       
        <li class="nav-item"><a style="color:black;" class="nav-link" href="{{action('DashboardController@employee_salary_increment', $employee_id)}}">
                    Salary Increment History</a></li>
        
    </ul>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link" href="{{URL::to('employee/'.$personal->employee_id.'/edit')}}">Profile Info</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{URL::to('employee/'.$personal->employee_id.'/work')}}">Work Schedule</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{URL::to('employee/'.$salary->employee_id.'/salary')}}">Salary Detail</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{URL::to('employee/'.$personal->employee_id.'/emergency')}}">Emergency
                contact</a>
        </li>
        <?php 
         $emp = DB::table('employee_bvn')->where('id', $employee_id)->first();
         if($emp->is_terminated_request == 1 ) {
             $termination = DB::table('terminated_employees')->where('id', $emp->terminate_id)->first();
             $terminated_documents= DB::table('terminated_employee_documents')->where('employee_id', $emp->id)->get();
             
             ?>
        <li class="nav-item">
            <a class="nav-link" href="{{URL::to('employee/'.$personal->employee_id.'/terminate')}}">Termination
                Details</a>
        </li>
        <?php
         }
         ?>
    </ul>

</nav>
